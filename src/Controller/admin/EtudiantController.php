<?php

namespace App\Controller\admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/etudiant")
 * @IsGranted("ROLE_ADMIN")
 */
class EtudiantController extends AbstractController
{
    /**
     * @Route("/", name="admin_etudiant_index")
     */
    public function index(): Response
    {
        return $this->render('admin/etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
        ]);
    }
}
