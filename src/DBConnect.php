<?php

namespace src;

use Dotenv\Dotenv;
use PDO;
use PDOException;

//This class get the .env data and use this to create a connexion with DB
class DBConnect
{
    private string $host;
    private string $dbname;
    private string $dbuser;
    private string $password;

    //every instance created, construct insert .env data into the variable
    public function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        $this->host = $_ENV['HOST'];
        $this->dbname = $_ENV['DBNAME'];
        $this->dbuser = $_ENV['DBUSER'];
        $this->password = $_ENV['PASSWORD'];
    }

    //getPDO try to acces to the DB
    function getPDO() : PDO
{
    try {
        $pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->dbuser, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}
}