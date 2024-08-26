<?php

declare(strict_types=1);

namespace App\Tests\Acceptance;

use App\Entity\Flash;
use App\Entity\FlashType;
use App\Entity\User;
use App\Repository\TeamRepository;
use App\Tests\Support\Step\Acceptance\AcceptanceUser;

class GoldenFlashCest
{
    private TeamRepository $teamRepository;

    public function _before(AcceptanceUser $I): void
    {
        $this->teamRepository = $I->grabService(TeamRepository::class);

        $I->have(User::class, [
            'username' => 'C0001',
            'team' => $this->teamRepository->find(1),
            'goldenUsername' => 'C0002',
            'nullUsername' => 'C0003',
        ]);
    }

    public function flashGoldenTicketInMyTeam(AcceptanceUser $I): void
    {
        $I->have(User::class, ['username' => 'C0002', 'team' => $this->teamRepository->find(1), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');
        $I->amOnPage('/flash/C0002');

        $I->see('Vous avez flashé C0002');
        $I->see('TICKET D\'OR !');

        $I->seeInRepository(Flash::class, [
            'flasher' => ['username' => 'C0001'],
            'flashed' => ['username' => 'C0002'],
            'isSuccess' => true,
            'score' => 100,
            'type' => FlashType::GOLDEN_TICKET,
        ]);

        $I->amOnPage('/');
        $I->see('Votre score personnel : 100 points');
    }

    public function flashGoldenTicketInAnotherTeam(AcceptanceUser $I): void
    {
        $I->have(User::class, ['username' => 'C0002', 'team' => $this->teamRepository->find(2), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');
        $I->amOnPage('/flash/C0002');

        $I->see('Vous avez flashé C0002');
        $I->see('TICKET D\'OR !');

        $I->seeInRepository(Flash::class, [
            'flasher' => ['username' => 'C0001'],
            'flashed' => ['username' => 'C0002'],
            'isSuccess' => false,
            'score' => 100,
            'type' => FlashType::GOLDEN_TICKET,
        ]);

        $I->amOnPage('/');
        $I->see('Votre score personnel : 100 points');
    }

    public function flashNullTicketInMyTeam(AcceptanceUser $I): void
    {
        $I->have(User::class, ['username' => 'C0003', 'team' => $this->teamRepository->find(1), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');
        $I->amOnPage('/flash/C0003');

        $I->see('Vous avez flashé C0003');
        $I->see('COEUR BRISÉ...');

        $I->seeInRepository(Flash::class, [
            'flasher' => ['username' => 'C0001'],
            'flashed' => ['username' => 'C0003'],
            'isSuccess' => true,
            'score' => -200,
            'type' => FlashType::NULL_TICKET,
        ]);

        $I->amOnPage('/');
        $I->see('Votre score personnel : -200 points');
    }

    public function flashNullTicketInAnotherTeam(AcceptanceUser $I): void
    {
        $I->have(User::class, ['username' => 'C0003', 'team' => $this->teamRepository->find(2), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');
        $I->amOnPage('/flash/C0003');

        $I->see('Vous avez flashé C0003');
        $I->see('COEUR BRISÉ...');

        $I->seeInRepository(Flash::class, [
            'flasher' => ['username' => 'C0001'],
            'flashed' => ['username' => 'C0003'],
            'isSuccess' => false,
            'score' => -200,
            'type' => FlashType::NULL_TICKET,
        ]);

        $I->amOnPage('/');
        $I->see('Votre score personnel : -200 points');
    }
}