<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Sondage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SondageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du sondage'
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du sondage'
            ])
            ->add('dateStart', DateType::class)
            ->add('dateEnd', DateType::class)
            ->add('question', QuestionType::class, [
                'label' => false
            ])
            ->add('answers', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sondage::class,
        ]);
    }
}
