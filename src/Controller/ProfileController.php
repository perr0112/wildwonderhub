<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AvatarType;
use App\Form\DeleteType;
use App\Form\ProfilePasswordType;
use App\Form\ProfileType;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher, SluggerInterface $slugger, TicketRepository $ticketRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        // formulaire pour les informations principales

        $formChangeInformations = $this->createForm(ProfileType::class, $user, [
            'attr' => ['class' => 'change-form']
        ])->add('edit', SubmitType::class, ['label' => 'Modifier mes informations', 'attr' => ['class' => 'btn button-primary full-width']]);

        $formChangeInformations->handleRequest($request);
        // validation du formulaire
        if ($formChangeInformations->isSubmitted() && $formChangeInformations->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte a bien été modifié!');
        }

        // formulaire pour un nouveau mot de passe

        $formChangePassword = $this->createForm(ProfilePasswordType::class, null, [
            'attr' => ['class' => 'password-form']
        ])->add('update', SubmitType::class, ['label' => 'Modifier mon mot de passe', 'attr' => ['class' => 'btn button-primary full-width']]);

        $formChangePassword->handleRequest($request);

        if ($formChangePassword->isSubmitted()) {
            $currentPassword = $formChangePassword->get('currentPassword')->getData();
            $comparePasswords = $userPasswordHasher->isPasswordValid($user,  $currentPassword);
            if (!$currentPassword || !$comparePasswords) {
                $this->addFlash('error', 'Le mot de passe saisi ne correspond pas à votre mot de passe.');
            } else {
                $newPassword = $formChangePassword->get('newPassword')->getData();
                if ($newPassword) {
                    $user->setPassword($userPasswordHasher->hashPassword($user, $newPassword));
                    $entityManager->flush();
                    $this->addFlash('success', 'Le mot de passe a bien été modifié!');
                } else {
                    $this->addFlash('error', 'Vous avez saisi deux mots de passe différents...');
                }
            }
        }

        // formulaire pour avatar

        $formChangeAvatar = $this->createForm(AvatarType::class, null, [
                'attr' => ['class' => 'avatar-form'], ]
        )->add('edit', SubmitType::class, ['label' => 'Modifier mon avatar', 'attr' => ['class' => 'btn button-primary full-width']]);

        $formChangeAvatar->handleRequest($request);

        if ($formChangeAvatar->isSubmitted() && $formChangeAvatar->isValid()) {
            $avatar = $formChangeAvatar->get('avatar')->getData();

            if ($avatar) {
                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatar->guessExtension();

                try {
                    $avatar->move(
                        $this->getParameter('profile_avatar_directory'),
                        $newFilename
                    );
                    $this->addFlash('success', 'Votre avatar a bien été modifié!');
                } catch(FileException $e) {
                    $this->addFlash('error', "Une erreur est survenue lors de l'upload de votre avatar.");
                }

                $user->setAvatarPathname($newFilename);
                $entityManager->persist($user);
                $entityManager->flush();
            }

        }

        $tickets = $ticketRepository->findBy(['user' => $user], ['date' => 'ASC'], 3);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formChangeInformations' => $formChangeInformations,
            'formChangePassword' => $formChangePassword,
            'formChangeAvatar' => $formChangeAvatar,
            'tickets' => $tickets,
        ]);
    }

    #[Route('/profile/delete', name: 'app_profile_delete')]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $formDelete = $this->createForm(DeleteType::class, null, [
            'attr' => ['class' => 'delete-form'],
        ]);

        $formDelete->handleRequest($request);

        if ($formDelete->isSubmitted()) {
            if ($formDelete->get('delete')->isClicked()) {
                // déconnexion de l'utilisateur courant
                $request->getSession()->invalidate();
                $this->container->get('security.token_storage')->setToken(null);
                // suppression de l'utilisateur
                $entityManager->remove($user);
                $entityManager->flush();

                $this->addFlash('success', 'Votre compte a bien été supprimé!');

                return $this->redirectToRoute('app_login');
            } else {
                return $this->redirectToRoute('app_profile', ['user' => $user]);
            }
        } else {
            return $this->render('profile/delete.html.twig', ['user' => $user, 'formDelete' => $formDelete]);
        }

    }

}
