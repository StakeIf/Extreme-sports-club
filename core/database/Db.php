<?php
session_start();

function flash(?string $message = null, string $type = 'danger')
{
    if ($message) {
        $_SESSION['flash'] = $message;
        $_SESSION['type'] = $type;
    } else {
        if (!empty($_SESSION['flash'])) { ?>
            <div class="alert alert-<?=$_SESSION['type']?> mb-3">
                <?=$_SESSION['flash']?>
            </div>
        <?php }
        unset($_SESSION['flash']);
        unset($_SESSION['type']);
    }
}

function check_auth(): bool
{
    return !!($_SESSION['user_id'] ?? false);
}

class Db {

    protected static $_instance;

    /**
     * @var PDO $pdo
     */
    protected PDO $pdo;

    protected function __construct() {
        $filename = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'access-db.php';

        if (file_exists($filename)) {
            /**
             * @var array $access
             */
            $access = require_once $filename;
        } else {
            header("Location: /error/db/");
            die();
        }

        $dsn = "pgsql:host=$access[host];port=$access[port];dbname=$access[db];";
        $this->pdo = new PDO($dsn, $access['user'], $access['pass']);
    }

    public static function getInstance(): static {
        if (self::$_instance === null) {
            self::$_instance = new static;
        }

        return self::$_instance;
    }

    /**
     * @param string $query
     * @return array
     */
    public function query(string $query): array {
        /**
         * @var PDOStatement $stmt
         */
        $stmt = $this->pdo->query($query);

        $data = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }

        return $data;
    }

    public function exec(string $sql): int {

        return $this->pdo->exec($sql);
    }
}
