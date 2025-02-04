<?php

class Connection
{
    //Koneksi ke database
    public static function connect()
    {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'notes';

        $connection = null;

        try {
            $connection = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Error: " . $e->getMessage();
        }

        return $connection;
    }
}

?>