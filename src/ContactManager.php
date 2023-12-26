<?php

namespace src;

use http\Exception\RuntimeException;
use PDO;

//use to query the database, it's a repository
class ContactManager
{
    private DBConnect $dbConnect;

    public function __construct(DBConnect $dbConnect)
    {
        $this->dbConnect = $dbConnect;
    }
    //find all contact and return an array of contact
    public function findAll() : array
    {
        try {
            $pdo = $this->dbConnect->getPDO();
            $query = "SELECT * FROM contact";
            $statement = $pdo->prepare($query);
            $statement->execute();

            $contacts = [];
            while ($contactData = $statement->fetch(PDO::FETCH_ASSOC)) {
                $contact = new Contact(
                    $contactData['id'],
                    $contactData['name'],
                    $contactData['email'],
                    $contactData['phone_number']
                );
                $contacts[] = $contact;
            }

            return $contacts;
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur lors de la récupération des contacts : " . $e->getMessage());
        }
    }

    //return a single contact with his id
    public function findOne($id) : ?Contact
    {
        try {


        $pdo = $this->dbConnect->getPDO();
        $query = "SELECT * FROM contact WHERE id = $id";

        $statement = $pdo->query($query);
        $contactData = $statement->fetch(PDO::FETCH_ASSOC);

        if($contactData === false) {
            return null;
        }

        return new Contact(
            $contactData['id'],
            $contactData['name'],
            $contactData['email'],
            $contactData['phone_number']
        );
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur lors de la récupération des contacts : " . $e->getMessage());
        }
    }

    //create one contact and return his id
    public function createOne($contactData): int
    {
        try {
            $pdo = $this->dbConnect->getPDO();
            $query = "INSERT INTO contact (name, email, phone_number) VALUES (:name, :email, :phone)";

            $statement = $pdo->prepare($query);
            $statement->bindParam(':name', $contactData['name'], PDO::PARAM_STR);
            $statement->bindParam(':email', $contactData['email'], PDO::PARAM_STR);
            $statement->bindParam(':phone', $contactData['phone'], PDO::PARAM_STR);

            $statement->execute();

            return (int)$pdo->lastInsertId();


        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur lors de la création d'un contact : " . $e->getMessage());
        }
    }

    //delete one contact with his id
    public function deleteOne($id): void
    {
        try {
            $pdo = $this->dbConnect->getPDO();
            $query = "DELETE FROM contact WHERE id = :id";

            $statement = $pdo->prepare($query);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);

            $statement->execute();
        } catch (\PDOException $e) {
            throw new \RuntimeException("Erreur lors de la suppression d'un contact : " . $e->getMessage());
        }
    }
    //delete all contacts
    public function deleteAll(): void
    {
        try {
            $pdo = $this->dbConnect->getPDO();
            $query = "DELETE FROM contact";
            $statement = $pdo->prepare($query);
            $statement->execute();

        }catch (\PDOException $e) {
            throw new RuntimeException('Erreur lors de la purge de la DB' . $e->getMessage());
        }
    }
}