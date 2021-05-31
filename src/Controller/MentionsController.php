<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MentionsController extends AbstractController
{
    #[Route('/mentions/cgu', name: 'mentions_cgu')]
    public function cgu(): Response
    {
        return $this->render('/mentions/cgu.html.twig');
    }

    #[Route('/mentions/confidentialite', name: 'mentions_confidentialite')]
    public function confidentialite(): Response
    {
        return $this->render('/mentions/confidentialite.html.twig');
    }

    #[Route('/mentions/cookies', name: 'mentions_cookies')]
    public function cookies(): Response
    {
        return $this->render('/mentions/cookies.html.twig');
    }
}
