<?php

// src/Form/PublicationType.php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'attr' => [
                    'minlength' => 4,
                    'maxlength' => 200,
                    'placeholder' => "Qu'avez-vous en tête?"
                ]
            ])
            ->add('publier', SubmitType::class, [
                'label' => 'Feeder!',
                'attr' => [
                    'id' => 'feedy-new-submit'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}
