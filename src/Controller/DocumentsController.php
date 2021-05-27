<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil/documents')]
class DocumentsController extends AbstractController
{
    #[Route('/', name: 'documents_profil', methods: ['GET'])]
    public function index(DocumentsRepository $documentsRepository): Response
    {
        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findByEmploye($this->getUser()),
        ]);
    }

    // Créer une route pour les RH, qu'elles puissent afficher tous les documents

    #[Route('/new', name: 'documents_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $destination = $this->getParameter("dossier_documents");

                // name fait référence au fichier envoyé par l'employé
            if($doc = $form->get("name")->getData()){

                $nomDoc = pathinfo($doc->getClientOriginalName(), PATHINFO_FILENAME);
                $nouveauNom = str_replace(" ", "_", $nomDoc);
                $nouveauNom .= "-" . uniqid() . "." . $doc->guessExtension();
                $doc->move($destination, $nouveauNom);

                $document->setName($nouveauNom);
            }
            $document->setStatut("envoye");
            $document->setEmploye($this->getUser());


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('documents_profil');
        }

        return $this->render('documents/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'documents_show', methods: ['GET'])]
    public function show(Documents $document): Response
    {
        return $this->render('documents/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'documents_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Documents $document): Response
    {
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('documents_index');
        }

        return $this->render('documents/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'documents_delete', methods: ['POST'])]
    public function delete(Request $request, Documents $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('documents_index');
    }
}
