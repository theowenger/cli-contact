<?php

//get all files we need
use src\Command;
require_once __DIR__ . '/vendor/autoload.php';
require_once 'src/DBConnect.php';
require_once 'src/ContactManager.php';
require_once 'src/Contact.php';

//launch DBConnect for catch the connexion
$database = new src\DBConnect();

//while user don't exit, the command reroll
while (true) {
    $line = readline("Entrez votre commande : ");
    echo "Vous avez saisi : $line\n";
    $response = new Command($database);
    $response->execute($line);

}