<?php


namespace App\Tests\Controller\Home;

use App\Tests\Support\ControllerTester;
use App\Tests\Support\UsersSetup;

class IndexCest
{
    use UsersSetup;

    public function _before(ControllerTester $I): void
    {
        $this->createUsers();
    }
    public function TestIndex(ControllerTester $I): void
    {
        $I->amOnPage('/');
        $I->seeCurrentRouteIs('app_presentation');
        $I->seeResponseCodeIs(200);
        $I->seeElement('header');
        $I->seeElement('.homepage');
        $I->seeElement('footer');
    }

    public function TestIndexForAuthenticatedAdmins(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userAdmin);
        $I->amOnPage('/presentation');
        $I->seeCurrentRouteIs('app_presentation');
        $I->see('Bienvenue sur le site du Zoo de la Palmyre');
        $identityUser = 'Bonjour, ' . $this->userAdmin->getFirstName() . ' ' . $this->userAdmin->getLastName();
        $I->see($identityUser);
        $I->see('Accès administratif');
        $I->click('Accès administratif');
        $I->seeCurrentRouteIs('admin');
    }

    public function TestIndexForAuthenticatedUsers(ControllerTester $I): void
    {
        $I->amLoggedInAs($this->userBasic);
        $I->amOnPage('/presentation');
        $I->seeCurrentRouteIs('app_presentation');
        $I->dontSee('Accès administratif');
        $I->amOnPage('/admin');
        $I->seeResponseCodeIs(403);
    }

}
