<?php

namespace App\Controller\professeur;

use App\Entity\Note;
use App\Entity\Cours;
use App\Entity\Classe;
use App\Entity\Epreuve;
use App\Entity\Matiere;
use App\Entity\Etudiant;
use App\Entity\Professeur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
     * @Route("/admin/professeur")
     */
class ClasseController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }
    /**
     * @Route("/", name="professeur_index")
     */
    public function index(): Response
    {
        $epreuve=$this->em->getRepository(Epreuve::class)->findAll();
        return $this->render('professeur/classe/index.html.twig', [
            'li' => 'classe',
            'epreuves'=>$epreuve 
        ]);
       
    }
    /**
     * @Route("/classe/{classe}", name="professeur_show_classe")
     */
    public function ShowClasse(Request $request, Classe $classe): Response
    {
        
        $session = $request->getSession();
        $session->set('classe_id', $classe->getId());
        $epreuve=$this->em->getRepository(Epreuve::class)->findby(array('classe' =>$classe));
        return $this->render('professeur/classe/index.html.twig', [
            'li' => 'classe','epreuves'=>$epreuve ,
        ]);
     
    }
    /**
     * @Route("/listEtudiant", name="professeur_list_etudiant")
     */
    public function listEtudiant(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $params = $request->query;
        $session = $request->getSession();
        // dd($session->get('classe_id'));
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and e.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }
        $filtre .="and e.classe_id = ".$session->get('classe_id')."";
        
        $columns = array(
            array( 'db' => 'e.id', 'dt' => 0 ),
            array( 'db' => 'e.nom', 'dt' => 1 ),
            array( 'db' => 'e.prenom',  'dt' => 2 ),

        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
                FROM etudiant e 
                $filtre"
        ;

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

        $data = array();
        $i = 1;
        foreach ($result as $key => $row) {
            // dd($row['id']);
            $nestedData = array();
            $cd = $row['id'];
            $actions = "<div class='actions'>"
            . " <i class='bi bi-plus addNote' data-bs-toggle='modal' data-bs-target='#addNote'  data-id='".$row['id']."'></i>"
            . " <i class='bi bi-eye seeNote'  data-id='".$row['id']."'></i>"
            . "</div>";
            
            
            foreach (array_values($row) as $key => $value) {
                if($key == 0) {
                    $nestedData[] = $i;
                    $i++;
                } else {
                    $nestedData[] = $value;
                }
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
        // die;
        return new Response(json_encode($json_data));
    }
    /**
     * @Route("/cours", name="professeur_show_cours")
     */
    public function ShowCours(): Response
    {

        
        return $this->render('professeur/classe/cours.html.twig', [
            'li' => 'classe',
        ]);
     
    }
    /**
     * @Route("/epreuve", name="professeur_show_epreuve")
     */
    public function ShowEpreuve(Request $request): Response
    {
        
        return $this->render('professeur/classe/epreuve.html.twig', [
            'li' => 'classe',
        ]);
     
    }
    /**
     * @Route("/listCours", name="professeur_list_Cours")
     */
    public function listCours(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $params = $request->query;
        $session = $request->getSession();
        // dd($session->get('classe_id'));
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and c.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }
        $filtre .="and c.classe_id = ".$session->get('classe_id')."";
        
        $columns = array(
            array( 'db' => 'c.id', 'dt' => 0 ),
            array( 'db' => 'cl.designation as classe', 'dt' => 1 ),
            array( 'db' => 'm.designation as matier',  'dt' => 2 ),
            array( 
                'db' => 'c.date_cours', 
                'dt' => 3,
                'formatter' => function( $d, $row ) {
                    return date('d-m-Y', strtotime($d));
                }
            ),
            array( 
                'db' => 'c.heure_d', 
                'dt' => 4,
                'formatter' => function( $d, $row ) {
                    return date('H:i:s', strtotime($d));
                }
            ),
            array( 
                'db' => 'c.heure_f', 
                'dt' => 5,
                'formatter' => function( $d, $row ) {
                    return date('H:i:s', strtotime($d));
                }
            ),
            array( 'db' => 'c.designation',  'dt' => 6 ),
            

        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
                FROM cours c 
                inner join classe cl on cl.id=c.classe_id
                inner join matiere m on m.id=c.matiere_id
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
            . " <i class='bi bi-trash deleteCours' data-id='".$row['id']."'></i>"
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
     * @Route("/listEpreuve", name="professeur_listEpreuve")
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
        $filtre .="and ep.classe_id = ".$session->get('classe_id')."";
        
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
            . " <i class='bi bi-trash deleteEpreuve' data-id='".$row['id']."'></i>"
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
     * @Route("/addCourse", name="professeur_add_course")
     */
    public function AddCourse(Request $request): Response
    {
        $data = (object)$request->request->get('data');
        $session = $request->getSession();
        $classe=$this->em->getRepository(Classe::class)->find($session->get('classe_id'));
        $matiere=$this->em->getRepository(Matiere::class)->find($data->matiere);

        $cours = new Cours();
        $cours->setClasse($classe);
        $cours->setMatiere($matiere);
        $cours->setDateCours(new \DateTime($data->dateCourse));
        $cours->setHeureD(new \DateTime($data->heureD));
        $cours->setHeureF(new \DateTime($data->heureF));
        $cours->setDesignation($data->designation);

        $this->em->persist($cours);
        $this->em->flush();
        return new JsonResponse("success");
        
     
    }
    /**
     * @Route("/addEpreuve", name="professeur_add_epreuve")
     */
    public function AddEpreuve(Request $request): Response
    {
         $data = (object)$request->request->get('data');
        $session = $request->getSession();
        $classe=$this->em->getRepository(Classe::class)->find($session->get('classe_id'));
        $matiere=$this->em->getRepository(Matiere::class)->find($data->matiere);
        
        $epreuve = new Epreuve();
        $epreuve->setClasse($classe);
        $epreuve->setMatiere($matiere);
        $epreuve->setDate(new \DateTime($data->dateEpreuve));
        $epreuve->setHeureDebut(new \DateTime($data->heureD));
        $epreuve->setHeureFin(new \DateTime($data->heureF));
        $epreuve->setDesingation($data->designation);
        $epreuve->setValide(0);
        $this->em->persist($epreuve);
        $this->em->flush();
        return new JsonResponse("success");
     
    }
    /**
     * @Route("/addNote", name="professeur_add_note")
     */
    public function AddNote(Request $request): Response
    {
         $data = (object)$request->request->get('data');
         $idEtudiant=$request->request->get('id');
        $epreuve=$this->em->getRepository(Epreuve::class)->find($data->epreuve);
        $etudiant=$this->em->getRepository(Etudiant::class)->find($idEtudiant);
        //dd($epreuve);
        $note = new Note();
        $note->setEpreuve($epreuve);
        $note->setEtudiant($etudiant);
        $note->setNote($data->note);
        
        $this->em->persist($note);
        $this->em->flush();
        return new JsonResponse("success");
     
    }
    /**
     * @Route("/seeNote", name="professeur_see_note")
     */
    public function SeeNote(Request $request): Response
    {
        $idEtudiant=$request->request->get('id');
        //dd($idEtudiant);
        $etudiant=$this->em->getRepository(Etudiant::class)->find($idEtudiant);
        $notes=$this->em->getRepository(Note::class)->findby(array('etudiant' => $idEtudiant));
        //dd($notes);
        $html = $this->render('professeur/classe/inc/seeNote.html.twig', [
            'notes' => $notes,
            'etudiant' => $etudiant
        ]);
        // dd($html->getContent());
        return new JsonResponse($html->getContent());
    }
     /**
     * @Route("/updateNote", name="professeur_update_note")
     */
    public function UpdateNote(Request $request): Response
    {
        $idNote=$request->request->get('id');
        $valueNote=$request->request->get('note');
        $note=$this->em->getRepository(Note::class)->find($idNote);
        $note->setNote($valueNote);
        $this->em->persist($note);
        $this->em->flush();

        return new JsonResponse("success");
    }

    /**
     * @Route("/deleteCours", name="deleteCours")
     */
    public function deleteCours(Request $request): Response
    {
        
        $idCours = $request->request->get('id');
        $cours = $this->em->getRepository(Cours::class)->find($idCours);
        
        $message = "<p style='font-size:1.4rem'>Le cours<span class='bold'> " . $cours->getDesignation() ."</span> a été bien supprimé</p>";
        $this->em->remove($cours);
        $this->em->flush();
        
        return new JsonResponse($message);

    }
    /**
     * @Route("/deleteEpreuve", name="deleteEpreuve")
     */
    public function deleteEpreuve(Request $request): Response
    {
        
        $idEpreuve = $request->request->get('id');
        $epreuve = $this->em->getRepository(Epreuve::class)->find($idEpreuve);
        
        $message = "<p style='font-size:1.4rem'>L'epreuve'<span class='bold'> " . $epreuve->getDesingation() ."</span> a été bien supprimé</p>";
        $this->em->remove($epreuve);
        $this->em->flush();
        
        return new JsonResponse($message);

    }
    
}
