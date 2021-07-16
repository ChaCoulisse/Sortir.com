<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Place;
use App\Entity\Trip;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'label'=>'Date limite d\'inscription :'
            ])
            ->add('limitedPlace', Integer::class, [
                'label'=>'Nombre de places :'
            ])
            ->add('duration', Integer::class, [
                'label'=>'Durée :'
            ])
            ->add('infoTrip', TextareaType::class, [
                'label'=>'Description et infos :'
            ])
            ->add('name',EntityType::class,[
                'label'=>'Ville :',
                'class'=> City::class,
                'choice_label'=>'name'
            ])
            ->add('place', EntityType::class,[
                'label'=>'Lieu :',
                'class'=> Place::class,
                'choice_label'=>'name'
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
