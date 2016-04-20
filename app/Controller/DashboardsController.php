<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::import('vendor', 'geoPHP/geoPHP.inc');

/**
 * Static content controller.
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class DashboardsController extends AppController
{
    public $components = array('Session', 'RequestHandler');
    public $helpers = array('Session', 'Html', 'Form', 'Js' => array('jquery'));
    public $uses = array(
        //Feed finder tables
        'Venue',
        'Review',
        'User',
        //postgre geospatial tables
        'County',
        'Soa'
        );

    // index page action
    public function index()
    {

    }

    /*
    Called from dashboard.js
    Takes queries from the search control
    */
    public function map_query()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->layout = null;
            //get the sent 
            $query = $this->request->query;
            $model = $query['category']['model'];
            $result = $this->$model->route($query);
            $json = array("request" => $query, "result"=>$result);
            echo "<pre>";
            print_r($json);
            echo "</pre>";
            header('Content-type: application/json');
            echo json_encode($json);
            exit;
        }
    }

    public function map_click()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->layout = null;
            //get the sent 
            $query = $this->request->query;
            $model = $query['model'];
            $result = $this->$model->getFeatureInfo($query);
            if(empty($result)){
                $result = array($query['model']=>array($query['pg_column'] => 0));
            }
            echo json_encode($result);
            exit;
        }
    }
}
