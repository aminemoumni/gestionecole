<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/etudiant")
 */
class EtudiantController extends AbstractController
{
    /**
    * @Route("/", name="etudiant_index")
    */
    public function index(): Response
    {
       return $this->render('etudiant/index.html.twig');
    }
    /**
    * @Route("/new", name="etudiant_new")
    */
    public function new(): Response
    {
        return $this->render('etudiant/new.html.twig');
    }
}
