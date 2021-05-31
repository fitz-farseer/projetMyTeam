<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface as Encoder;


#[Route('/employe')]
class EmployeController extends AbstractController
{
    #[Route('/', name: 'employe_index', methods: ['GET'])]
    public function index(EmployeRepository $employeRepository): Response
    {
        return $this->render('employe/index.html.twig', [
            'employesService' => $employeRepository->findByService($this->getUser()->getService()),
            'employes' => $employeRepository->findAll()
        ]);
    }

    #[Route('gestion/new', name: 'employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Encoder $encoder): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $form->get("password")->getData();           // Récupère le mdp saisi dans le formulaire
            $mdp = $encoder->encodePassword($employe, $mdp);    // Hashe le mdp récupèré
            $role = $form->get("roles")->getData();             // Récupère le rôle assigné dans le formulaire


            // Gestion photo par défaut en fonction du sexe :
            if ($form->get("sexe")->getData() == "m") {         // Si le sexe est "m"
                $employe->setPhoto("male");                     // la photo aura pour valeur "male"
            } else {                                            // Sinon,
                $employe->setPhoto("female");                   // elle aura pour valeur "female"
            }

            $employe->setPassword($mdp);
            $employe->setRoles([$role]);                        // $role doit être contenu dans un array, car Symfony le considère comme tel (ROLE_USER assigné par défaut en plus du role renseigné à l'ajout d'un nouvel employé)


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employe);
            $entityManager->flush();

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/new.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'employe_show', methods: ['GET'])]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }

    #[Route('/{id}/edit', name: 'employe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Employe $employe, Encoder $encoder): Response
    {
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion de la photo si téléchargée :
            $destination = $this->getParameter("dossier_images");
            if ($photoDL = $form->get("photo")->getData()) {

                $photo = $employe->getPhoto();
                // On vérifie si l'employé avait une photo avant modification
                if (file_exists($this->getParameter("dossier_images") . "/" . $photo)) {
                    // Si il en avait une, on la supprime pour la remplacer par celle ajoutée
                    unlink($this->getParameter("dossier_images") . "/" . $photo);
                }
                
                // On récupèrte le nom du fichier
                $nomPhoto = pathinfo($photoDL->getClientOriginalName(), PATHINFO_FILENAME);
                // On remplace les espaces par des "_"
                $nouveauNom = str_replace(" ", "_", $nomPhoto);
                // On ajoute une suite de chiffre aléatoires
                $nouveauNom .= "-" . uniqid() . "." . $photoDL->guessExtension();
                // On déplace le fichier dans le dossier $destination et on le renome par $nouveauNom
                $photoDL->move($destination, $nouveauNom);

                $employe->setPhoto($nouveauNom);
            }
            if ($form->get("password")->getData() != "") {
                $mdp = $form->get("password")->getData();
                $mdp = $encoder->encodePassword($employe, $mdp);
                $employe->setPassword($mdp);
            }

            $role = $form->get("roles")->getData();
            $employe->setRoles([$role]);


            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('employe_index');
        }

        return $this->render('employe/edit.html.twig', [
            'employe' => $employe,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/{id}', name: 'employe_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {

            $photo = $employe->getPhoto();
            // On vérifie si la photo existe
            if (file_exists($this->getParameter("dossier_images") . "/" . $photo)) {
                // Si elle existe, on la supprime en même temps que l'employé
                unlink($this->getParameter("dossier_images") . "/" . $photo);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employe_index');
    }
}
