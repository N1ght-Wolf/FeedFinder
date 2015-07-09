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

    private $date = array('Life time','Today','Yesterday','This week','Last week','This month',
                          'Last month','3 month','6 month','custom');

    public $actions = array('add_existing_venue'=>'add existing venue',
    'add_new_venue'=>'add new venue',
    'agreement_post'=>'agreement post',
    'auth'=>'authentication',
    'change_password_post'=>'change password',
    'forgotten_post'=>'forgot password',
    'register'=>'register',
    'review'=>'review',
    'survey'=>'survey',
    'survey_get'=>'survey get',
    'venues'=>'venues',
    'venue_get'=>'venue get',
    'verified_password'=>'verified password');



    public function index()
    {
      $this->set('date_options',$this->date);

      $this->set('actions', $this->actions);
      $select_index = $this->request->query('date_span');
      $this->_print_array(array_keys($this->actions));
    }

    public function action_graph_data(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
        $selected_action =  $this->request->query['actions'];
        $select_index = $this->request->query('date_span');

        $conditions = $this->_timespan_condition_switch($select_index);
        $conditions['FeedFinderTransaction.action'] = $selected_action;
        $result = $this->FeedFinderTransaction->
        find('all',array('fields'=>array('FeedFinderTransaction.created'),
                         'order'=>'FeedFinderTransaction.created',
                         'conditions'=>$conditions));

        echo json_encode($this->_calc_graph_data($result));
      }
    }


    public function date_range(){
      $this->autoRender =false;
      if ($this->request->is('ajax'))
       {

        $select_index = $this->request->query('date_span');

        $query_array = array('fields'=>array('FeedFinderTransaction.user_id,FeedFinderTransaction.email,
        FeedFinderTransaction.lat,FeedFinderTransaction.lng, count(FeedFinderTransaction.user_id) as mycount'),
        'order'=>'mycount DESC',
        'group'=>'FeedFinderTransaction.user_id');
        $query_array['conditions']= $this->_timespan_condition_switch($select_index);
        $result = $this->FeedFinderTransaction->find('all',$query_array);
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

    public function _timespan_condition_switch($select_index){
      switch ($select_index) {
        case 0://Lifetime
              return array('FeedFinderTransaction.action'=>'review');
        break;

        case 1: //Today
              return array('FeedFinderTransaction.action'=>'review',
                           'DATE(FeedFinderTransaction.created) = CURRENT_DATE');
        break;

        case 2://yesterday
              return array('FeedFinderTransaction.action'=>'review',
                           'DATE(FeedFinderTransaction.created) = CURRENT_DATE - INTERVAL 1 DAY');
        break;

        case 3://this week
              return array('FeedFinderTransaction.action'=>'review',
                           'FeedFinderTransaction.created > DATE_SUB(NOW(), INTERVAL 1 WEEK)');
        break;

        case 4: //last week
              return array('FeedFinderTransaction.action'=>'review',
                           'FeedFinderTransaction.created >= CURRENT_DATE() - INTERVAL DAYOFWEEK(CURRENT_DATE())+6 DAY',
                           'FeedFinderTransaction.created < CURRENT_DATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY');
        break;

        case 5://this month
              return array('FeedFinderTransaction.action'=>'review',
                           'YEAR (FeedFinderTransaction.created) = YEAR(CURRENT_DATE())',
                           'MONTH(FeedFinderTransaction.created) = MONTH(CURRENT_DATE())');
        break;

        case 6: // last month
              return array('FeedFinderTransaction.action'=>'review',
                           'YEAR (FeedFinderTransaction.created) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)',
                           'MONTH(FeedFinderTransaction.created) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)');
        break;

        case 7:// 3 months
              return array('FeedFinderTransaction.action'=>'review',
                           'FeedFinderTransaction.created >= NOW() - INTERVAL 3 month');
        break;

        case 8: // 6 months
              return array('FeedFinderTransaction.action'=>'review',
                           'FeedFinderTransaction.created >= NOW() - INTERVAL 6 month');
        break;

        case 9:
              // to do !!!!
        break;
        default:
          # code...
          break;
      }
    }

    function _print_array($array){
      echo "<pre>";
      print_r($array);
      echo "</pre>";

    }
}
