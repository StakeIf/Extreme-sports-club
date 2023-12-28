<?php

require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'Db.php');


abstract class QueryDb {

    /**
     * кол-во записей в таблице
     * @var int|null $itemsCount
     */
    protected ?int $itemsCount = null;

    /**
     * был ли выполнен FetchAll
     * @var bool $hasFetch
     */
    protected bool $hasFetch = false;

    /**
     * Название таблицы, с которой работаем
     * @var string $tableName
     */
    protected string $tableName;

    /**
     * Какие данные будут выбраны, по умолчанию ВСЕ
     * @var array $select
     */
    protected array $select = ['*'];

    /**
     * Объединения таблиц, имеет вид ['НАЗВАНИЕ_ТАБЛИЦЫ' => '[INNER|CROSS] JOIN]']
     * @var array $join
     */
    protected array $join = [];

    /**
     * Фильтры, имеет вид ['СТОЛБЕЦ' => 'ЧЕМУ_РАВЕН']
     * @var array $filter
     */
    protected array $filter = [];

    /**
     * Сортировка
     * @var array $sort
     */
    protected array $sort;

    /**
     * Сколько вывести строк
     * @var int|null $limit
     */
    protected ?int $limit = null;

    /**
     * Начиная с какой выводить строки
     * @var int|null $offset
     */
    protected ?int $offset = null;

    /**
     * База данных - подключение
     * @var Db $connection
     */
    protected static Db $connection;

    public function __construct(string $tableName) {
        static::$connection = Db::getInstance();
        $this->tableName = $tableName;
        $this->sort = ['pk_' . $tableName => 'DESC'];
    }

    /**
     * @return static
     */
    abstract static function get(): static;

    /**
     * @param array $select
     * @return QueryDb
     */
    public function select(array $select): static {
        $this->select = $select;

        return $this;
    }

    /**
     * @param array $join
     * @return QueryDb
     */
    public function join(array $join): static {
        $this->join = $join;

        return $this;
    }

    /**
     * @param array $filter
     * @return QueryDb
     */
    public function where(array $filter): static {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param array $sort
     * @return QueryDb
     */
    public function sort(array $sort): static {
        $this->sort = $sort;
        return $this;
    }

    /**
     * @param int|null $limit
     * @return QueryDb
     */
    public function limit(?int $limit): static {
        $this->limit = $limit;
        return $this;
    }

    /**
     * @param int|null $offset
     * @return QueryDb
     */
    public function offset(?int $offset): static {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return string
     */
    public function getTableName(): string {
        return $this->tableName;
    }

    /**
     * Получить кол-во записей в таблице
     * @return int
     */
    public function getItemsCount(): int {
        return $this->itemsCount;
    }

    /**
     * Приводит поля к формату таблица.столбец
     * @param string $filed
     * @return string
     */
    protected function checkTable(string $filed): string {
        $check = explode('.', $filed);
        if (count($check) > 1) {
            if (isset($this->join)) {
                foreach ($this->join as $alias => $value) {
                    if ($check[0] == $value['model']::get()->getTableName()) {
                        $order = $alias . '.' . $check[1];
                        break;
                    }
                    $order = $this->getTableName() . '.' . $check[1];
                }

            } else {
                $order = $filed;
            }
        } else {
            $order = $this->getTableName() . '.' . $check[0];
        }
        return $order;
    }

    /**
     * Формирует часть sql запроса: select
     * @return string
     */
    public function selectToSql($cheat = ' '): string {
        if ($cheat != ' '){
            $select = 'SELECT ' . $cheat;

        } else {
            $select = 'SELECT ';
            $fields = [];
            foreach ($this->select as $alias => $field){
                if (is_string($alias)) {
                    $fields[] = $field . ' AS ' . $alias;
                } else {
                    $fields[] = $this->checkTable($field);
                }

            }

            $select .= implode(', ', $fields);
        }


        return $select;
    }

    /**
     * Формирует часть sql запроса: where
     * @return string|null
     */
    public function whereToSql(): string|null {
        if(!empty($this->filter)){
            $where = 'WHERE ';

            $filter = [];
            foreach ($this->filter as $filed => $value) {
                if (is_array($value)) {
                    foreach ($value as $valueItem) {
                        $filter[$filed][] = $filed . '=' . $valueItem;
                    }

                } else {
                    $filter[$filed] = $filed . '=' . $value . ' ';
                }
            }

            foreach ($filter as $field => $filterValue) {
                $filter[$field] = is_array($filterValue) ? '(' . implode(' OR ', $filterValue) . ')' : $filterValue;
            }

            $where .= implode(' AND ', $filter);

            return $where;
        }
        return null;
    }

    /**
     * Формирует часть sql запроса: sort
     * @return string
     */
    public function sortToSql(): string {
        $sort = 'ORDER BY ';
        $order = [];
        foreach ($this->sort as $filed => $value) {
            $order[] = $this->checkTable($filed) . ' ' . $value;
        }

        $sort .= implode(', ', $order);

        return $sort;
    }

    /**
     * Формирует часть sql запроса: limit
     * @return string|null
     */
    public function limitToSql(): string|null {
        if (isset($this->limit)) {
            return 'LIMIT ' . $this->limit;
        }

        return null;
    }

    /**
     * Формирует часть sql запроса: join
     * @return string|null
     */
    public function joinToSql(): string|null {
        if (!empty($this->join)) {
            $str = 'JOIN ';
            $join = [];

            foreach ($this->join as $alias => $value) {
                $str .= $value['model']::get()->getTableName() . ' ' . $alias . ' ON ';

                $arrRelationship = [];
                foreach ($value['relationship'] as $first => $second) {
                    $relationship = $this->getTableName() . '.' . $first . '=' . $alias . '.' . $second;
                    $arrRelationship[] = $relationship;
                }
                $relationship = implode(', ', $arrRelationship);

                $str .= $relationship;

                $join[] = $str;
            }

            return implode(' ', $join);
        }

        return null;
    }

    /**
     * Отправляет Select запрос в базу, возвращает массив строк
     * @return array
     */
    public function fetchAll(): array {
        $this->hasFetch = true;

        // Добавляем в запрос:
        // Что будет выбрано
        $select = $this->selectToSql();

        // Откуда выбрано
        $table = 'FROM ' . $this->tableName;

        $join = $this->joinToSql();
        $where = $this->whereToSql();
        $sort = $this->sortToSql();
        $limit = $this->limitToSql();

        if (isset($this->offset)) {
            $offset = 'OFFSET ' . $this->offset;
        }

        // Собираем запрос
        $query[] = $select;
        $query[] = $table;
        $query[] = $join ?? null;
        $query[] = $where ?? null;
        $query[] = $sort;
        $query[] = $limit ?? null;
        $query[] = $offset ?? null;

        $sql = implode(' ', $query);

        if ($this->limit && isset($this->offset)) {
            $this->fetchItemsCount();
        }
        //echo '<br>';

        //echo $sql;
        //echo '<br>';

        return static::$connection->query($sql);
    }

    public function sql($sql): array {
        //echo ($sql);

        return static::$connection->query($sql);
    }

    /**
     * Возвращает одну строку из таблицы
     * @return array
     */
    public function fetch(): array {
        $result = $this->fetchAll();

        return $result[0] ?? [];
    }

    /**
     * Получить, установить кол-во строк в таблице
     * @return void
     */
    public function fetchItemsCount(): void {
        $sqlCount = 'SELECT' . ' COUNT(*) FROM ' . $this->tableName;
        $count = static::$connection->query($sqlCount);

        $this->itemsCount = $count[0]['COUNT(*)'];
    }

    /**
     * Внести данные в таблицу
     * @param array $values
     * @return int
     */
    public function insert(array $values): int {
        $sql = 'INSERT ' . 'INTO ' . $this->tableName . ' (';

        $column = array_keys($values);
        $value = array_values($values);

        $column = implode(', ', $column);

        $value = implode(', ', $value);
        //echo ($value);
        //echo '<br>';
        $sql .= $column . ')';
        $sql .= ' VALUES (' . $value . ')';

        //echo $sql;

        //die();
        return static::$connection->exec($sql);
    }

    public function registration($mail, $pass): int {
        $sql = 'CALL registration(\'' . $mail . '\' ,\'' . $pass . '\')';
        return static::$connection->exec($sql);
    }

    /**
     * Обновить данные в таблице
     * @param array $values
     * @return int
     */
    public function update(array $values): int {
        $sql = 'UPDATE ' . $this->tableName . ' SET';

        $set = [];
        foreach ($values as $column => $newValue){
            $set[] = $column . '=' . $newValue . ' ';
        }
        $set = implode(', ', $set);

        $where = $this->whereToSql();

        // Собираем запрос
        $update[] = $sql;
        $update[] = $set;
        $update[] = $where ?? null;

        $sql = implode(' ', $update);

        echo $sql;

        return static::$connection->exec($sql);
    }

    /**
     * Пагинация
     * @param $page
     * @param $limit
     * @return $this
     */
    public function page($page, $limit): static {

        $offset = (int)($page) - 1;
        if ($offset < 0){
            $offset = 0;
        }
        $offset *= $limit;

        $this->limit($limit);
        $this->offset($offset);

        return $this;
    }

    /**
     * Получить информацию для пагинации
     * @return array
     * @throws Exception
     */
    public function getPageData(): array {
        if (!$this->hasFetch) {
            throw new \Exception('Need fetch');
        }

        return [
            'totalCount'  => $this->itemsCount,
            'currentPage' => ceil($this->offset/$this->limit) + 1,
            'pageSize'    => $this->limit,
            'pageCount'    => ceil($this->itemsCount / $this->limit)
        ];

    }
}