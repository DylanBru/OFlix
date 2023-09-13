<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReviewType extends AbstractType
{
    /**
     * Création du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom'
                ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel'
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Critique'
                ])
            ->add('rating', ChoiceType::class, [
                'choices'  => [
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1,
                ],
                'placeholder' => 'Votre choix ...'
            ])
            ->add('reactions', ChoiceType::class, [
                'choices'  => [
                    'Rire' => 'smile',
                    'Pleurer' => 'cry',
                    'Réfléchir' => 'think',
                    'Dormir' => 'sleep',
                    'Rêver' => 'dream',
                ],
                // Les reactions sont un tableau, on aura la possibilité d'avoir plusieurs choix ici
                 'multiple' => true,
                 // Pour que chaque chois soit prit en compte, on ajoute ...
                 'expanded' => true
            ])
            ->add('watchedAt', DateType::class, [
                'label' => 'Vous avez vu ce film le ...',
                // Pour indiquer que le format de la propriété $watchedAt est une datetimeimmutable, on ajoute
                'input' => 'datetime_immutable'
            ])
            // Pas besoin de movie car deja présent dans l'URL
            // ->add('movie')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
