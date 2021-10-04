<?php

namespace App\Controller\admin;

use Mpdf\Mpdf;
use App\Entity\Note;
use App\Entity\User;
use App\Entity\Frais;
use App\Entity\Classe;
use App\Entity\Epreuve;
use App\Entity\Matiere;
use App\Entity\Etudiant;
use App\Entity\Professeur;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Forms;

/**
 * @Route("/admin/classe")
 */
class ClasseController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager, SerializerInterface $serializer)
    {
        $this->em = $manager;
        $this->serializer = $serializer;
    }
    /**
     * @Route("/etudiant", name="admin_classe")
     */
    public function index(): Response
    {
       
        return $this->render('admin/classe/classeEtudiant.html.twig', [
            'controller_name' => 'ClasseController',
            'li' => 'classe'
        ]);
    }
    /**
     * @Route("/professeur", name="admin_classeP")
     */
    public function indexProfesseur(): Response
    {
       
        return $this->render('admin/classe/classeProfesseur.html.twig', [
            'controller_name' => 'ClasseController',
            'li' => 'classe'
        ]);
    }
  

    /**
     * @Route("/classeEtudiant", name="admin_classe_classeEtudiant")
     */
    public function classeEtudiant(Request $request): Response
    {
        
        $params = $request->query;
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and c.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }

        
        $columns = array(
            array('db' => 'e.id','dt' => 0),
            array( 'db' => 'e.nom', 'dt' => 1 ),
            array( 'db' => 'e.prenom',  'dt' => 2 ),
            array( 'db' => 'c.designation',  'dt' => 3 ),
           
        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
        FROM etudiant e
        inner join inscription i on i.etudiant_id = e.id
        inner join classe c on c.id = i.classe_id 
        
        $filtre";

        $totalRows .= $sql;
        $sqlRequest .= $sql;
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $totalRecords = count($stmt->fetchAll());

        $my_columns = DatatablesController::Pluck($columns, 'db');
        $where = DatatablesController::Search($request, $columns);
        if (isset($where) && $where != '') {
            $sqlRequest .= $where;
        }
        // dd($sqlRequest);
        $sqlRequest .= DatatablesController::Order($request, $columns);
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $data = array();
        
        foreach ($result as $key => $row) {
            
            $nestedData = array();
            $cd = $row['id'];
            $etudiant = $this->em->getRepository(Etudiant::class)->find($row['id']);
            if($etudiant->getClasse()) {
                $actions = "<div class='actions'>"
                . " <i class='bi bi-dash-circle annuleEtudiant' data-id='".$row['id']."'></i>"
                . " <i class='fa fa-print addFacture'  data-id='".$row['id']."'></i>"
                . "</div>";
            } else {
                $actions = "<div class='actions'>"
                . " <i  class='bi bi-plus-circle valideEtudiant' data-id='".$row['id']."'></i>"
                . "</div>";
            }

            foreach (array_values($row) as $key => $value) {
                $nestedData[] = $value;
            }
            $nestedData[] = $actions;
            $nestedData["DT_RowId"] = $cd;
            $nestedData["DT_RowClass"] = $cd;
            $data[] = $nestedData;
            
        }

        $json_data = array(
            "draw" => intval($params->get('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $data   // total data array
        );
        
        return new Response(json_encode($json_data));

        
         

        // return $this->render('admin/classe/classeEtudiant.html.twig', [
        //     'controller_name' => 'ClasseController',
        // ]);
    }
    /**
     * @Route("/classeProfesseur", name="admin_classe_classeProfesseur")
     */
    public function classeProfesseur(Request $request): Response
    {
        $params = $request->query;
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and p.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }

        
        $columns = array(
            array('db' => 'p.id','dt' => 0),
            array( 'db' => 'p.name', 'dt' => 1 ),
            array( 'db' => 'p.prenom',  'dt' => 2 ),
            array( 'db' => 'p.date_naiss',  'dt' => 3 ),
            array( 'db' => 'p.email',  'dt' => 4 ),
            array( 'db' => 'p.tel',  'dt' => 5 ),
           
        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
        FROM professeur p
        $filtre";

        $totalRows .= $sql;
        $sqlRequest .= $sql;
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $totalRecords = count($stmt->fetchAll());

        $my_columns = DatatablesController::Pluck($columns, 'db');
        $where = DatatablesController::Search($request, $columns);
        if (isset($where) && $where != '') {
            $sqlRequest .= $where;
        }
        // dd($sqlRequest);
        $sqlRequest .= DatatablesController::Order($request, $columns);
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $result = $stmt->fetchAll();

        $data = array();
        
        foreach ($result as $key => $row) {
            
            $nestedData = array();
            $cd = $row['id'];
            
                $actions = "<div class='actions'>"
                . " <i class='bi bi-eye seeClass'  data-id='".$row['id']."'></i>"
                . " <i class='bi bi-trash deleteProfesseur'  data-id='".$row['id']."'></i>"
                . "</div>";
            

            foreach (array_values($row) as $key => $value) {
                $nestedData[] = $value;
            }
            $nestedData[] = $actions;
            $nestedData["DT_RowId"] = $cd;
            $nestedData["DT_RowClass"] = $cd;
            $data[] = $nestedData;
            
        }

        $json_data = array(
            "draw" => intval($params->get('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $data   // total data array
        );
        
        return new Response(json_encode($json_data));

        
    }

    /**
     * @Route("/admin_classe_valider", name="admin_classe_valider")
     */
    public function classeValideEtudiant(Request $request): Response
    {
        $idEtudiant = $request->request->get('id');
        
        $etudiant = $this->em->getRepository(Etudiant::class)->find($idEtudiant);
        $inscription=$this->em->getRepository(Inscription::class)->findOneBy(['etudiant'=>$idEtudiant]);
        $etudiant->setClasse($inscription->getClasse());
        $this->em->flush();
        $message = "<p style='font-size:1.4rem'>L'etudiant <span class='bold'> " . $etudiant->getNom() ." </span><span class='bold'> " . $etudiant->getPrenom() . "</span> à bien été valider dans le classe <span class='bold'>" . $etudiant->getClasse()->getDesignation(). "</span></p>";
        return  new JsonResponse($message);
        
    }

    /**
     * @Route("/admin_classe_annuler", name="admin_classe_annuler")
     */
    public function classeAnnuleEtudiant(Request $request): Response
    {
        $idEtudiant = $request->request->get('id');
        
        $etudiant = $this->em->getRepository(Etudiant::class)->find($idEtudiant);
        $etudiant->setClasse( NULL);
        $this->em->flush();
        $message = "<p style='font-size:1.4rem'>L'etudiant <span class='bold'> " . $etudiant->getNom() ." </span><span class='bold'> " . $etudiant->getPrenom() . "</span> à bien été supprimer dans la classe </p>";
        return  new JsonResponse($message);
        
    }

    /**
     * @Route("/admin_get_professeur", name="admin_get_professeur")
     */
    public function adminGetProfesseur(Request $request): Response
    {
        $idProfesseur = $request->request->get('id');
        $professeur = $this->em->getRepository(Professeur::class)->find($idProfesseur);
        $sqlRequest = "Select * from classe where classe.id not in (select classe_id from professeur_classe where professeur_id = $idProfesseur)";
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $classes = $stmt->fetchAll();
        // dd($classes);
        $html = $this->render('admin/classe/inc/popUp.html.twig', [
            'professeur' => $professeur,
            'classes' => $classes
        ]);
        // dd($html->getContent());
        return new JsonResponse($html->getContent());

    }
    /**
     * @Route("/admin_set_class", name="admin_set_class")
     */
    public function adminSetClass(Request $request): Response
    {
        $idClasse = $request->request->get('idClasse');
        //dd($idClasse);
        $idProfesseur = $request->request->get('idProfesseur');
        //dd($idProfesseur);
        $professeur = $this->em->getRepository(Professeur::class)->find($idProfesseur);
        $classe=$this->em->getRepository(Classe::class)->find($idClasse);
        $professeur->addClasse($classe);
        $this->em->flush();
        $sqlRequest = "Select * from classe where classe.id not in (select classe_id from professeur_classe where professeur_id = $idProfesseur)";
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $classes = $stmt->fetchAll();
      
        $html = $this->render('admin/classe/inc/popUp.html.twig', [
            'professeur' => $professeur,
            'classes' => $classes
        ]);
        
        return new JsonResponse($html->getContent());

    }
    /**
     * @Route("/admin_classProfesseur_annuler", name="admin_classProfesseur_annuler")
     */
    public function adminClasseAnnuler(Request $request): Response
    {
        
        $idClasse = $request->request->get('idClasse');
        $idProfesseur = $request->request->get('idProfesseur');
        $professeur = $this->em->getRepository(Professeur::class)->find($idProfesseur);
        $classe=$this->em->getRepository(Classe::class)->find($idClasse);
        $professeur->removeClasse($classe);
        $this->em->flush();

        $sqlRequest = "Select * from classe where classe.id not in (select classe_id from professeur_classe where professeur_id = $idProfesseur)";
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $classes = $stmt->fetchAll();
      
        $html = $this->render('admin/classe/inc/popUp.html.twig', [
            'professeur' => $professeur,
            'classes' => $classes
        ]);
        
        return new JsonResponse($html->getContent());

    }


     /**
     * @Route("/admin_delete_professeur", name="admin_delete_professeur")
     */
    public function adminDeleteProfesseur(Request $request): Response
    {
        
        $idProfesseur = $request->request->get('id');
        //dd($idProfesseur);
        $user = $this->em->getRepository(User::class)->findOneBy(['professeur'=>$idProfesseur]);
        $professeur = $this->em->getRepository(Professeur::class)->find($idProfesseur);
        //dd($professeur);
        $message = "<p style='font-size:1.4rem'>Le proffesseur <span class='bold'> " . $professeur->getName() ." </span><span class='bold'> " . $professeur->getPrenom() ."</span> a été bien supprimé</p>";
        $this->em->remove($user);
        $this->em->remove($professeur);
        $this->em->flush();
        
        return new JsonResponse($message);

    }
     /**
     * @Route("/adminAddProfesseur", name="adminAddProfesseur")
     */
    public function adminAddProfesseur(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $data = (object)$request->request->get('data');
        $userExist=$this->em->getRepository(User::class)->findBy(['email'=> $data->email]);
        if($userExist)
        {
            return new JsonResponse("this user is already exists");
        }
      
        $professeur = new Professeur();
        $professeur->setName($data->nom);
        $professeur->setPrenom($data->prenom);
        $professeur->setEmail($data->email);
        $professeur->setTel($data->tel);
        $professeur->setDateNaiss(new \DateTime($data->dateNaissance));
        
        $this->em->persist($professeur);
        
            $user = new User();
            $user->setEmail($data->email);
            $user->setProfesseur($professeur);
            $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                "0123456789"
            )
            );
            $user->setRoles(['ROLE_PROF']);
            $this->em->persist($user);
        $this->em->flush();
        return new JsonResponse("success");
        
    }


    // public function serializeContent($myObject) 
    // {
    //     $encoders = [
    //         new JsonEncoder()
    //     ];
    //     $normalizer = new ObjectNormalizer();
    //     $normalizer->setCircularReferenceHandler(function ($object) {
    //         return $object->getId();
    //     });
    //     $normalizers = [$normalizer];
    //     $serializer  = new Serializer($normalizers, $encoders);

    //     $data = $serializer->serialize($myObject, 'json');
    //     return $data;
    // }


    /**
     * @Route("/epreuve", name="admin_classeEpreuve")
     */
    public function indexEpreuve(): Response
    {
        $classes=$this->em->getRepository(Classe::class)->findAll();
        return $this->render('admin/classe/epreuve.html.twig', [
            'controller_name' => 'ClasseController',
            'li' => 'classe',
            'classes'=>$classes
        ]);
    }
    /**
     * @Route("/listEpreuve", name="admin_listEpreuve")
     */
    public function listEpreuve(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $params = $request->query;
        $session = $request->getSession();
        // dd($session->get('classe_id'));
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and ep.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }
        $filtre .="and ep.valide =0";
        
        $columns = array(
            array( 'db' => 'ep.id', 'dt' => 0 ),
            array( 
                'db' => 'ep.date', 
                'dt' => 1,
                'formatter' => function( $d, $row ) {
                    return date('d-m-Y', strtotime($d));
                }
            ),
            array( 'db' => 'ep.desingation',  'dt' => 2 ),
            array( 'db' => 'cl.designation as classe', 'dt' => 3 ),
            array( 'db' => 'm.designation as matier',  'dt' => 4 ),
            array( 
                'db' => 'ep.heure_debut', 
                'dt' => 5,
                'formatter' => function( $d, $row ) {
                    return date('H:i:s', strtotime($d));
                }
            ),
            array( 
                'db' => 'ep.heure_fin', 
                'dt' => 6,
                'formatter' => function( $d, $row ) {
                    return date('H:i:s', strtotime($d));
                }
            ),
            

        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
                FROM epreuve ep 
                inner join classe cl on cl.id=ep.classe_id
                inner join matiere m on m.id=ep.matiere_id
                $filtre"
        ;
        // dd($sql);
        $totalRows .= $sql;
        $sqlRequest .= $sql;
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $totalRecords = count($stmt->fetchAll());
        $my_columns = DatatablesController::Pluck($columns, 'db');
        
        // search 
        $where = DatatablesController::Search($request, $columns);
        if (isset($where) && $where != '') {
            $sqlRequest .= $where;
        }
        // dd($sqlRequest);
        $sqlRequest .= DatatablesController::Order($request, $columns);
        $stmt = $this->getDoctrine()->getManager()->getConnection()->prepare($sqlRequest);
        $stmt->execute();
        $result = $stmt->fetchAll();
        // dd($result);

        $data = array();
        
        foreach ($result as $key => $row) {
            // dd($row['id']);
            $nestedData = array();
            $cd = $row['id'];
            $actions = "<div class='actions'>"
            . " <i class='bi bi-check valideEpreuve' data-id='".$row['id']."'></i>"
            . " <i class='bi bi-eye valideNote'  data-id='".$row['id']."'></i>"
            . "</div>";
            
            
            foreach (array_values($row) as $key => $value) {
                $nestedData[] = $value;
            }
            $nestedData[] = $actions;
            $nestedData["DT_RowId"] = $cd;
            $nestedData["DT_RowClass"] = $cd;
            $data[] = $nestedData;
            
        }

        $json_data = array(
            "draw" => intval($params->get('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($totalRecords),
            "data" => $data   // total data array
        );
        // dd($data);
        return new Response(json_encode($json_data));
    }
    /**
     * @Route("/validEpreuve", name="admin_valid_epreuve")
     */
    public function validEpreuve(Request $request): Response
    {
         $idEpreuve = $request->request->get('id');
       
        $epreuve=$this->em->getRepository(Epreuve::class)->find($idEpreuve);
       
        $epreuve->setValide(1);

        $this->em->persist($epreuve);
        $this->em->flush();
        return new JsonResponse("success");
     
    }
    /**
     * @Route("/validNote", name="admin_valid_note")
     */
    public function ValidNote(Request $request): Response
    {
        $idEpreuve=$request->request->get('id');
    
        $epreuve=$this->em->getRepository(Epreuve::class)->find($idEpreuve);
        $notes=$this->em->getRepository(Note::class)->findby(array('epreuve' => $epreuve));
        $html = $this->render('admin/classe/inc/validNote.html.twig', [
            'epreuve' => $epreuve,
            'notes' => $notes
        ]);
        
        return new JsonResponse($html->getContent());
    }
     /**
     * @Route("/frais", name="admin_classeFrais")
     */
    public function indexFrais(): Response
    {
        $frais=$this->em->getRepository(Frais::class)->findAll();
        $classes=$this->em->getRepository(Classe::class)->findAll();
        return $this->render('admin/classe/frais.html.twig', [
            'controller_name' => 'ClasseController',
            'li' => 'classe',
            'frais'=>$frais,
            'classes'=>$classes
        ]);
    }
    /**
     * @Route("/addFrais", name="add_frais")
     */
    public function addFrais(Request $request): Response
    {
        $data = (object)$request->request->get('data');
        $classe=$this->em->getRepository(Classe::class)->find($data->classe);
        $frais = new Frais();
        $frais->setClasse($classe);
        $frais->setDesignation($data->designation);
        $frais->setPrix($data->prix);
        
        $this->em->persist($frais);
        
        $this->em->flush();
        
        return new JsonResponse("success");
       
    }
    /**
     * @Route("/addFacture", name="addFacture")
     */
    public function adminAddFacture(Request $request): Response
    {
        $idEtudiant = $request->request->get('id');
        $etudiant = $this->em->getRepository(Etudiant::class)->find($idEtudiant);
        $classe=$this->em->getRepository(Classe::class)->find($etudiant->getClasse());
        //dd($classe);
        $frais=$this->em->getRepository(Frais::class)->findby(array('classe' => $classe));
        
        $html = $this->render('admin/classe/inc/printFacture.html.twig', [
            'etudiant' => $etudiant,
            'frais' => $frais
        ]);
        // dd($html->getContent());
        $session = $request->getSession();
        $session->set('idE',$idEtudiant);
       
        //$session->set('facture', '');
        
    
        return new JsonResponse($html->getContent());

    }
       /**
     * @Route("/addFraisEtudiant", name="addFraisEtudiant")
     */
    public function adminAddFraisEtudiant(Request $request): Response
    {
        $idFrais = $request->request->get('id');
        $frais=$this->em->getRepository(Frais::class)->find($idFrais);
        $index=$request->request->get('index');
        $session = $request->getSession();
        
        
        
        
        $tables['0'] = $frais->getDesignation();
        $tables['1'] = $frais->getPrix();
        $session->set('tables', $tables);

        $facture[$index]=$session->get('tables');     
        $session->set('facture', $facture);
        $html = "<tr> <td> ".$index." </td> "
               ." <td> ".$frais->getDesignation()." </td> "
               ." <td> ".$frais->getPrix()." </td> " 
               ." <td> <i class='bi bi-trash deleteFraisEtudiant'  data-id='".$index."'></i> </td> </tr>";
        //dd($html);
        return new JsonResponse($html);

    }
      /**
    * @Route("/facture", name="facture")
    */
    public function facture(Request $request)
    {
        
        $session = $request->getSession();
        $etudiant= $this->em->getRepository(Etudiant::class)->find($session->get('idE'));
         $session->get('tables');
         $facture=$session->get('tables');
        $mpdf= new Mpdf();
        $html = $this->renderView('admin/classe/inc/facture.html.twig', [
            'title' => "Facture" ,
            'etudiant'=>$etudiant,
            'facture'=>$facture
            
        ]);
       
        $mpdf->WriteHtml($html);
        $mpdf->Output('facture.pdf','D');
        
        return new Response('The PDF file has been succesfully generated !');
    }
   
    

    /**
     * @Route("/updateFrais", name="admin_update_frais")
     */
    public function updateFrais(Request $request): Response
    {
        $idFrais=$request->request->get('id');
        $prix=$request->request->get('prix');
        $frais=$this->em->getRepository(Frais::class)->find($idFrais);
        $frais->setPrix($prix);
        $this->em->persist($frais);
        $this->em->flush();

        return new JsonResponse("success");
    }
}
