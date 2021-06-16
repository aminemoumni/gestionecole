<?php

namespace App\Controller;

use Mpdf\Mpdf;
use App\Entity\Classe;
use App\Entity\Inscription;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/etudiant")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class EtudiantController extends AbstractController
{
    /**
    * @Route("/", name="etudiant_index")
    */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $inscriptions=$entityManager->getRepository(Inscription::class)->findBy(['user'=>$user]);

        
       return $this->render('etudiant/index.html.twig',['inscriptions'=>$inscriptions,]);
    }
    /**
    * @Route("/new", name="etudiant_new")
    */
    public function new(): Response
    { 
        $entityManager = $this->getDoctrine()->getManager();
        $classes=$entityManager->getRepository(Classe::class)->findAll();
        return $this->render('etudiant/new.html.twig',['classes'=>$classes,]);
    }

      /**
    * @Route("/store", name="etudiant_store")
    */
    public function store(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $classe = $entityManager->getRepository(Classe::class)->find($request->request->get('classe'));
        // dd($this->getUser());
        $inscription=new Inscription();
        //dd($request->request->get('classe'));
        $inscription->setNom($request->request->get('nom'));
        $inscription->setPrenom($request->request->get('prenom'));
        $inscription->setDateNaiss(new \DateTime($request->request->get('dateNaissance')));
        $inscription->setVille($request->request->get('ville'));
        $inscription->setNomPere($request->request->get('nomP'));
        $inscription->setPrenomPere($request->request->get('prenomP'));
        $inscription->setEmailPere($request->request->get('emailP'));
        $inscription->setFonctionPere($request->request->get('fonctionP'));
        $inscription->setTelPere($request->request->get('telephoneP'));
        $inscription->setNomMere($request->request->get('nomM'));
        $inscription->setPrenomMere($request->request->get('prenomM'));
        $inscription->setEmailMere($request->request->get('emailM'));
        $inscription->setFonctionMere($request->request->get('fonctionM'));
        $inscription->setTelMere($request->request->get('telephoneM'));
        $inscription->setClasse($classe);
        $inscription->setUser($this->getUser());
        // dd($inscription);
        $entityManager->persist($inscription);
        $entityManager->flush();
        // die;
        return $this->redirectToRoute('etudiant_new');

    }
     /**
    * @Route("/attestation/{inscription}", name="etudiant_attestation")
    */
    public function attestation(Request $request,Inscription $inscription)
    {
        $em = $this->getDoctrine()->getManager();
        $mpdf= new Mpdf();
        $html = $this->renderView('etudiant/attestation.html.twig', [
            'title' => "Attestation scolaire" ,'inscription' =>$inscription
        ]);
       
        $mpdf->WriteHtml($html);
        $mpdf->Output('Myfile.pdf','D');
        return new Response('The PDF file has been succesfully generated !');
    }
}
