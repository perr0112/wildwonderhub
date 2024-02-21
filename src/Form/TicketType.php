<?php

namespace App\Form;

use App\Entity\Event;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date',DateType::class,[
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
                ],
            ] )
           // ->add('price', MoneyType::class,[

           // ])
            ->add('type',ChoiceType::class,[
               'choices' => [
                   'Classique' =>'CLASSIC',
                   'Junior' =>'JUNIOR',
                   'Enfant' => 'ENFANT',
                   'Senior' => 'SENIOR',
                   'Étudiant' => 'ÉTUDIANT',
                   'Personne handicapée' => 'HANDICAPE'
               ],
               'data' => 'CLASSIC'
           ])

           /* ->add('event',EntityType::class,[
                'class' => Event::class,
                'choice_label' => 'name',
                'multiple'=>true,


                    ])
            ->add('user',EntityType::class,[
                'class' => Event::class,
                'choice_label' => 'firstName'.'lastName',
                'multiple'=>false] )
        */;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
