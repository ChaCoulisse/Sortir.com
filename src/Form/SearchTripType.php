<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTripType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('campus', ChoiceType::class, [
                'choices' =>[
                    'Nantes' =>'Nantes',
                    'Rennes' =>'Rennes',
                    'Niort' => 'Niort'
                ],
                'multiple'=>false
            ])
            ->add('name')
            ->add('startHour')






        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
