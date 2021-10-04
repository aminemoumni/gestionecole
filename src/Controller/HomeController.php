<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
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
    /**
     * @Route("/profile", name="profile")
     */
    public function profil(Request $request): Response
    {
        
      
        
        return $this->render('home/inc/popUp.html.twig', [
            
        ]);
    }


    /**
     * @Route("/updatePassword", name="updatePassword")
     */
    public function updatePassword(Request $request): Response
    {
        $iduser = $request->request->get('idUser');
        $oldPass = $request->request->get('oldPass');
        $newPass = $request->request->get('newPass');
        
        
        
        $user = $this->em->getRepository(User::class)->find($iduser);
        if($user->getPassword()==$oldPass)
        {
            $user->setPassword($newPass);
            $this->em->flush();
            return new JsonResponse('success');
        }
        else{
            return new JsonResponse('password incorrecte');
        }
        
      
        
        
        
      
        
    }
    
}
