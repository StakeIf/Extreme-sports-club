<?php
require_once($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'core' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'QueryDb.php');

class User extends QueryDb {

    protected ?int $itemsCount = null;

    /**
     * @return static
     */
    public static function get(): static {
        return new static('users');
    }
}