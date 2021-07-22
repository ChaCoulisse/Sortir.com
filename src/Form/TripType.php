<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :'
            ])
            ->add('startHour', DateType::class, [
                'label'=>'Date et heure de la sortie :',
                'html5' => true,
                'widget'=>'single_text'
            ])
            ->add('limitDate', DateType::class, [
                'label'=>'Date limite d\'inscription :',
                'html5' => true,
                'widget'=>'single_text'
            ])
            ->add('limitedPlace', IntegerType::class, [
                'label'=>'Nombre de places :'
            ])
            ->add('duration', TimeType::class, [
                'label'=>'Durée :',
                'html5' => true,
                'widget'=>'single_text'
            ])
            ->add('infoTrip', TextareaType::class, [
                'label'=>'Description et infos :'
            ])
            ->add('campus',EntityType::class,[
                'mapped' => false,
                'label'=>'Campus :',
                'class'=> Campus::class,
                'choice_label'=>'name'
            ])
            ->add('place', EntityType::class,[
                'label'=>'Lieu :',
                'class'=> Place::class,
                'choice_label'=>'name'
            ])
            ->add('city',EntityType::class,[
                'mapped' => false,
                'label'=>'Ville :',
                'class'=> City::class,
                'choice_label'=>'name'
            ])
            ->add('street',EntityType::class,[
                'mapped' => false,
                'label'=>'Rue :',
                'class'=> Place::class,
                'choice_label'=>'street'
            ])
            ->add('zip_code',EntityType::class,[
                'mapped' => false,
                'label'=>'Code Postal :',
                'class'=> City::class,
                'choice_label'=>'zip_code'
            ])
            ->add('latitude',EntityType::class,[
                'mapped' => false,
                'label'=>'Latitude :',
                'class'=> Place::class,
                'choice_label'=>'latitude'
            ])
            ->add('longitude',EntityType::class,[
                'mapped' => false,
                'label'=>'Longitude :',
                'class'=> Place::class,
                'choice_label'=>'longitude'
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}