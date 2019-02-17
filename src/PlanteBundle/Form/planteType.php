<?php

namespace PlanteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class planteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('photo',FileType::class, ['label' => 'image plante','data_class' => null])->add('nom')->add('Description',TextareaType::class)->add('stock',TextType::class)->add('prix')->add('hauteur')
            ->add('fertiliseur')->add('categorie',ChoiceType::class, [
                'choices'  => [
                    'Legume' => 'Legume',
                    'Fruit' => 'Fruit',
                    'Herbe' => 'Herbe',
                    'Fleur' => 'Fleur',
                ],
            ])->add('season');
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PlanteBundle\Entity\plante'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'plantebundle_plante';
    }


}
