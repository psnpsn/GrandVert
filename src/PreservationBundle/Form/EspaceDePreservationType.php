<?php

namespace PreservationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EspaceDePreservationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom',TextType::class, array(
            'attr' => array(
                'placeholder' => 'Nom De L\'espace De Préservation',
            )))
                ->add('capacity',IntegerType::class, array(
                    'attr' => array(
                        'placeholder' => 'Capacité De L\'espace De Préservation ',
                    )))
                ->add('lieu',TextType::class, array(
                    'attr' => array(
                        'placeholder' => 'Lieu De L\'espace De Préservation',
                    )));
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PreservationBundle\Entity\EspaceDePreservation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'preservationbundle_espacedepreservation';
    }


}
