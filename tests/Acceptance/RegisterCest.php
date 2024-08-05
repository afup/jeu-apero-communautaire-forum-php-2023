<?php


namespace App\Tests\Acceptance;

use App\Tests\Support\AcceptanceTester;

class RegisterCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function homePageWorks(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->see('Soirée communautaire : le jeu');
        $I->seeInSource('/images/forumphp-2024.png');
    }
}
