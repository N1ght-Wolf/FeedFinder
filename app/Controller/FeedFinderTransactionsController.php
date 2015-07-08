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

    public function date_range(){
      $this->autoRender =false;
      if ($this->request->is('ajax'))
       {
         $select_index = $this->request->query('date span');

         $result = $this->FeedFinderTransaction->find('all',
               array('fields'=>array('FeedFinderTransaction.user_id,count(FeedFinderTransaction.user_id) as myCount,FeedFinderTransaction.email,FeedFinderTransaction.lat,FeedFinderTransaction.lng'),
              'order'=>'myCount','conditions'=>array('FeedFinderTransaction.action'=>'review',
              'group'=>'FeedFinderTransaction.user_id',
                                  'FeedFinderTransaction.created >= NOW() - INTERVAL 3 MONTH')));
               echo json_encode($result);

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
