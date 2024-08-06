<?php

declare(strict_types=1);

namespace App\Tests\Support\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Entity\Team;
use App\Entity\User;
use App\Repository\TeamRepository;

class Factories extends \Codeception\Module
{
    public function _beforeSuite(array $settings = [])
    {
        $factory = $this->getModule('DataFactory');
        $em = $this->getModule('Doctrine')->_getEntityManager();

        /** @var TeamRepository $teamRepository */
        $teamRepository = $em->getRepository(Team::class);
        $teams = $teamRepository->findAllOrderedByName();

        $factory->_define(User::class, []);
    }
}
