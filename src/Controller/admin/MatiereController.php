<?php

namespace App\Controller\admin;

use App\Entity\Matiere;
use App\Entity\Professeur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
     * @Route("/admin/matiere")
     */
class MatiereController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }
    /**
     * @Route("/", name="admin_matiere")
     */
    public function index(): Response
    {
        $matieres = $this->em->getRepository(Matiere::class)->findAll();
        return $this->render('admin/matiere/index.html.twig', [
            'matieres' => $matieres,
            'li'=>'matiere'
        ]);
    }
     /**
     * @Route("/addMatiere", name="addMatiere")
     */
    public function addMatiere(Request $request): Response
    {
        $data = (object)$request->request->get('data');
        
        $matiere = new Matiere();
        $matiere->setDesignation($data->designation);
        
        $this->em->persist($matiere);

        $this->em->flush();

        $sqlRequest = "Select * from matiere";
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $matieres = $stmt->fetchAll();
      
        $html = $this->render('admin/matiere/listMatiere.html.twig', [
            'matieres' => $matieres,
            'li'=>'matiere'
        ]);
        return new JsonResponse($html->getContent());
        
    }
    
    /**
     * @Route("/admin_delete_matiere", name="admin_delete_matiere")
     */
    public function adminDeleteMatiere(Request $request): Response
    {
        
        $idMatiere = $request->request->get('id');
        $matiere = $this->em->getRepository(Matiere::class)->find($idMatiere);
        //dd($professeur);
        $message = "<p style='font-size:1.4rem'>La matiere<span class='bold'> " . $matiere->getDesignation() ."</span> a été bien supprimé</p>";
        $this->em->remove($matiere);
        $this->em->flush();
        
        return new JsonResponse($message);

    }
}
