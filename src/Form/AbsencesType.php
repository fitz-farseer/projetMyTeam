<?php

namespace App\Form;

use App\Entity\Absences;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Début de l\'absence'
            ])
            ->add('date_retour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fin de l\'absence'
            ])
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function (Employe $e) {
                    return $e->getNom() . ' ' . $e->getPrenom();
                },
                'placeholder' => 'Choisissez un employé'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Absences::class,
        ]);
    }
}
