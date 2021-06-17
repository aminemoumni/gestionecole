<?php

namespace App\Controller\admin;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Professeur;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/classe")
 */
class ClasseController extends AbstractController
{
    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
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
        left join professeur_classe pc on p.id = pc.professeur_id
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
            //$professeur = $this->em->getRepository(Professeur::class)->find($row['id']);
            // if($professeur->getClasse()) {
                $actions = "<div class='actions'>"
                . " <i class='bi bi-eye seeClass'  data-id='".$row['id']."'></i>"
                . "</div>";
            // } 

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
        $classes = $this->em->getRepository(Classe::class)->findAll();
        $professeur = $this->em->getRepository(Professeur::class)->find($idProfesseur);
        
        // $message = "<p style='font-size:1.4rem'>Le professeur <span class='bold'> " . $etudiant->getNom() ." </span><span class='bold'> " . $etudiant->getPrenom() . "</span> à bien été supprimer dans la classe </p>";
        $data = [
            'classes' => $classes,
            'professeur' => $professeur
        ];
        return  new JsonResponse($data);
        
    }
}
