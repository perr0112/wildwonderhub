<?php


namespace App\Tests\Controller\FAQController;

use App\Tests\Support\ControllerTester;
use App\Tests\Support\UsersSetup;
class IndexCest
{
    use UsersSetup;

    public function _before(ControllerTester $I): void
    {
        $this->createUsers();
    }
    public function TestIndexFaq(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);

        $I->amOnPage('/forum');
        $I->seeCurrentRouteIs('app_forum');
        $I->see('Forum du Zoo de la Palmyre');
    }

    public function TestLoginForUsers(ControllerTester $I): void
    {
        $I->amOnPage('/forum');
        $I->seeCurrentRouteIs('app_login');
        $I->see('Connectez-vous!');
    }

}
