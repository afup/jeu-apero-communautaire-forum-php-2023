<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\User;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class UserCodeGenerator
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function generate(int $number): void
    {
        for ($i = 0; $i < $number; $i++) {
            $code = $this->randomString(4) . $this->randomNumber(3);
            $user = (new User())->setUsername($code);

            try {
                $this->entityManager->persist($user);
                $this->entityManager->flush();
            } catch (UniqueConstraintViolationException) {
            }
        }
    }

    private function randomString(int $size): string
    {
        return $this->random($size, 'abcdefghijklmnopqrstuvwxyz');
    }

    private function randomNumber(int $size): string
    {
        return $this->random($size, '0123456789');
    }

    private function random(int $size, string $characters): string
    {
        $randomString = '';

        for ($i = 0; $i < $size; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}