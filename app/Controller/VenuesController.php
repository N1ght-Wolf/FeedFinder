<?php

class VenuesController extends AppController
{
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','Review','FeedFinderTransaction','UserLookupTable',
                         'World','AdminOne','UkAdminThree','User', );


    public function index($id = null)
    {
        $venue = $this->Venue->findAllById($id);
        $ratings = $this->Venue->findRatingsById($id);
        $average_rating = $this->Review->getAverageVenueRating($id);

        $this->set('venue', $venue[0]);
        $this->set('ratings', $ratings);
        $this->set('average_rating', $average_rating[0][0]);
    }
    public function get_reviews(){
      $this->autoRender = false;
      if ($this->request->is('ajax')) {
          $result = $this->Review->getReviewPaginated($this->request->query);
          echo json_encode($result);
      }
    }

    public function get_count(){
      $this->autoRender = false;
      if ($this->request->is('ajax')) {
          $id = $this->request->query('id');
          $result = $this->Venue->find('all',array('conditions'=>array('Venue.id'=>$id)));

          echo json_encode(count($result[0]['Review']));
      }
    }

}
