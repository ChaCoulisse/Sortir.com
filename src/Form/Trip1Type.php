<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\State;
use App\Entity\Trip;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Trip1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('name', TextType::class,[
                'label'=> 'Nom de la sortie :',
                'required'=>true
            ])
            ->add('startHour', DateType::class,[
                'label'=> 'Date :',
                'required'=>true,
                'html5' =>true,
                'widget'=>'single_text',

            ])
            ->add('duration', TimeType::class,[
                'label'=> 'Durée :',
                'widget'=>'single_text',
                'required'=>true,
            ] )
            ->add('limitDate', DateType::class,[
                'label' => 'Date limite d\'inscription',
                  'widget'=>'single_text',
                'required'=>true,
                'html5' =>true,

            ])
            ->add('limitedPlace', IntegerType::class, [
                'label' => 'Nombre maximum de participants ',
                'required' => true,
            ])
            ->add('infoTrip', TextType::class, [
                'label'=>'Description et infos :',
                'required' => false,
            ])
            ->add('campus', EntityType::class, [
                'label'=>'Campus :',
                'class'=>Campus::class,
                'choice_label'=>'name',
                'required'=>'true',
            ])
            ->add('state', EntityType::class, [
                'class'=>State::class,
                'choice_label'=>function(State $state ){
                return $state->getWording();
                },
                'required'=>'true',
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
