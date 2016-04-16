<?php

class VenuesController extends AppController
{
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','Review','User');


    public function index()
    {
      if(!empty($this->request->query)){
        $id = $this->request->query('id');
        //check the id is not null
        if($id != null){
          
        }
      }
        
    }
    
    public function venue_info(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->layout = null;
            $query = $this->request->query;
            $result = $this->Venue->getVenueInfo($query);
            echo json_encode($result);
            exit;
        }
    }
}
