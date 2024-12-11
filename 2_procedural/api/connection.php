<?php
/**
 * Connection to the database 
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0 September 2019
 */

/**
 * Opens a connection to the database
 * 
 * @return a connection object
 */
function connect(): object {
    $host = 'localhost';
    $dbName = 'movies';
    $user = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $conn = @new PDO($dsn, $user, $password, $options); 
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
function disconnect(object $conn): void {
    $conn = null;
}