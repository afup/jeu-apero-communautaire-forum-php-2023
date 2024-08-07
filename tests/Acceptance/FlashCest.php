<?php


namespace App\Tests\Acceptance;

use App\Entity\User;
use App\Repository\TeamRepository;
use App\Tests\Support\AcceptanceTester;
use App\Tests\Support\Step\Acceptance\AcceptanceUser;

class FlashCest
{
    private TeamRepository $teamRepository;

    public function _before(AcceptanceUser $I)
    {
        $this->teamRepository = $I->grabService(TeamRepository::class);
        $I->have(User::class, ['username' => 'C0001', 'team' => $this->teamRepository->find(1)]);
    }

    // tests
    public function flashRegisteredUserFromSameTeam(AcceptanceUser $I)
    {
        $I->have(User::class, ['username' => 'C0002', 'team' => $this->teamRepository->find(1), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');

        $I->canSee('Flasher un QR Code');
        $I->canSee('Entrer un code manuellement');

        $I->click('Entrer un code manuellement');
        $I->fillField('flash[code]', 'C0002');
        $I->click('Valider');

        $I->see('Vous avez flashé C0002');
        $I->see('BRAVO !');
    }

    public function flashRegisteredUserFromOtherTeam(AcceptanceUser $I)
    {
        $I->have(User::class, ['username' => 'C0004', 'team' => $this->teamRepository->find(2), 'registeredAt' => new \DateTimeImmutable()]);

        $I->login('C0001');

        $I->amOnPage('/flash/C0004');

        $I->see('Vous avez flashé C0004');
        $I->see('Et non...');
    }

    public function flashUnregisteredUser(AcceptanceUser $I)
    {
        $I->have(User::class, ['username' => 'C0003', 'team' => $this->teamRepository->find(1)]);

        $I->login('C0001');

        $I->click('Entrer un code manuellement');
        $I->fillField('flash[code]', 'C0003');
        $I->click('Valider');

        $I->see('Vous avez flashé C0003');
        $I->see('Ce·tte SuPHPerhero ne participe pas encore au jeu !');
    }
}
