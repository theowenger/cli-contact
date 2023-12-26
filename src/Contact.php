<?php

namespace src;

class Contact
{
    private int $id;
    private string $name;
    private string $email;
    private string $phoneNumber;

    //instantiate with the constructor the contact
    public function __construct(int $id, string $name, string $email, string $phoneNumber)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->phoneNumber = $phoneNumber;
    }

    //all differents getters
    public function getId() :int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    //all differents setters
    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $phoneNumber
     */
    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    //method used to display the contact with the CLI
    public function toString() :string
    {
        return sprintf(
            "ID: %5d Name: %-20s Email: %-30s Phone: %-15s\n",
            $this->getId(),
            $this->getName(),
            $this->getEmail(),
            $this->getPhoneNumber()
        );
    }
}