<?php

namespace App\Controller\admin;

use App\Entity\Classe;
use App\Entity\Etudiant;
use App\Entity\Inscription;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(EntityManagerInterface $manager)
    {
        $this->em = $manager;
    }
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
            $inscription_valider = $this->generateUrl('admin_inscription_valider', ['inscription' => $row['id']]);
            $inscription_annuler = $this->generateUrl('admin_inscription_annuler', ['inscription' => $row['id']]);
            $inscriptionEtudiant = $em->getRepository(Inscription::class)->find($row['id']);
            if($inscriptionEtudiant->getEtudiant() and $inscriptionEtudiant->getValide() == 1) {
                $actions = "<div class='actions'>"
                . " <a href='$inscription_annuler' class='btn btn-danger'>Annuler l'année</a>"
                . "</div>";
            } else {
                $actions = "<div class='actions'>"
                . " <a href='$inscription_valider' class='btn btn-success'>Valider</a>"
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
        // die;
        return new Response(json_encode($json_data));
        
    }
    /**
     * @Route("/admin_inscription_valider/{inscription}", name="admin_inscription_valider")
     */
    public function adminInscriptionValider(Request $request,Inscription $inscription): Response
    {      
        $etudiant=new Etudiant();
        $classe=$inscription->getClasse()->getId();
        $etudiant->setNom($inscription->getNom());
        $etudiant->setPrenom($inscription->getPrenom());
        $etudiant->setDateNaiss($inscription->getDateNaiss());
        $etudiant->setVille($inscription->getVille());
        // $etudiant->setClasse($inscription->getClasse());
        $etudiant->setUser($this->getUser());
        $etudiant->setCodeAdmission('1');
        // dd($inscription->getClasse());
        //dd($etudiant);
        
        $this->em->persist($etudiant);
        $inscription->setValide(1);
        $inscription->setEtudiant($etudiant);
        
        $this->em->flush();
        
        
        
        return $this->redirectToRoute('admin_etudiant_index');
        // return new Response('admin_inscription_valider');
    }
    /**
     * @Route("/admin_inscription_annuler/{inscription}", name="admin_inscription_annuler")
     */
    public function adminInscriptionAnnuler(Inscription $inscription): Response
    {
        // $inscription = $em->getRepository(Inscription::class)->find($id);
        
        $inscription->setValide(0);
        $this->em->persist($inscription);
        $this->em->flush();
        return $this->redirectToRoute('admin_etudiant_index');
        // return new Response('admin_inscription_annuler');
    }
}
