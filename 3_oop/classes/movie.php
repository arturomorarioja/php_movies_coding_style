<?php
/**
 * Movie class
 * 
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 January/September 2019
 * @version 1.0.1 February 2023. Refactoring. Code updated to PHP 8.2
 */
require_once 'connection.php';

class Movie 
{
    /**
     * Retrieves movie information
     * 
     * @return  all movie fields (ID, movie name) ordered by movie name, 
     *          or false if the database connection was unsuccessful
     */
    public function list(): array|false
    {
        $db = new DB;
        if (!$conn = $db->connect()) {
            return false;
        }
        
        $sql = 'SELECT nMovieID, cName FROM movies ORDER BY cName';
        
        $results = [];
        $stmt = $conn->query($sql);
        while($row = $stmt->fetch()) {
            $results[] = [$row['nMovieID'], $row['cName']];
        }

        $stmt = null;
        $db->disconnect($conn);
        
        return($results);
    }

    /**
     * Retrieves the movies whose name matches a certain text
     * 
     * @param   text upon which to execute the search
     * @return  matching movie fields (ID, movie name) ordered by movie name,
     *          or false if the database connection was unsuccessful
     */
    public function search(string $searchText): array|false 
    {
        $db = new DB;
        if (!$conn = $db->connect()) {
            return false;
        }
        
        $sql =<<<SQL
            SELECT nMovieID, cName 
            FROM movies
            WHERE cName LIKE ?
            ORDER BY cName
        SQL;
        
        $results = [];
        $stmt = $conn->prepare($sql);
        $stmt->execute(["%$searchText%"]);
        while($row = $stmt->fetch()) {
            $results[] = [$row['nMovieID'], $row['cName']];
        }

        $stmt = null;
        $db->disconnect($conn);
        
        return($results);
    }

    /**
     * Inserts a new movie
     * 
     * @param   name of the new movie
     * @return  true if the insertion was correct, 
     *          or false if the database connection was unsuccessful or there was an error
     */
    public function add(string $movieName): bool 
    {
        $db = new DB;
        if (!$conn = $db->connect()) {
            return false;
        }

        $sql =<<<SQL
            INSERT INTO movies (cName)
                VALUES (?)
        SQL;

        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$movieName]);

        $stmt = null;                
        $db->disconnect($conn);
        
        return ($ok);
    }

    /**
     * Updates the name of a movie
     * 
     * @param   id of the movie to update
     * @param   new name of the movie
     * @return  true if the update was correct,
     *          or false if the database connection was unsuccessful or there was an error
     */
    public function update(int $movieID, string $movieName): bool 
    {
        $db = new DB;
        if (!$conn = $db->connect()) {
            return false;
        }

        $sql =<<<SQL
            UPDATE movies
            SET cName = ?
            WHERE nMovieID = ?
        SQL;

        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$movieName, $movieID]);  

        $stmt = null;                
        $db->disconnect($conn);
        
        return ($ok);
    }

    /**
     * Deletes a movie
     * 
     * @param   id of the movie to delete
     * @return  true if the deletion was correct, false if there was an error
     *          or false if the database connection was unsuccessful or there was an error
     */
    public function delete(int $movieID): bool 
    {
        $db = new DB;
        if (!$conn = $db->connect()) {
            return false;
        }

        $sql =<<<SQL
            DELETE FROM movies
            WHERE nMovieID = ?
        SQL;

        $stmt = $conn->prepare($sql);
        $ok = $stmt->execute([$movieID]);

        $stmt = null;                
        $db->disconnect($conn);
        
        return ($ok);
    }
}