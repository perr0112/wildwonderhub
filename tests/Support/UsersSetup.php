<?php

namespace App\Tests\Support;

use App\Entity\User;
use App\Factory\UserFactory;
use Faker\Factory;

trait UsersSetup
{

    private User $userAdmin;
    private User $userBasic;

    public function createUsers(): void
    {
        $faker = Factory::create();
        $uniqueEmailAdmin = $faker->unique()->safeEmail;
        $uniqueEmailBasic = $faker->unique()->safeEmail;

        $userAdmin = UserFactory::createOne([
            'firstname' => 'Clément',
            'lastname' => 'Admin',
            'email' => $uniqueEmailAdmin,
            'password' => 'test',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $this->userAdmin = $userAdmin->object();

        $userBasic = UserFactory::createOne([
            'firstname' => 'Clément',
            'lastname' => 'Admin',
            'email' => $uniqueEmailBasic,
            'password' => 'test',
            'roles' => ['ROLE_USER'],
        ]);

        $this->userBasic = $userBasic->object();
    }

}