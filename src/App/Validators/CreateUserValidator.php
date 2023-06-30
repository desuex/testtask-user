<?php

namespace App\Validators;

use App\Services\UserService;
use DateTime;
use Exception;

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

    /**
     * @param $name
     * @return bool
     */
    private function isNameAllowed($name): bool
    {
        // Check if the name contains any forbidden words
        $forbiddenWords = ['admin', 'root', 'support'];
        foreach ($forbiddenWords as $word) {
            if (stripos($name, $word) !== false) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param $name
     * @return bool
     */
    private function isNameValid($name): bool
    {
        // Check if the name matches the required pattern
        return (bool)preg_match('/^[a-z0-9]{8,}$/i', $name);
    }

    /**
     * @param $name
     * @return bool
     */
    private function isNameUnique($name): bool
    {
        // Check if the name is unique in the database
        return !$this->userService->isNameExists($name);
    }

    /**
     * @param $email
     * @return bool
     */
    private function isEmailAllowed($email): bool
    {
        // Check if the email domain is in the list of unreliable domains
        $unreliableDomains = ['mail.ru', 'bk.ru', 'list.ru'];
        $domain = explode('@', $email)[1];
        if (in_array($domain, $unreliableDomains)) {
            return false;
        }
        return true;
    }

    /**
     * @param $email
     * @return bool
     */
    private function isEmailValid($email): bool
    {
        // Check if the email has a valid format
        return (bool)filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param $email
     * @return bool
     */
    private function isEmailUnique($email): bool
    {
        // Check if the email is unique in the database
        return !$this->userService->isEmailExists($email);
    }


    /**
     * @param $deleted
     * @param $created
     * @return bool
     */
    private function isDeletedValid($deleted, $created): bool
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
        } catch (Exception $exception) {
            // If invalid datetime is used
            return false;
        }
        return true;
    }
}