<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ModifierProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $employe = $options["data"];
        // $roles = $employe->getRoles();
        $builder
            ->add('email', EmailType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Email",
                    "class" => "input"
                ],
                "invalid_message" => "Veuillez renseigner une adresse mail valide, elle sera utilisée pour l'identification",
                "required" => $employe->getId() ? false : true
            ])
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Mot de passe",
                    "class" => "input"
                ],
                "constraints" => [
                    new Regex([
                        "pattern" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,10})$/",
                        "message" => "Le mot de passe doit contenir au moins 1 majuscule, 1 miniscule, 1 chiffre et 1 caractère spécial et doit faire entre 6 t 10 caractères"
                    ])
                ],
                "required" => $employe->getId() ? false : true
            ])
            ->add('photo', FileType::class, [
                "mapped" => false,
                "label" => "Photo de profil",
                "required" => $employe->getId() ? false : true
            ]);

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
