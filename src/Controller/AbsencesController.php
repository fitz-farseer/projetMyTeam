<?php

namespace App\Controller;

use App\Entity\Absences;
use App\Form\AbsencesEmployeType;
use App\Form\AbsencesType;
use App\Form\AbsencesValidationType;
use App\Repository\AbsencesRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/absences')]
class AbsencesController extends AbstractController
{
    #[Route('/', name: 'absences_index', methods: ['GET'])]
    public function index(AbsencesRepository $absencesRepository, EmployeRepository $er): Response
    {
        return $this->render('absences/index.html.twig', [
            'absences' => $absencesRepository->findAll(),
            'absencesService' => $absencesRepository->findByService($this->getUser()->getService()),
            'employesService' => $er->findByService($this->getUser()->getService()), 
            'employes' => $er->findAll()
        ]);
    }

    #[Route('/absences/liste', name: 'absences_liste_validation', methods: ['GET'])]
    public function list(AbsencesRepository $absencesRepository, EmployeRepository $er): Response
    {
        return $this->render('absences/liste.html.twig', [
            'absencesAValider' => $absencesRepository->findByStatut('En attente'),
            'absences' => $absencesRepository->findAll(),
            'absencesService' => $absencesRepository->findByService($this->getUser()->getService()),
            'employesService' => $er->findByService($this->getUser()->getService()), 
            'employes' => $er->findAll()
        ]);
    }

    #[Route('/new', name: 'absences_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $absence = new Absences();
        $form = $this->createForm(AbsencesType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jours = (date_diff(($form->get('date_debut')->getData()), ($form->get('date_retour')->getData())))->days;
            $absence->setNbJours($jours);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);
            $entityManager->flush();

            return $this->redirectToRoute('absences_index');
        }

        return $this->render('absences/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new/employe', name: 'employe_absences_new', methods: ['GET', 'POST'])]
    public function signalerAbsence(Request $request): Response
    {
        $absence = new Absences();
        $absence->setEmploye($this->getUser());
        $form = $this->createForm(AbsencesEmployeType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $jours = (date_diff(($form->get('date_debut')->getData()), ($form->get('date_retour')->getData())))->days;
            $absence->setNbJours($jours);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($absence);  
            $entityManager->flush();

            $this->addFlash('success', 'Absence enregistrée !');

            return $this->redirectToRoute('absences_index');
        }

        return $this->render('absences/new.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'absences_show', methods: ['GET'])]
    public function show(Absences $absence): Response
    {
        return $this->render('absences/show.html.twig', [
            'absence' => $absence,
        ]);
    }

    #[Route('/{id}/edit', name: 'absences_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Absences $absence): Response
    {
        $form = $this->createForm(AbsencesType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('absences_index');
        }

        return $this->render('absences/edit.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit/employe', name: 'absences_edit_employe', methods: ['GET', 'POST'])]
    public function editEmploye(Request $request, Absences $absence): Response
    {
        $form = $this->createForm(AbsencesEmployeType::class, $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('absences_index');
        }

        return $this->render('absences/edit.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/manager/{id}/valider', name: 'absences_validation', methods: ['GET', 'POST'])]
    public function valider(Request $request, Absences $absence): Response
    {
        $form = $this->createForm(AbsencesValidationType::class , $absence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (($form->get("statut")->getData()) == 'Validée'){
                $employe = $absence->getEmploye();
                $joursConges = $employe->getNbConges();
                $joursConges -= $absence->getNbJours(); 
                $employe->setNbConges($joursConges);
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('absences_index');
            } else {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('absences_index');
            }
        }

        return $this->render('absences/edit.html.twig', [
            'absence' => $absence,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'absences_delete', methods: ['POST'])]
    public function delete(Request $request, Absences $absence): Response
    {
        if ($this->isCsrfTokenValid('delete'.$absence->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($absence);
            $entityManager->flush();
        }

        return $this->redirectToRoute('absences_index');
    }
}
