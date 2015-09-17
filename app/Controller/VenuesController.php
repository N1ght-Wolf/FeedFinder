<?php

class VenuesController extends AppController
{
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','Review','FeedFinderTransaction','UserLookupTable',
                         'World','AdminOne','UkAdminThree','User', );


    public function index()
    {
        $id = $this->params['url']['id'];

        $venue = $this->Venue->findAllById($id);
        $venue_rating = $this->Review->getVenueAvgRating($id);
        $this->set('venue', $venue[0]);
        $this->set('venue_rating',$venue_rating);

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
          $id = $this->request->query['query']['id'];
          $from = $this->request->query['query']['from'];
          $to = $this->request->query['query']['to'];

          $result = $this->Review->find('all',array(
            'conditions'=>array(
            'Review.venue_id'=>$id,
            'Review.created >=' => $from,
            'Review.created <=' => $to)));
          echo json_encode(count($result));
      }
    }


    public function get_venue_attr_rating(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
        $ratings = $this->Review->getVenueAttributeRating($this->request->query);
        echo json_encode($ratings);
      }
    }

    public function get_venue_perform(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
        $terrible = $this->Review->getVenuePerformance(
        $this->request->query,0,1);

        $poor = $this->Review->getVenuePerformance(
        $this->request->query,1,2);

        $average = $this->Review->getVenuePerformance(
        $this->request->query,2,3);

        $v_good = $this->Review->getVenuePerformance(
        $this->request->query,3,4);

        $excellent = $this->Review->getVenuePerformance(
        $this->request->query,4,5);

        // print_r($this->Review->getLastQuery());

        $performance = array(
          'terrible' =>$terrible,
          'poor'=>$poor,
          'average'=>$average,
          'v_good'=>$v_good,
          'excellent'=>$excellent);
        echo json_encode($performance);
      }
    }

}
