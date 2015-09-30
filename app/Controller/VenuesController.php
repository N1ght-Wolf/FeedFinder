<?php

class VenuesController extends AppController
{
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','Review','FeedFinderTransaction','UserLookupTable',
                         'World','AdminOne','UkAdminThree','User', );
    public $excellent;
    public $v_good;
    public $average;
    public $poor;
    public $terrible;
    public $reviews = array(
      'excellent'=>array(),
      'v_good'=>array(),
      'average'=>array(),
      'poor'=>array(),
      'terrible'=>array()
    );
    // public $ratings = array(
    //   'baby_fac'=>0,
    //   'privacy'=>0,
    //   'comfort'=>0,
    //   'hygiene'=>0,
    //   'avg_spend'=>0);

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

    public function get_review(){
      //for new
      $this->autoRender = false;
      if ($this->request->is('ajax')) {

          $result = $this->Review->getReviewPaginated($this->request->query);

          $reviews['terrible'] = $this->Review->getReviewCountByPerf(
          $this->request->query,1,2);

          $reviews['poor'] = $this->Review->getReviewCountByPerf(
          $this->request->query,2,3);

          $reviews['average'] = $this->Review->getReviewCountByPerf(
          $this->request->query,3,4);

          $reviews['v_good'] = $this->Review->getReviewCountByPerf(
          $this->request->query,4,5);

          $reviews['excellent'] = $this->Review->getReviewCountByPerf(
          $this->request->query,5,6);
          $performances = array(
            'terrible' =>count($reviews['terrible']),
            'poor'=>count($reviews['poor']),
            'average'=>count($reviews['average']),
            'v_good'=>count($reviews['v_good']),
            'excellent'=>count($reviews['excellent']));
          $json = array('paginated' => $result, 'sorted'=>$reviews,'performances'=>$performances );
          echo json_encode($json);
      }
    }


    public function get_count(){
      $this->autoRender = false;
      if ($this->request->is('ajax')) {
          $id = $this->request->query['query']['id'];
          $from = $this->request->query['query']['from'];
          $to = $this->request->query['query']['to'];
          $rating_low_bound = $this->request->query['query']['rating_low'];
          $rating_high_bound = $this->request->query['query']['rating_high'];
          $conditions = array();
          if($rating_high_bound == 0 && $rating_low_bound ==0){
            $conditions = array(
            'Review.venue_id'=>$id,
            'Review.created >=' => $from,
            'Review.created <=' => $to);
          }else{
            $conditions = array(
            'Review.venue_id'=>$id,
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.average_rating >=' => $rating_low_bound,
            'Review.average_rating <' => $rating_high_bound);
          }


          $result = $this->Review->find('all',array(
            'conditions'=>$conditions));
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

    public function get_venue_ratings(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
        $ratings = $this->Review->getVenueAttributeRating($this->request->query);
        if(!empty($ratings)){
          echo json_encode($ratings[0]);
        }else{
          echo 'there rating returned was empty
                ... get_venue_ratings';
        }
      }
    }

    public function get_venue_perform(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
        $reviews['terrible'] = $this->Review->getVenuePerformance(
        $this->request->query,1,2);

        $reviews['poor'] = $this->Review->getVenuePerformance(
        $this->request->query,2,3);

        $reviews['average'] = $this->Review->getVenuePerformance(
        $this->request->query,3,4);

        $reviews['v_good'] = $this->Review->getVenuePerformance(
        $this->request->query,4,5);

        $reviews['excellent'] = $this->Review->getVenuePerformance(
        $this->request->query,5,6);

        // print_r($this->Review->getLastQuery());
        $perf = array();
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
