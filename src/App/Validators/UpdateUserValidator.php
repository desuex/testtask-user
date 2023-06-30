<?php

namespace App\Validators;

class UpdateUserValidator extends UserValidator
{

    public function validate($input): array
    {
        $errors = [];

        // Validate name
        if (isset($input['name'])) {
            if (!$this->isNameValid($input['name'])) {
                $errors['name'][] = 'Invalid name.';
            }
            if ($this->isNameChanged($input) && !$this->isNameUnique($input['name'])) {
                $errors['name'][] = 'This name already exists.';
            }
            if (!$this->isNameAllowed($input['name'])) {
                $errors['name'][] = 'This name is not allowed.';
            }
        }

        // Validate email
        if (isset($input['email'])) {
            if (!$this->isEmailValid($input['email'])) {
                $errors['email'][] = 'Invalid email.';
            }
            if ($this->isEmailChanged($input) && !$this->isEmailUnique($input['email'])) {
                $errors['email'][] = 'This email already exists.';
            }
            if (!$this->isEmailAllowed($input['email'])) {
                $errors['email'][] = 'This email is not allowed.';
            }
        }

        // Validate deleted
        if (isset($input['deleted']) && !$this->isDeletedValid($input['deleted'], $this->userService->getCurrentUser()->getCreated() ?? null)) {
            $errors[] = 'Invalid deleted date.';
        }

        return $errors;
    }

    private function isNameChanged($input): bool
    {
        $currentUser = $this->userService->getCurrentUser(); // Replace with your logic to get the current user
        return isset($input['name']) && $input['name'] !== $currentUser->getName();
    }

    private function isEmailChanged($input): bool
    {
        $currentUser = $this->userService->getCurrentUser(); // Replace with your logic to get the current user
        return isset($input['email']) && $input['email'] !== $currentUser->getEmail();
    }

}