<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @IsGranted("IS_AUTHENTICATED_FULLY")
     */
    public function index(): Response
    {
        if($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_etudiant_index');
        }
        else if($this->isGranted('ROLE_PROF')) {
            return $this->redirectToRoute('professeur_index');
        }
        else {
            return $this->redirectToRoute('etudiant_index');
        }
    }
}
