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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $employe = $options["data"];
        // $roles = $employe->getRoles();
        $builder
            ->add('sexe', ChoiceType::class, [
                "label" => "Civilité",
                "choices" => [
                    "Homme" => "m",
                    "Femme" => "f"
                ],
                "attr" => [
                    "class" => "input"
                ],
                "multiple" => false,
                "expanded" => true
            ])
            ->add('nom', TextType::class, [
                "label" => false,
                "attr" => [
                    "placeholder" => "Nom",
                    "class" => "input"
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
                    "placeholder" => "Prénom",
                    "class" => "input"
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
                    "placeholder" => "Email",
                    "class" => "input"
                ],
                "invalid_message" => "Veuillez renseigner une adresse mail valide, elle sera utilisée pour l'identification"
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
            ->add('roles', ChoiceType::class, [ 
                "mapped" => false,
                "label" => "Rôle",
                "placeholder" => "Rôle",
                "choices" => [
                    "Administrateur" => "ROLE_ADMIN",
                    "RH" => "ROLE_RH",
                    "Manager" => "ROLE_MANAGER",
                    "Employé" => "ROLE_EMPLOYE"
                ],
                "multiple" => false,
                "expanded" => true,
                "data" => $employe->getRoles() ? $employe->getRoles()[0] : "ROLE_EMPLOYE",
                // "data" => $roles[0] // permet d'outrepasser l'array et de garder multiple en "false"
                "attr" => [
                    "class" => "input2"
                ]
            ])
            ->add('service', ChoiceType::class, [
                "label" => "Service :",
                "choices" => [
                    "Informatique" => "informatique",
                    "Communication" => "communication",
                    "Ressources Humaines" => "ressources_humaines",
                    "Commercial" => "commercial"
                ],
                "attr" => [
                    "class" => "input"
                ]
            ])
            ->add('nb_conges', IntegerType::class, [
                "label" => "Nombre de jours de congés",
                "data" => 0
            ])
            ->add('photo', FileType::class, [
                "mapped" => false,
                "label" => "Photo de profil",
                "required" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
