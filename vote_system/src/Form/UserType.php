<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Adresse Email',
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('surname', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('birthDate', TextType::class, [
                'label' => 'Date de naissance',
            ])
            ->add('birthPlace', TextType::class, [
                'label' => 'Lieu de naissance',
            ])
            ->add('sex', TextType::class, [
                'label' => 'Sexe',
            ])
            ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
