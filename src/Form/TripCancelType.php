<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Trip;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TripCancelType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label'=>'Nom de la sortie :',
            ])
//            ->add('startHour', DateType::class, [
//                'label'=>'Date de la sortie :',
//                'html5' => true,
//                'widget'=>'single_text'
//            ])
            ->add('campus',TextType::class,[
                'mapped' => false,
                'label'=>'Campus :',
//                'choice_value'=>function (?Campus $entity) {
//                    return $entity ? $entity->getName() : '';
//               },
            ])
            ->add('city',EntityType::class,[
                'mapped' => false,
                'label'=>'Ville :',
                'class'=> City::class,
                'choice_label'=>'name'
            ])
            ->add('cancelReason', TextareaType::class, [
                'label'=>'Motif :'
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
