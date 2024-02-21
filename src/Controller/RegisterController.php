<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class, $user, [
            'attr' => ['class' => 'register-form']
        ])->add('register', SubmitType::class, ['label' => "S'inscrire", 'attr' => ['class' => 'btn button-primary full-width']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // vérification si un utilisateur a déjà l'email saisi
            $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);

            if ($existingUser) {
                $this->addFlash('error', 'Cet email est déjà associé à un compte.');
            } else {
                $password = $form->get('password')->getData();
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));
                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a bien été créé!');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('register/index.html.twig', [
            'form' => $form
        ]);
    }
}
