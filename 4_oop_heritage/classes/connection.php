<?php
/**
 * Encapsulates a connection to the database 
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 September 2019
 */
require_once '../runtime/info.php';

class DB 
{
    protected object $conn;

    /**
     * Opens a connection to the database
     */
    public function __construct() 
    {
        $dsn = 'mysql:host=' . Info::HOST . ';dbname=' . Info::DB_NAME . ';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->conn = @new PDO($dsn, Info::USER, Info::PASSWORD, $options); 
        } catch (\PDOException $e) {
            die('Connection unsuccessful: ' . $e);
            exit();
        }
    }

    /**
     * Closes the database connection
     */
    public function __destruct() 
    {
        unset($this->conn);
    }
}