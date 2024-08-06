<?php

declare(strict_types=1);

namespace App\Tests\Support\Step\Acceptance;

use App\Tests\Support\AcceptanceTester;

class AcceptanceUser extends AcceptanceTester
{
    public function login(string $usercode)
    {
        $I = $this;

        $I->amOnPage('/');
        $I->fillField('registration[code]', $usercode);
        $I->click('registration[save]');
        $I->click('Oui c\'est bien mon badge !');
        $I->see('Vous êtes ' . $usercode . '.');
    }

}
