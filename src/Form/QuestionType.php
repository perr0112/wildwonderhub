<?php

namespace App\Form;

use App\Entity\Question;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class,
                [
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Écrivez le titre de votre post'
                    ],
                    'constraints' => [
                        new Length(['min' => 2, 'max' => 50])
                    ]
                ]
            )
            ->add('description', TextareaType::class,
                [
                    'required' => true,
                    'attr' => [
                        'placeholder' => 'Écrivez votre message'
                    ],
                    'constraints' => [
                        new Length(['min' => 10])
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
