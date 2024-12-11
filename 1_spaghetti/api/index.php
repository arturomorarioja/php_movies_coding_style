<?php
/**
 * Main backend API
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 January 2019
 * @version 1.0.1 February 2023. 
 *      Refactoring to turn a beautiful object-oriented example 
 *      into a paradigm of spaghetti code for demonstration purposes
 */

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
    echo json_encode(false);
    exit();
}

switch($_POST['action']) {
    case 'load':        
        $sql = 'SELECT nMovieID, cName FROM movies ORDER BY cName';
        
        $results = [];
        $stmt = $conn->query($sql);
        while($row = $stmt->fetch()) {
            $results[] = [$row['nMovieID'], $row['cName']];
        }
        echo json_encode($results);
        break;
    case 'search':
        $sql =<<<SQL
            SELECT nMovieID, cName 
            FROM movies
            WHERE cName LIKE ?
            ORDER BY cName
        SQL;
        
        $results = [];
        $stmt = $conn->prepare($sql);
        $stmt->execute(["%{$_POST['movie_search_text']}%"]);
        while($row = $stmt->fetch()) {
            $results[] = [$row['nMovieID'], $row['cName']];
        }

        echo json_encode($results);
        break;
    case 'add':
        $sql =<<<SQL
            INSERT INTO movies (cName)
                VALUES (?)
        SQL;
        $stmt = $conn->prepare($sql);
        echo json_encode($ok = $stmt->execute([$_POST['movie_name']]));
        break;
    case 'modify':
        $sql =<<<SQL
            UPDATE movies
            SET cName = ?
            WHERE nMovieID = ?
        SQL;
        $stmt = $conn->prepare($sql);
        echo json_encode($ok = $stmt->execute([$_POST['movie_name'], $_POST['movie_id']]));
        break;
    case 'delete':
        $sql =<<<SQL
            DELETE FROM movies
            WHERE nMovieID = ?
        SQL;
        $stmt = $conn->prepare($sql);
        echo json_encode($ok = $stmt->execute([$_POST['movie_id']]));
        break;
}
if (isset($stmt)) {
    $stmt = null;
}
$conn = null;