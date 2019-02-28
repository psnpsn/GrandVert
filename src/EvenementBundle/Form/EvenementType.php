<?php

namespace EvenementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;


class EvenementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $formation='Formation';
        $conference='Conference';
        $seminaire='Seminaire';
        $congres='Congres';
        $forum='Forum';
        $foire='Foire';
        $ariana='Ariana';
        $beja='Béja';
        $benarous='Ben Arous';
        $bizerte='Bizerte';
        $gabes='Gabes';
        $gafsa='Gafsa';
        $jandouba='Jandouba';
        $kairouan='Kairouan';
        $kasserine='Kasserine';
        $kebili='Kebili';
        $kef='Kef';
        $mahdia='Mahdia';
        $manouba='Manouba';
        $medenine='Medenine';
        $monastir='Monastir';
        $nabeul='Nabeul';
        $sfax='Sfax';
        $sidibouzid='Sidi Bouzid';
        $siliana='Siliana';
        $sousse='Sousse';
        $tataouine='Tataouine';
        $tozeur='Tozeur';
        $tunis='Tunis';
        $zaghouan='Zaghouan';

        $builder
            ->add('categorie', ChoiceType::class, [
                'choices'  => [
                    'Formation' => $formation,
                    'Conférence' => $conference,
                    'Séminaire' => $seminaire,
                    'Congrès' => $congres,
                    'Forum' => $forum,
                    'Foire' => $foire,
                ],
            ])
            ->add('titre')
            ->add('organisation')
            ->add('description',TextareaType::class)
            ->add('image', FileType::class, ['label' => 'image plante','data_class' => null])
            ->add('lieu')
            ->add('adresse')
            ->add('gouvernorat', ChoiceType::class, [
                'choices'  => [
                    'Ariana' => $ariana,
                    'Béja' => $beja,
                    'Ben Arous' => $benarous,
                    'Bizerte' => $bizerte,
                    'Gabes' => $gabes,
                    'Gafsa' => $gafsa,
                    'Jandouba' => $jandouba,
                    'Kairouan' => $kairouan,
                    'Kasserine' => $kasserine,
                    'Kebili' => $kebili,
                    'Kef' => $kef,
                    'Mahdia' => $mahdia,
                    'Manouba' => $manouba,
                    'Medenine' => $medenine,
                    'Monastir' => $monastir,
                    'Nabeul' => $nabeul,
                    'Sfax' => $sfax,
                    'Sidi Bouzid' => $sidibouzid,
                    'Siliana' => $siliana,
                    'Sousse' => $sousse,
                    'Tataouine' => $tataouine,
                    'Toszeur' => $tozeur,
                    'Tunis' => $tunis,
                    'Zaghouan' => $zaghouan,
                ],
            ])
            ->add('dated',DateTimeType::class, ['label' => 'Date de début',

                    'years'=> range(2019,2025)


            ])
            ->add('datef', DateTimeType::class, ['label' => 'Date de fin',

                'years'=> range(2019,2025)


            ])


            ->add('prix', MoneyType::class, ['label' => 'Prix',
                'divisor' => 1, 'currency'=>'TND'
            ])

            ->add('Submit',SubmitType::class , ['label' => 'créer événement']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EvenementBundle\Entity\Evenement'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'evenementbundle_evenement';
    }

}
