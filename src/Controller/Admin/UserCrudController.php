<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{

    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPassword($entityInstance);

        parent::updateEntity($entityManager, $entityInstance);
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $this->setUserPassword($entityInstance);

        parent::persistEntity($entityManager, $entityInstance); // TODO: Change the autogenerated stub
    }

    public function setUserPassword($entityInstance): void {
        $password = $this->getContext()->getRequest()->get('User')['password'];

        if ($entityInstance instanceof User) {
            if (!empty($password)) {
                $passwordHashed = $this->passwordHasher->hashPassword($entityInstance, $password);
                $entityInstance->setPassword($passwordHashed);
            }
        }
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield IdField::new('id', 'ID')->hideOnForm(),
            yield TextField::new('firstname', 'Prénom'),
            yield TextField::new('lastname', 'Nom de famille'),
            yield TextField::new('password')->hideOnIndex()
                ->setFormType(PasswordType::class)
                ->setFormTypeOptions(
                    [
                        'required' => false,
                        'empty_data' => '',
                        'attr' => ['autocomplete' => 'new-password'],
                    ]
                ),
            yield TextField::new('email','Email'),
            yield TextField::new('phone', 'Numéro de téléphone'),
            yield TextField::new('pc', 'Code postal'),
            yield TextField::new('city', 'Ville'),
            yield TextField::new('address', 'Adresse'),
            yield ArrayField::new('roles', 'Rôle')
                ->formatValue(function (array $roles) {
                    if (in_array('ROLE_ADMIN', $roles)) {
                        return <<<HTML
                            <span class="material-symbols-outlined">
                                admin_panel_settings
                            </span>
                            HTML;
                    } elseif (in_array('ROLE_USER', $roles)) {
                        if (in_array('ROLE_EMPLOYEE', $roles)) {
                            return <<<HTML
                            <span class="material-symbols-outlined">
                                work
                            </span>
                            HTML;
                        } return <<<HTML
                            <span class="material-symbols-outlined">
                                person
                            </span>
                            HTML;
                    } else {
                        return <<<HTML
                            <span class="material-symbols-outlined">
                                error
                            </span>
                            HTML;
                    }
                }),
        ];
    }

    public function configureAssets(Assets $assets): Assets
    {
        //return parent::configureAssets($assets); // TODO: Change the autogenerated stub
        return $assets
            ->addHtmlContentToHead('<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />');
    }

}
