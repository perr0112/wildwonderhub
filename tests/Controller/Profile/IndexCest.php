<?php


namespace App\Tests\Controller\Profile;

use App\Tests\Support\ControllerTester;
use App\Tests\Support\UsersSetup;

class IndexCest
{
    use UsersSetup;
    public function _before(ControllerTester $I): void
    {
        $this->createUsers();
    }

    public function TestIndexProfile(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentRouteIs('app_profile');
        $I->see('Profil de ' . $this->userBasic->toString());
        $I->see('Bonjour, ' . $this->userBasic->toString());
    }

}
