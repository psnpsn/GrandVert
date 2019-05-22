<?php

namespace JardinBundle\Form;

use Doctrine\DBAL\Types\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlantationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plante',EntityType::class,array(
                'class'=>'PlanteBundle:plante',
                'choice_label'=>'nom',
                'expanded'=>true,
                'multiple'=>false,
                'label'=>false
            ))
            ->add('typeP',ChoiceType::class,[
                'choices'  => [
                    'Plante' => 'Plante',
                    'Graine' => 'Graine',
                ]], ['label' => 'Type?'])
            ->add('quantite',\Symfony\Component\Form\Extension\Core\Type\IntegerType::class, ['label' => 'Quantité'])
            ->add('add', SubmitType::class, ['label' => 'Confirmer']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JardinBundle\Entity\Plantation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'jardinbundle_plantation';
    }


}
