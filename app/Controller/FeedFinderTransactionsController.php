<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');

/**
 * Static content controller.
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class FeedFinderTransactionsController extends AppController
{
    public $components = array('Session','Highcharts.Highcharts','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js'=>array('jquery'));
    public $uses = array();
    public $layout = 'Highcharts.chart.demo';
    public $Highcharts = null;

    private $date = array('Lifetime','This week','This month','Last month','3 months','6 months','custom');


    public function index()
    {

    $this->set('date',$this->date);

    }

    public function reviews(){
      $this->autoRender = false;
       $this->request->onlyAllow('ajax');

      if($this->request->is('ajax')){
        $from = $this->request->query('from');
        $to = $this->request->query('to');
        $compare= $this->request->query('compare');

        $result = $this->FeedFinderTransaction->find('all',
        array('fields'=>array('FeedFinderTransaction.created'),
              'order'=>'FeedFinderTransaction.created',
              'conditions'=>array('FeedFinderTransaction.action'=>'review',
                                  'FeedFinderTransaction.created >= '=> $from,
                                  'FeedFinderTransaction.created <= '=>$to)));

            $to_json = $this->_calc_graph_data($result);
            echo json_encode($to_json);
    }
    }

    public function most_active(){

      $this->autoRender = false;
       $this->request->onlyAllow('ajax');
      if($this->request->is('ajax')){
        $from = $this->request->query('from');
        $to = $this->request->query('to');
        $this->paginate =
        array('fields'=>array('FeedFinderTransaction.user_id, count(user_id)'),
              'order'=>'mycount DESC',
              'group'=>'FeedFinderTransaction.user_id',
              'limit'=>5,
              'conditions'=>array('FeedFinderTransaction.action'=>'review',
                                  'FeedFinderTransaction.created >= '=> $from,
                                  'FeedFinderTransaction.created <= '=>$to));
                                  $result = $this->paginate('FeedFinderTransaction');

                                  $this->set('users',$result);
    }

    }


















    public function _calc_graph_data($query_result)
    {
      $index_date = new DateTime();
      $index_date->format('Y-m-d');
      $array_months = array();
      $review_count = array();
      $counter = 0;

      foreach ($query_result as $result){

        $index_date->modify($result['FeedFinderTransaction']['created']);
        // if the month of the year is not in the array, add it
        if(!in_array($index_date->format('M y'),$array_months)){
           $array_months[] = $index_date->format('M y');
           $counter=0;
           $counter++;
           $review_count[] = $counter;
         }
         else{
            $counter++;
            $index = count($review_count) -1;
            if($index >= 0){
              $review_count[$index] = $counter;
            }
        }
      }
      $result_array = array();
      $result_array['month'] = $array_months;
      $result_array['counts'] = $review_count;

      return $result_array;
    }

    function _print_array($array){
      echo "<pre>";
      print_r($array);
      echo "</pre>";

    }
}
