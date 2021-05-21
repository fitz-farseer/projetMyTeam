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
            'employes' => $employeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'employe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Encoder $encoder): Response
    {
        $employe = new Employe();
        $form = $this->createForm(EmployeType::class, $employe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mdp = $form->get("password")->getData();
            $mdp = $encoder->encodePassword($employe, $mdp);
            $role = $form->get("roles")->getData();


            // Gestion photo par défaut en fonction du sexe :
            if ($form->get("sexe")->getData() == "m") {
                $employe->setPhoto("male");
            } else {
                $employe->setPhoto("female");
            }

            $employe->setPassword($mdp);
            $employe->setRoles([$role]);


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
            if (file_exists($this->getParameter("dossier_images") . "/" . $photo)) {
                unlink($this->getParameter("dossier_images") . "/" . $photo);
            }
                $nomPhoto = pathinfo($photoDL->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomPhoto);
                $nouveauNom .= "-" . uniqid() . "." . $photoDL->guessExtension();
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

    #[Route('/{id}', name: 'employe_delete', methods: ['POST'])]
    public function delete(Request $request, Employe $employe): Response
    {
        if ($this->isCsrfTokenValid('delete' . $employe->getId(), $request->request->get('_token'))) {

            $photo = $employe->getPhoto();
            if (file_exists($this->getParameter("dossier_images") . "/" . $photo)) {
                unlink($this->getParameter("dossier_images") . "/" . $photo);
            } // verifie si la photo existe avant d'essayer de la supprimer

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($employe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('employe_index');
    }
}
