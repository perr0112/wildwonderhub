<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Entity\User;
use App\Form\DeleteType;
use App\Form\TicketType;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TicketController extends AbstractController
{
    /// methode de conversion du type en price for entity ticket
    private function forsetprice($type){
        switch ($type) {
            case 'ENFANT':
                $price = 12;
                break;
            case 'ETUDIANT':
                $price = 15;
                break;
            case 'SENIOR':
                $price = 16;
                break;
            case 'JUNIOR':
                $price = 0;
                break;
            case 'HANDICAPE':
                $price = 14;
                break;
            case 'CLASSIC':
                $price = 20;
                break;
            default:
                $price = null;
        } return $price;
    }

    private function checkAccess($user): ?RedirectResponse
    {
        if ($user->isAdmin() || $user->isEmployee()) {
            $this->addFlash('warning', 'En tant qu\'administrateur ou employé de ce Zoo, vous ne pouvez pas acheter ni consulter de billets. En revanche, vous pouvez consulter ici les événements à venir et les modifier, ou supprimer, ainsi que le nombre de places restantes.');
            return $this->redirectToRoute('app_events');
        }
        return null;
    }

    /**
     * @throws \Exception
     */
    #[Route('/ticket', name: 'app_ticket')]
    public function index(TicketRepository $ticketRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $res = $this->checkAccess($user);
        if ($res) {
            return $res;
        }

        $tickets = $ticketRepository->findBy(['user' => $user]);

        $pastTickets = array_filter($tickets, function ($ticket) {
            return $ticket->getDate() < new \DateTime('-1 day');
        });

        $availableTickets = array_filter($tickets, function ($ticket) {
            return $ticket->getDate() >= new \DateTime('-1 day');
        });

        usort($availableTickets, function ($ticket1, $ticket2) {
            return $ticket1->getDate() <=> $ticket2->getDate();
        });
            /////// Formulaire

        $newticket = new Ticket();
        $form = $this->createForm(TicketType::class, $newticket)
            ->add('submit', SubmitType::class, ['label' => 'Obtenir mon nouveau ticket', 'attr' => ['class' => 'btn button-primary full-width mt-50']]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newticket->setUser($this->getUser());
            $newticket->setPrice( $this->forsetprice($newticket->getType()));

            /// comparaison date

             if ($newticket->getDate() < new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'))) {
                 $this->addFlash('error',"La date donnée n'est pas valable, veuillez en saisir une autre.");
             }
             else {
                 $entityManager->persist($newticket);
                 $entityManager->flush();
                 $this->addFlash('success', "Votre ticket a été enregistré!");
             }
        }

        return $this->render('ticket/index.html.twig', [
            'controller_name' => 'TicketController',
            'tickets' => $tickets,
            'form'=>$form,
            'pastTickets' => $pastTickets,
            'availableTickets' => $availableTickets,
        ]);
    }

    #[Route('/ticket/{id}', name: 'app_ticket_id')]
    public function show(Ticket $ticket): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        } else if ($ticket->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour consulter ce ticket.');
            return $this->redirectToRoute('app_ticket');
        }

        $res = $this->checkAccess($user);
        if ($res) {
            return $res;
        }

        return $this->render('ticket/show.html.twig', [
            'ticket' => $ticket,
            'user'=>$user
        ]);
    }

    /**
     * @throws \Exception
     */
    #[Route ('/ticket/{id}/update', name: 'app_ticket_update',  requirements: ['id' => '\d+'])]
    public function update(Ticket $ticket,Request $request,EntityManagerInterface $entityManager) :Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        } else if ($ticket->getUser() !== $user) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour consulter ce ticket.');
            return $this->redirectToRoute('app_ticket');
        }

        $res = $this->checkAccess($user);
        if ($res) {
            return $res;
        }

        // modification d'un ticket

        $form_mdt=$this->createForm(TicketType::class, $ticket)
            ->add('submit',SubmitType::class, ['label' => 'Enregistrer les modifications du ticket','attr' => ['class' => 'btn button-primary full-width mt-50']]);

        $form_mdt->handleRequest($request);

        if ($form_mdt->isSubmitted() && $form_mdt->isValid()) {

            $ticket->setUser($this->getUser());
            $ticket->setPrice( $this->forsetprice($ticket->getType()));

            /// comparaison date

            if ($ticket->getDate() < new \DateTimeImmutable('now', new \DateTimeZone('Europe/Paris'))) {
                $this->addFlash('error',"La date donnée n'est pas valable");
            }

            else {
                $entityManager->persist($ticket);
                $entityManager->flush();
                $this->addFlash('success', "Votre ticket a été modifier !");
            }
        }

        // suppression d'un ticket

        $form_delt = $this->createForm(DeleteType::class, null, [
            'attr' => ['class' => 'delete-form'],
        ]);

        $form_delt->handleRequest($request);

        if ($form_delt->isSubmitted()) {
            if ($form_delt->get('delete')->isClicked()) {
                // suppression du ticket
                $entityManager->remove($ticket);
                $entityManager->flush();

                $this->addFlash('success', 'Ce ticket a bien été supprimé!');
                return $this->redirectToRoute('app_ticket');
            }

        }
        return $this->render('ticket/update.html.twig', [
            'ticket' => $ticket,
            'user'=>$user,
            'form_mdt'=>$form_mdt,
            'form_delt'=>$form_delt
    ]); }

}
