<?php

namespace App\Validators;

use App\Services\UserService;
use DateTime;

class CreateUserValidator extends BaseValidator
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function validate($input): array
    {
        $errors = [];

        // Validate name
        if (!$this->isValidName($input['name'])) {
            $errors[] = 'Invalid name.';
        }

        // Validate email
        if (!$this->isValidEmail($input['email'])) {
            $errors[] = 'Invalid email.';
        }

        // Validate deleted
        if (!$this->isValidDeleted($input['deleted'], $input['created'])) {
            $errors[] = 'Invalid deleted date.';
        }

        return $errors;
    }

    private function isValidName($name): bool
    {
        // Check if the name matches the required pattern
        if (!preg_match('/^[a-z0-9]{8,}$/i', $name)) {
            return false;
        }

        // Check if the name contains any forbidden words
        $forbiddenWords = ['admin', 'root', 'support'];
        foreach ($forbiddenWords as $word) {
            if (stripos($name, $word) !== false) {
                return false;
            }
        }

        // Check if the name is unique in the database
        if ($this->userService->isNameExists($name)) {
            return false;
        }

        return true;
    }

    private function isValidEmail($email): bool
    {
        // Check if the email has a valid format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        // Check if the email domain is in the list of unreliable domains
        $unreliableDomains = ['mail.ru', 'bk.ru', 'list.ru'];
        $domain = explode('@', $email)[1];
        if (in_array($domain, $unreliableDomains)) {
            return false;
        }

        // Check if the email is unique in the database
        if ($this->userService->isEmailExists($email)) {
            return false;
        }

        return true;
    }

    private function isValidDeleted($deleted, $created): bool
    {
        if (is_null($deleted)) {
            return true;
        }
        // Check if the deleted date is not less than the created date
        try {
            $createdDateTime = new DateTime($created);
            $deletedDateTime = new DateTime($deleted);

            if ($deletedDateTime < $createdDateTime) {
                return false;
            }
        } catch (\Exception $exception){
            // If invalid datetime is used
            return false;
        }


        return true;
    }
}