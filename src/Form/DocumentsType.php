<?php

namespace App\Form;

use App\Entity\Documents;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class DocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $employe = $options["data"];
        $builder
            ->add('name', FileType::class, [
                "mapped" => false,
                "label" => false,
                "attr" => [
                    "class" => "custom-file-input",
                    "id" => "customFile"
                ]
            ])
            ->add('type', ChoiceType::class, [
                "label" => "Type de document",
                "choices" => [
                    "Arrêt maladie" => "maladie",
                    "Congés payés" => "cp",
                    "Autre" => "autre"
                ]
            ])
            ->add('destinataire', EntityType::class, [
                "class" =>Employe::class,
                "choice_label" => function(Employe $e){
                    return ucfirst($e->getNom()) . " " .  ucfirst($e->getPrenom());
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
