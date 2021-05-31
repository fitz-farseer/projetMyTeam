<?php

namespace App\Controller;

use App\Repository\AbsencesRepository;
use App\Repository\DocumentsRepository;
use App\Repository\EmployeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'profil_index')]
    // #[IsGranted('ROLE_EMPLOYE')]

    public function index(EmployeRepository $employeRepository, AbsencesRepository $ar, DocumentsRepository $dr): Response
    {
        $user = $this->getUser();
        $service = $user->getService();
        return $this->render('profil/index.html.twig', [
            'employesService' => $employeRepository->findByService($this->getUser()->getService()),
            'employes' => $employeRepository->findAll(), 
            'absencesService' => $ar->findByService($service), 
            'absencesEnAttente' => $ar->findByStatut('En attente'),
            'absencesEnAttenteService' => $ar->findByStatutManager('En attente', $service),
            'absences' => $ar->findAll(),
            'documentsRecus' => $dr->findByDestinataire($user), 
        ]);
    }
}
