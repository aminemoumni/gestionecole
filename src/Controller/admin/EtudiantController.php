<?php

namespace App\Controller\admin;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Inscription;
use Symfony\Component\HttpFoundation\Request;
use App\Controller\admin\DatatablesController;
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
        $em =$this->getDoctrine()->getManager();
        $classes = $em->getRepository(Classe::class)->findAll();
        $inscriptions = $em->getRepository(Inscription::class)->findAll();
        return $this->render('admin/etudiant/index.html.twig', [
            'inscriptions' => $inscriptions,
            'classes' => $classes
        ]);
    }
    /**
     * @Route("/list", name="admin_list_inscription")
     */
    public function list(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $params = $request->query;
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and c.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }

        
        $columns = array(
            array('db' => 'i.id','dt' => 0),
            array( 'db' => 'i.nom', 'dt' => 1 ),
            array( 'db' => 'i.prenom',  'dt' => 2 ),
            array( 'db' => 'i.ville',   'dt' => 3 ),
            array( 
                'db' => 'i.date_naiss', 
                'dt' => 4,
                'formatter' => function( $d, $row ) {
                    return date( 'jS M y', strtotime($d));
                }
            ),
            
            array('db' => 'c.designation','dt' => 5),
        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
                FROM inscription i
                inner join classe c on c.id = i.classe_id
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

        foreach ($result as $key => $row) {
            // dd($row['id']);
            $nestedData = array();
            $cd = $row['id'];
            // $question_edit = $this->generateUrl('question_edit', ['id' => $row['id']]);
            $inscription_valider = $this->generateUrl('admin_inscription_valider', ['id' => $row['id']]);
            $inscription_annuler = $this->generateUrl('admin_inscription_annuler', ['id' => $row['id']]);
            $etudiant = $em->getRepository(Inscription::class)->findBy([
                'etudiant' => $row['id']
            ]);
            if($etudiant) {
                $actions = "<div class='actions'>"
                . " <a href='$inscription_annuler' class='btn btn-danger'>Anuller l'année</a>"
                . "</div>";
            } else {
                $actions = "<div class='actions'>"
                . " <a href='$inscription_valider' class='btn btn-warning'>Valider</a>"
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
         
    }
    /**
     * @Route("/admin_inscription_valider", name="admin_inscription_valider")
     */
    public function adminInscriptionValider(): Response
    {
        return new Response('admin_inscription_valider');
    }
    /**
     * @Route("/admin_inscription_annuler", name="admin_inscription_annuler")
     */
    public function adminInscriptionAnnuler(): Response
    {
        return new Response('admin_inscription_annuler');
    }
}
