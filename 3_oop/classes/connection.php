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

    /**
     * Opens a connection to the database
     * 
     * @return a connection object
     */
    public function connect(): object 
    {
        $dsn = 'mysql:host=' . Info::HOST . ';dbname=' . Info::DB_NAME . ';charset=utf8';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $conn = @new PDO($dsn, Info::USER, Info::PASSWORD, $options); 
        } catch (\PDOException $e) {
            die('Connection unsuccessful: ' . $e);
            exit();
        }
        
        return($conn);   
    }

    /**
     * Closes a connection to the database
     * 
     * @param the connection object to disconnect
     */
    public function disconnect(object $conn): void 
    {
        $conn = null;
    }
}