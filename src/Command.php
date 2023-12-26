<?php

namespace src;

class Command
{


    private ContactManager $contactManager;

    public function __construct(DBConnect $database)
    {
    //instantiate contactManager
        $this->contactManager = new ContactManager($database);
    }

    public function execute(string $line): void
    {
        //switch case for every program's launch, allow to select method
        if ($line === 'exit') {
            echo "Fermeture du programme.\n";
            exit;
        }
        //use regex to detect the syntax 'detail --2'
        if (preg_match('/^detail\s+--(\d+)$/', $line, $matches)) {
            $id = $matches[1];
            $this->displayOneById($id);
        } else {
            switch ($line) {
                case 'list':
                    $this->displayAll();
                    break;
                case 'detail':
                    $this->displayOneByIdInteractive();
                    break;
                case 'create':
                    $this->createContact();
                    break;
                case 'delete':
                    $this->displayOneToDelete();
                    break;
                case 'purge':
                    $this->purgeDatabase();
                    break;
                case 'help':
                    $this->helperCommand();
                    break;
                default:
                    echo "Commande non reconnue\n";
            }
        }
    }
    //allow to seatch contact with his id
    private function displayOneByIdInteractive(): void
    {
        $id = (int)readline("Entrez l'id de l'utilisateur recherché : ");
        $this->displayOneById($id);
    }
    // callback one displayOneByIdInteractive or if shortcut of 'detail -- 5'
    private function displayOneById(int $id): void
    {
        $contact = $this->contactManager->findOne($id);
        if ($contact) {
            echo $contact->toString();
        } else {
            echo "Utilisateur non trouvé avec l'ID : $id\n";
        }
    }

    //display all DB's entries with Contact class method
    private function displayAll(): void
    {
        $contacts = $this->contactManager->findAll();

        foreach ($contacts as $contact) {
            /** @var Contact $contact */
            echo $contact->toString();
        }
    }

    //Allow to create Contact
    private function createContact() : void
    {
        $contactData = [];

        $name = readline("Entrez le nom du contact : ");
        //every element is validate with regex in ValidationContact classe
        if(!ValidationContact::validateName($name)) {
            echo "Le nom n'est pas valide, il doit faire entre 3 et 15 caracteres et ne doit pas comporter de chiffres. \n";
            return;
        }
        $contactData['name'] = $name;

        $phoneNumber = readline("Entrez le téléphone du contact : ");
        if (!ValidationContact::validatePhoneNumber($phoneNumber)) {
            echo "Le numéro de téléphone n'est pas valide. Veuillez entrer un numéro composé uniquement de chiffres.\n";
            return;
        }
        $contactData['phone'] = $phoneNumber;

        $email = readline('Entrez le mail du contact : ');
        if (!ValidationContact::validateEmail($email)) {
            echo "L'adresse email n'est pas valide. Veuillez entrer une adresse email valide.\n";
            return;
        }
        $contactData['email'] = $email;

        //if fields are corrects, contact is created and displayed
        $contactId = $this->contactManager->createOne($contactData);
        echo "contact créé : ";
        $this->displayOneById($contactId);

    }

    //interactif command allow to choose contact id and delete off
    private function displayOneToDelete(): void
    {
        $id = (int)readline("Entrez l'id de l'utilisateur à supprimer : ");
        $this->deleteOneById($id);
    }

    //Call deleteOne method on an existing contact
    private function deleteOneById(int $id): void
    {
        $contact = $this->contactManager->findOne($id);

        if ($contact) {
            $this->contactManager->deleteOne($id);
            echo "Le contact a été supprimé \n";
        } else {
            echo "Le contact n'existe pas";
        }
    }
    //delete all the contact on the DB
    private function purgeDatabase() :void
    {
        $purge = strtolower(readline("Voulez vous purger la database ? [Y/N] "));

        if($purge === 'y') {
            $this->contactManager->deleteAll();
            echo 'db purgée' . PHP_EOL;
        } else if($purge === 'n') {
            echo 'commande abordée' . PHP_EOL;
        } else {
            echo 'commande non reconnue'. PHP_EOL;
        }
    }
    //display all the commands on the CLI
    private  function helperCommand() : void
    {
        $helpText = "Voici la liste des différentes commandes à votre disposition :\n";
        $helpText .= "  - list : Afficher tous les contacts\n";
        $helpText .= "  - detail : Afficher les détails d'un contact\n";
        $helpText .= "  - create : Créer un nouveau contact\n";
        $helpText .= "  - delete : Supprimer un contact\n";
        $helpText .= "  - purge : Purger la base de données (irréversible)\n";
        $helpText .= "  - help : Afficher cette liste d'aides\n";
        $helpText .= "  - exit : Quitter le programme en cours d'execution\n";

        echo $helpText;
    }
}