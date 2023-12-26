<?php

namespace src;

class ValidationContact
{
    //for each method, we compare input with regex to know if the field's correct
    public static function validateName(string $name): bool
    {
        return preg_match('/^[a-zA-Z]{3,15}$/', $name) === 1;
    }

    public static function validatePhoneNumber($phoneNumber): bool
    {
        return preg_match('/^[0-9]{8,12}$/', $phoneNumber) === 1;
    }
    public static function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}