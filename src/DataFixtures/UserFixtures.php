<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        UserFactory::CreateOne([
            'firstname' => 'Logan',
            'lastname' => 'Jacotin',
            'email' => 'root@example.com',
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ]);
        UserFactory::CreateOne([
            'firstname' => 'Romain',
            'lastname' => 'Leroy',
            'email' => 'user@example.com',
            'password' => 'test',
            'roles' => ['ROLE_USER'],
        ]);
        UserFactory::CreateOne([
            'firstname' => 'Clément',
            'lastname' => 'Perrot',
            'email' => 'employe@example.com',
            'password' => 'test',
            'roles' => ['ROLE_EMPLOYEE', 'ROLE_USER'],
        ]);
        UserFactory::createMany(10);

        // source pour les icônes gratuites (voir README.md) : https://www.iconfinder.com/
        //////////////////////////////////////////////////////////////////////////////////
        foreach (UserFactory::repository()->findAll() as $user) {
            if (Factory::create()->boolean(90)) {
                $avatar = 'avatar-' . rand(1, 9) . '.png';
                // data/avatars -> public/uploads/avatars
                copy(__DIR__ . '/data/avatars/' . $avatar, __DIR__ . '/../../public/uploads/avatars/' . $avatar);
                // set avatar pathname
                $user->setAvatarPathname($avatar);
            }
        }

        $manager->flush();
    }
}
