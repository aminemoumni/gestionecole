<?php

namespace App\Controller\professeur;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DatatablesController extends AbstractController {

    /**
     * Pull a particular property from each assoc. array in a numeric array, 
     * returning and array of the property values from each item.
     *
     *  @param  array  $a    Array to get data from
     *  @param  string $prop Property to read
     *  @return array        Array of property values
     */
    static function Pluck($a, $prop) {
        $out = array();
        for ($i = 0, $len = count($a); $i < $len; $i++) {
            $out[] = $a[$i][$prop];
        }
        return $out;
    }

    /*
     * search
     *      
     */

    static function Search($request, $columns) {
        $where = "";
        $params = $request->query;
        if (!empty($params->get('search')['value'])) {
            $search = $params->get('search')['value'];
            foreach ($columns as $key => $value) {
                if ($key == 0) {
                    $where = "and (" . $value['db'] . " LIKE '%$search%' ";
                } else {
                    $where .= " OR " . $value['db'] . " LIKE '%$search%' ";
                }
            }
            $where .= " )";
        }
        return $where;
    }

    /*
     * search
     *      
     */

    static function Order($request, $columns) {
        $params = $request->query;
        $sqlRequest = "";
        $sqlRequest = " ORDER BY " . self::Pluck($columns, 'db')[$params->get('order')[0]['column']] . "   " . $params->get('order')[0]['dir'] . "  LIMIT " . $params->get('start') . " ," . $params->get('length') . " ";
        return $sqlRequest;
    }
    
    
    
    
    //example server side no 
    
        /**
     * @Route("/list2", name="professeur_question_list2" , options = { "expose" = true })
     */
    public function list2Action() {
        $em = $this->getDoctrine()->getManager();
        $questions = $em->getRepository('App:Question')->GetAuestions();

        $data = array();
        foreach ($questions as $key => $value) {
            $question_edit = $this->generateUrl('question_edit', ['id' => $value['id']]);
            $question_newchoix = $this->generateUrl('question_newchoix', ['id' => $value['id']]);

            $actions = "<div class='actions'>"
                    . "<a href='$question_edit' title='Modifier la question'><i class='fa fa-pencil'></i></a>"
                    . "<a href='$question_newchoix' title='Ajouter un nouveau choix '><i class='fa fa-sort-numeric-asc'></i></a>"
                    . "<a  title='Supprimer la question '><i class='fa fa-trash-o'></i></a>"
                    . "</div>"
            ;

            $data[] = array($value['id'],
                $value['themeDesignation'],
                $value['itemeDesignation'],
                $value['questionLibelle'],
                $value['casLibelle'],
                $value['questionType'],
                $actions);
        }

        $response = new JsonResponse(["data" => $data]);
        return new Response($response->getContent());
    }

}
