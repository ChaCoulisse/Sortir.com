<?php

namespace App\Form;

use App\Entity\Trip;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
                'label' => 'Campus',
                'multiple'=>false])
            ->add('name', TextType::class, [
                'label' => 'Le nom de la série contient' ])
            ->add('start', DateType::class, [
                'mapped' => false,
                'html5' => true,
                'widget'=>'single_text',
                'label' => 'Entre'])
            ->add('end', DateType::class, [
                'mapped' => false,
                'html5' => true,
                'widget'=>'single_text',
                'label' => 'et'])
            ->add('organizer', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties dont je suis l\'organisateur/trice',
                'required' => false])
            ->add('participant', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties auxquelles je suis inscrit/e',
                'required' => false])
            ->add('notParticipant', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false])
            ->add('state', CheckboxType::class, [
                'mapped' => false,
                'label'    => 'Sorties passées',
                'required' => false])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trip::class,
        ]);
    }
}
