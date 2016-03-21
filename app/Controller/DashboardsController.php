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
        'AdminOne',
        'UkAdminThree',
        'World'
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
            $this->disableCache();
            $this->layout = null;
            $model = $this->request->query('category')['model'];
            echo json_encode($this->request->query);
        }
    }

}
