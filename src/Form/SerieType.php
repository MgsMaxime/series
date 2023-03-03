<?php

namespace App\Form;

use App\Entity\Serie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class SerieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('overview', TextareaType::class, ['required' => false])
            ->add('status', ChoiceType::class, ['choices' => ["Ended" => "ended", "Cancelled" => "cancelled", "Returning" => "returning"], 'multiple' => false, 'expanded' => false])
            ->add('status', ChoiceType::class, ['choices' =>
                [
                    "Ended" => "ended", "Cancelled" => "cancelled", "Returning" => "returning"
                ],
                'multiple' => false, 'expanded' => false
            ])
            ->add('vote')
            ->add('popularity')
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Western' => "western",
                    'Comedy' => "comedy",
                    'Drama' => 'drama'
                ]
            ])
            ->add('firstAirDate', DateType::class, [
                'label' => 'First air date : ',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('lastAirDate', DateType::class, [
                'label' => 'Last air date : ',
                'html5' => true,
                'widget' => 'single_text'
            ])
            ->add('backdrop')
            ->add('poster', FileType::class, [
                'mapped'=>false,
                'constraints'=>[
                    new Image([
                        "maxSize"=>'5000k',
                        "mimeTypesMessage"=>"Image not allowed!"
                    ])
                ]
            ])
            ->add('tmdbId');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Serie::class,
            //permet de supprimer tout les requirs mis en place dans le HTML
            'required' => false
        ]);
    }
}
