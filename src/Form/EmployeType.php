<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $employe = $options["data"];
        $builder
            ->add('sexe', ChoiceType::class, [
                "label" => false,
                "choices" => [
                    "Civilité" => "",
                    "Homme" => "m",
                    "Femme" => "f"
                ]
            ])
            ->add('nom', TextType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Nom"
                ],
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "minMessage" => " Le nom doit faire au moins 2 caractères",
                        "max" => 50,
                        "maxMessage" => " Le nom ne doit pas faire plus de 50 caractères"
                    ]),
                    new NotBlank(["message" => "Le nom doit être renseigné"])
                ]
            ])
            ->add('prenom', TextType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Prénom"
                ],
                "constraints" => [
                    new Length([
                        "min" => 2,
                        "minMessage" => " Le prénom doit faire au moins 2 caractères",
                        "max" => 50,
                        "maxMessage" => " Le prénom ne doit pas faire plus de 50 caractères"
                    ]),
                    new NotBlank(["message" => "Le prénom doit être renseigné"])
                ]

            ])
            ->add('email', EmailType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Email"
                ],
                "invalid_message" => "Veuillez renseigner une adresse mail valide, elle sera utilisée pour l'identification"
            ])
            ->add('password', PasswordType::class, [
                "mapped" => false,
                "label" => false,
                "attr" => [
                    "placeholder" => "Mot de passe"
                ],
                "constraints" => [
                    // new Regex([
                    //     "pattern" => "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[-+!*$@%_])([-+!*$@%_\w]{6,10})$/",
                    //     "message" => "Le mot de passe doit contenir au moins 1 majuscule, 1 miniscule, 1 chiffre et 1 caractère spécial et doit faire entre 6 t 10 caractères"
                    // ])
                ],
                "help" => "Le mot de passe doit contenir au moins 1 majuscule, 1 miniscule, 1 chiffre et 1 caractère spécial parmi -+!*$@%_ et doit faire entre 6 t 10 caractères",
                "required" => $employe->getId() ? false : true
            ])
            ->add('roles', ChoiceType::class, [
                "label" => false,
                "choices" => [
                    "Rôle" => "",
                    "Administrateur" => "ROLE_ADMIN",
                    "RH" => "ROLE_RH",
                    "Manager" => "ROLE_MANAGER",
                    "Employé" => "ROLE_EMPLOYE"
                ],
                "multiple" => true,
                "expanded" => false
            ])
            ->add('service', ChoiceType::class, [
                "label" => false,
                "choices" => [
                    "Service" => "",
                    "Informatique" => "informatique",
                    "Communication" => "communication",
                    "Ressources Humaines" => "rh",
                    "Commercial" => "commercial"
                ]
            ])
            ->add('nb_conges', IntegerType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "jours de congés",
                ],
            ])
            ->add('photo', FileType::class, [
                "label" => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
