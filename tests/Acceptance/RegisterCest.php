<?php


namespace App\Tests\Acceptance;

use App\Entity\User;
use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Tests\Support\AcceptanceTester;

class RegisterCest
{
    private TeamRepository $teamRepository;

    public function _before(AcceptanceTester $I)
    {
        $this->teamRepository = $I->grabService(TeamRepository::class);
    }

    // tests
    public function homePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Soirée communautaire : le jeu');
        $I->seeInSource('/images/forumphp-2024.png');
    }

    public function registerUser(AcceptanceTester $I)
    {
        $I->have(User::class, ['username' => 'C0001', 'team' => $this->teamRepository->find(1)]);

        $I->amOnPage('/');
        $I->canSeeCurrentUrlEquals('/register');
        $I->fillField('registration[code]', 'C0001');
        $I->click('registration[save]');

        $I->see('Confirmation');
        $I->see('Vous souhaitez vous inscrire ou vous reconnecter avec le code C0001.');

        $I->click('Oui c\'est bien mon badge !');

        $I->see('Bienvenue C0001 !');
        $I->fillField('name[name]', 'SuPHPhero');
        $I->click('Enregistrer et continuer');

        $I->see('Merci');
        $I->see('Vous êtes SuPHPhero (C0001).');

        $I->amOnPage('/register');
        $I->canSeeCurrentUrlEquals('/');
        $I->see('Vous êtes SuPHPhero (C0001).');
    }
}
