<?php


namespace App\Tests\Controller\Profile;

use App\Entity\User;
use App\Tests\Support\ControllerTester;
use App\Tests\Support\UsersSetup;

class CrudCest
{
    use UsersSetup;
    public function _before(ControllerTester $I): void
    {
        $this->createUsers();
    }

    public function TestEditMainInformationsOfUser(ControllerTester $I): void
    {
        // connecté en tant que userBasic
        /* 'firstname' => 'Clément',
            'lastname' => 'Perrot',
            'email' => $uniqueEmailBasic,
            'password' => 'test',
            'roles' => ['ROLE_USER'], */

        $I->amLoggedInAs($this->userBasic);
        $I->amOnPage('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentRouteIs('app_profile');

        $I->fillField('profile[firstName]', 'Clémentine');
        $I->fillField('profile[pc]', '75000');
        $I->fillField('profile[city]', 'Paris');
        $I->fillField('profile[phone]', '0123456789');
        $I->click('Modifier mes informations');

        $I->see('Votre compte a bien été modifié!');

        // vérifions que les données ont bien été modifiées
        $I->seeInRepository(User::class, [
            'id' => $this->userBasic->getId(),
            'firstName' => 'Clémentine',
            'pc' => '75000',
            'city' => 'Paris',
            'phone' => '0123456789',
        ]);
    }


    public function TestErrorNewPasswordOfUser(ControllerTester $I): void
    {
        // connecté en tant que userBasic
        /* 'firstname' => 'Clément',
            'lastname' => 'Perrot',
            'email' => $uniqueEmailBasic,
            'password' => 'test',
            'roles' => ['ROLE_USER'], */

        $I->amLoggedInAs($this->userBasic);
        $I->amOnPage('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentRouteIs('app_profile');

        $I->fillField('profile_password[currentPassword]', 'test');
        $I->fillField('profile_password[newPassword][first]', 'test2');
        $I->fillField('profile_password[newPassword][second]', 'test3');
        $I->click('Modifier mon mot de passe');

        $I->see('Vous avez saisi deux mots de passe différents...');
    }

    public function TestEditPasswordOfUser(ControllerTester $I): void
    {
        // connecté en tant que userBasic
        /* 'firstname' => 'Clément',
            'lastname' => 'Perrot',
            'email' => $uniqueEmailBasic,
            'password' => 'test',
            'roles' => ['ROLE_USER'], */

        $I->amLoggedInAs($this->userBasic);
        $I->amOnPage('/profile');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentRouteIs('app_profile');

        $I->fillField('profile_password[currentPassword]', 'test');
        $I->fillField('profile_password[newPassword][first]', 'test2');
        $I->fillField('profile_password[newPassword][second]', 'test2');
        $I->click('Modifier mon mot de passe');

        $I->see('Le mot de passe a bien été modifié!');

        // logout
        $I->click('Se déconnecter');
        $I->seeCurrentRouteIs('app_presentation');

        // login
        $I->amOnPage('/login');
        $I->seeResponseCodeIs(200);
        $I->seeCurrentRouteIs('app_login');
        $I->fillField('email', $this->userBasic->getEmail());
        $I->fillField('password', 'test2');
        $I->click('Me connecter');
        $I->amOnPage('/profile');
        $I->seeResponseCodeIs(200);
        $I->see('Bonjour, ' . $this->userBasic->toString());
    }


}
