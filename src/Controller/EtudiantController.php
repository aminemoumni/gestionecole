<?php

namespace App\Controller;

use Mpdf\Mpdf;
use App\Entity\Note;
use App\Entity\Cours;
use App\Entity\Classe;
use App\Entity\Epreuve;
use App\Entity\Matiere;
use App\Entity\Etudiant;
use App\Entity\Attestation;
use App\Entity\Inscription;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\professeur\DatatablesController;
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


       return $this->render('etudiant/index.html.twig',[
           'inscriptions'=>$inscriptions,
           'li' => 'etudiant'
        ]);
    }
    /**
    * @Route("/new", name="etudiant_new")
    */
    public function new(): Response
    { 
        $entityManager = $this->getDoctrine()->getManager();
        $classes=$entityManager->getRepository(Classe::class)->findAll();
        return $this->render('etudiant/new.html.twig',[
            'classes'=>$classes,
            'li' => 'etudiant'
        ]);
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
        $etudiant=$inscription->getEtudiant();
        //dd($etudiant);
        $attestation=new Attestation();
        $attestation->setEtudiant($etudiant);
        $attestation->setDateCreated(new \DateTime());
        
        $em->persist($attestation);
        $em->flush();

        $mpdf= new Mpdf();
        $html = $this->renderView('etudiant/attestation.html.twig', [
            'title' => "Attestation scolaire" ,'inscription' =>$inscription
        ]);
       
        $mpdf->WriteHtml($html);
        $mpdf->Output('Myfile.pdf','D');
        return new Response('The PDF file has been succesfully generated !');
    }


    /**
    * @Route("/planification/{index}", name="etudiant_planification")
    */
    public function planification($index,Request $request): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        //dd($this->getUser());
        $i=0;
        $etudiants=$em->getRepository(Etudiant::class)->findby(['user'=>$this->getUser()]);
        foreach($etudiants as $etudiants)
        {
                if($i==$index)
                {
                    
                    
                    $session->set('etudiant_id', $etudiants->getId());
                    
                }
                $i++;
            
            
        }
        
        //    dd($session->get('etudiant_id'));
        
        $etudiant=$em->getRepository(Etudiant::class)->find($session->get('etudiant_id'));
        $classe=$em->getRepository(Classe::class)->find($etudiant->getClasse());
        $cours=$em->getRepository(Cours::class)->findby(['classe'=>$classe]);
        $epreuve=$em->getRepository(Epreuve::class)->findby(['classe'=>$classe]);
        //dd($cours,$epreuves);
        $courses = [];

        foreach($cours as $rows){
            $courses[] = [
                "id" => $rows->getId(),
                "start" => $rows->getHeureD()->format('Y-m-d H:i:s'),
                "end" => $rows->getHeureF()->format('Y-m-d H:i:s'),
                "title" => $rows->getDesignation(),
                "backgroundColor" => 'blue',
                "borderColor" => 'black',
                "textColor" => 'black',
                "allDay" => false
            ];
        }

        $dataCourses = json_encode($courses);

        $epreuves = [];

        foreach($epreuve as $rows){
            $epreuves[] = [
                "id" => $rows->getId(),
                "start" => $rows->getHeureDebut()->format('Y-m-d H:i:s'),
                "end" => $rows->getHeureFin()->format('Y-m-d H:i:s'),
                "title" => $rows->getDesingation(),
                "backgroundColor" => 'red',
                "borderColor" => 'black',
                "textColor" => 'black',
                "allDay" => false
            ];
        }

        $dataEpreuves = json_encode($epreuves);
        //dd($dataCourses,$dataEpreuves);
        return $this->render('etudiant/planification.html.twig',
        
        [
            'li' => 'child',
            'i' => $index,
            "courses"=>compact('dataCourses'),
            "epreuves"=>compact('dataEpreuves')
        ]);
    }

    /**
    * @Route("/note/{index}", name="etudiant_note")
    */
    public function note($index,Request $request): Response
    { 
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $i=0;
        $etudiants=$em->getRepository(Etudiant::class)->findby(['user'=>$this->getUser()]);
        foreach($etudiants as $etudiants)
        {
                if($i==$index)
                {
                    
                    //dd($i,$index);
                    $session->set('etudiant_id', $etudiants->getId());
                    
                }
                $i++;
            
            
        }
        //dd($session->get('etudiant_id'));
        //$session = $request->getSession();
        //$session->set('etudiant_id', $index->getId());
        return $this->render('etudiant/note.html.twig',[
            'li' => 'child',
            'i' => $index,
            'etudiant_id'=>$session->get('etudiant_id')

        ]);
    }
    /**
    * @Route("/listNote", name="etudiant_listNote")
    */
    public function listNote(Request $request): Response
    {  $em = $this->getDoctrine()->getManager();
        $params = $request->query;
        $session = $request->getSession();
        //dd($session->get('etudiant_id'));
        // dd($params);
        $where = $totalRows = $sqlRequest = "";
        $filtre = "where 1 = 1 ";
        if (!empty($params->get('columns')[0]['search']['value'])) {
            $filtre .= " and e.id = '" . $params->get('columns')[0]['search']['value'] . "' ";
        }
        $filtre .="and n.etudiant_id  = ".$session->get('etudiant_id')."";
        $filtre .=" and e.valide  = 1";

        $columns = array(
            array( 'db' => 'e.id', 'dt' => 0 ),
            array( 'db' => 'e.desingation', 'dt' => 1 ),
            array( 'db' => 'm.designation', 'dt' => 2 ),
            array( 'db' => 'n.note',  'dt' => 3 ),

        );

        $sql = "SELECT " . implode(", ", DatatablesController::Pluck($columns, 'db')) . "
                FROM epreuve e 
                inner join matiere m on m.id=e.matiere_id
                inner join note n on e.id=n.epreuve_id
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
            // $actions = "<div class='actions'>"
            // . " <i class='bi bi-plus addNote' data-bs-toggle='modal' data-bs-target='#addNote'  data-id='".$row['id']."'></i>"
            // . " <i class='bi bi-eye seeNote'  data-id='".$row['id']."'></i>"
            // . "</div>";
            
            
            foreach (array_values($row) as $key => $value) {
                if($key == 0) {
                    $nestedData[] = $i;
                    $i++;
                } else {
                    $nestedData[] = $value;
                }
            }
            // $nestedData[] = $actions;
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
    * @Route("/releveNote/{etudiant}", name="etudiant_releve_note")
    */
    public function releveNote(Etudiant $etudiant,Request $request)
    {
        
        $k=0;
        $moyenne_classe=0;
        $em = $this->getDoctrine()->getManager();
       $etudiants=$em->getRepository(Etudiant::class)->findby(['classe'=>$etudiant->getClasse()]);
    //    $rnote_etudiant=[][];
       foreach($etudiants as $row)
       {   
            $moyenneClasse[$k]["moyenneMatiere"]=0;
            $moyenne_generale=0;
            $j=0;
            $matieres=$em->getRepository(Matiere::class)->findAll();
            $moyenneClasse[$k]["etudiant"]=$row->getNom();
            foreach($matieres as $matiere)
            {   
                
                $i=0;
                $moyenne_matiere=0;
                
                $epreuves=$em->getRepository(Epreuve::class)->findby(['classe'=>$etudiant->getClasse(),'matiere'=>$matiere->getId()]);
                $rnote_etudiant[$j]["matiere"]=$matiere->getDesignation();
                foreach($epreuves as $epreuve)
                {
                    if($epreuve->getValide()==1)
                    {
                        
                        $notes=$em->getRepository(Note::class)->findby(['epreuve'=>$epreuve->getId()]);
                    
                        foreach($notes as $note)
                       { 
                        if($note->getEtudiant()==$etudiant)
                        {
                            $i++;
                            $rnote_etudiant[$j]["controlN".$i.""]=$note->getNote();
                            //dd($rnote_etudiant[1]);
                            // dd($note->getNote());
                            $moyenne_matiere=$moyenne_matiere+$note->getNote();
                            
                            
                        }
                        if($note->getEtudiant()==$row)
                        {
                            
                            $moyenneClasse[$k]["moyenneMatiere"]= $moyenneClasse[$k]["moyenneMatiere"]+$note->getNote();
                        }
                        
                       }
                       //dd($moyenne_matiere);
                        
                    }
                    

                }
                $rnote_etudiant[$j]["noteMatiere"]=$moyenne_matiere/$i;
                $moyenne_generale=$moyenne_generale+$rnote_etudiant[$j]["noteMatiere"];
                $j++; 
            }  
            $moyenneClasse[$k]["moyenneMatiere"]= $moyenneClasse[$k]["moyenneMatiere"]/($j+1);
            $moyenne_classe=$moyenne_classe+$moyenneClasse[$k]["moyenneMatiere"];
              $moyenne_generale = $moyenne_generale/$j;
              $k++;
        
       }
       $moyenne_classe=$moyenne_classe/$k;
       // dd($moyenne_classe);
        $mpdf= new Mpdf();
        $html = $this->renderView('etudiant/releveNote.html.twig', [
            'title' => "Releve de note" ,
            'rnote_etudiant'=>$rnote_etudiant,
            'moyenne_generale'=>$moyenne_generale,
            'etudiant'=>$etudiant,
            'moyenne_classe'=>$moyenne_classe
        ]);
       
        $mpdf->WriteHtml($html);
        $mpdf->Output('releveDeNote.pdf','D');
        return new Response('The PDF file has been succesfully generated !');
    }

}
