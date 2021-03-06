<?php

namespace App\Controller;

use App\Entity\Documents;
use App\Form\DocumentsType;
use App\Repository\DocumentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil/documents')]
class DocumentsController extends AbstractController
{
    #[Route('/', name: 'documents_index')]
    public function index(DocumentsRepository $documentsRepository, Request $request): Response
    {
        
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document,[
            'action' => $this->generateUrl('documents_index'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_documents");

            // name fait référence au fichier envoyé par l'employé
            if ($doc = $form->get("name")->getData()) {

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

            return $this->redirectToRoute('documents_index');
        }

        return $this->render('documents/index.html.twig', [
            'documents' => $documentsRepository->findByEmploye($this->getUser()),
            'documents' => $documentsRepository->findAll(),
            'docRecu' => $documentsRepository->findByDestinataire($this->getUser()),
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'documents_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $document = new Documents();
        $form = $this->createForm(DocumentsType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $destination = $this->getParameter("dossier_documents");

            // name fait référence au fichier envoyé par l'employé
            if ($doc = $form->get("name")->getData()) {

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

            return $this->redirectToRoute('documents_index');
        }

        return $this->render('documents/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/documentsenvoyes', name: 'documents_envoyes', methods: ['GET', 'POST'])]
    public function documentsRecus(DocumentsRepository $dr) : Response {

        return $this->render('documents/documentsEnvoyes.html.twig', [
            'documents' => $dr->findByEmploye($this->getUser())
        ]);
    }

    #[Route('/documentsrecus', name: 'documents_recus', methods: ['GET', 'POST'])]
    public function documentsEnvoyes(DocumentsRepository $dr) : Response {

        return $this->render('documents/documentsRecus.html.twig', [
            'docRecu' => $dr->findByDestinataire($this->getUser())
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
        if ($this->isCsrfTokenValid('delete' . $document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('documents_index');
    }

    /**
     * @Route("/download/{name}", name="download")
     */
    public function download($name)
    {

        // On récupère le fichier
        $file = $this->getParameter("dossier_documents") . "/" . $name;
        // On configure la réponse
        $response = new BinaryFileResponse($file);
        //On configure le fait que le navigateur va directement proposer le téléchargement du document et non l'afficher
        $response->headers->set('Content-Type', 'text/plain');
        $response->setContentDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $name
        );

        return $response;
    }

}
