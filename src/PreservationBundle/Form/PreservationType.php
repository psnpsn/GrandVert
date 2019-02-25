<?php

namespace PreservationBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateDebut', DateType::class, [
            'widget' => 'single_text',
            // this is actually the default format for single_text
            'format' => 'yyyy-MM-dd',
        ])
            ->add('dateFin' , DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
            ])
            ->add('nbPlaces',IntegerType::class, array(
                'attr' => array(
                    'placeholder' => 'Nombre De Places à Réserver ',
                )))

            ->add('EspaceDePreservation',EntityType::class,array('class'=>"PreservationBundle:EspaceDePreservation",

            'query_builder' => function (EntityRepository $er) {
                   
                    return $er->createQueryBuilder('s')


                        ->where('s.capacity >=3');

                },'choice_label'=>'lieu','multiple'=>false))
           ;


    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PreservationBundle\Entity\Preservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'preservationbundle_preservation';
    }


}
