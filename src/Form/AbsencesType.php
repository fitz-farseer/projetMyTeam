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
use Symfony\Component\Validator\Constraints\GreaterThan;

class AbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date_debut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Début de l\'absence',
                'constraints' => [
                    new GreaterThan([
                        (new \DateTime('+6 days')),
                        "message" => "Le mot de passe doit contenir au moins 1 majuscule, 1 miniscule, 1 chiffre et 1 caractère spécial et doit faire entre 6 t 10 caractères"
                        ])
                ]
            ])
            ->add('date_retour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Fin de l\'absence', 
                'constraints' => [
                ]
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
