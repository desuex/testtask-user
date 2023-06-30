<?php

namespace App\Validators;

class CreateUserValidator extends UserValidator
{

    public function validate($input): array
    {
        $errors = [];

        // Validate name
        if (!$this->isNameValid($input['name'])) {
            $errors['name'][] = 'Invalid name.';
        }
        if (!$this->isNameUnique($input['name'])) {
            $errors['name'][] = 'This name already exists.';
        }
        if (!$this->isNameAllowed($input['name'])) {
            $errors['name'][] = 'This name is not allowed.';
        }


        // Validate name
        if (!$this->isEmailValid($input['email'])) {
            $errors['email'][] = 'Invalid email.';
        }
        if (!$this->isEmailUnique($input['email'])) {
            $errors['email'][] = 'This email already exists.';
        }
        if (!$this->isEmailAllowed($input['email'])) {
            $errors['email'][] = 'This email is not allowed.';
        }

        // Validate deleted
        if (!$this->isDeletedValid($input['deleted'] ?? null, $input['created'] ?? null)) {
            $errors[] = 'Invalid deleted date.';
        }
        return $errors;
    }
}