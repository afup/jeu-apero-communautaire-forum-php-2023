<?php

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CodeExistsValidator extends ConstraintValidator
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\CodeExists $constraint */

        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->userRepository->findOneBy(['username' => $value]);

        if (!$user instanceof User) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
