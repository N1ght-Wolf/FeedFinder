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
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','FeedFinderTransaction','UserLookupTable');
    // public $layout = 'Highcharts.chart.demo';

    private $timespan_options = array('Life time','Today','Yesterday','This week','Last week','This month',
                          'Last month','3 month','6 month','custom', );

    public $actions = array('add existing venue',
    'add new venue',
    'review',
    'register',
    'survey',
    'survey get',
    'venues',
    'venue get' );

    public function index()
    {
        $this->set('timespan_options', $this->timespan_options);
        $this->set('actions', $this->actions);
        $result = $this->FeedFinderTransaction->find('all',array('conditions'=>array("FeedFinderTransaction.venue_id
         IN (SELECT `id` FROM `venues` WHERE `country` = 'United Kingdom') ")));
        //$this->_print_array($result);
        // $json = file_get_contents('http://code.highcharts.com/mapdata/countries/gb/gb-all.geo.json');
        // $obj = json_decode($json,true);
        // $this->_print_array($obj['features']);
    }

    public function actions()
    {
      $this->autoRender = false;
      if($this->request->is('ajax'))
      { //access the values by index
        $selected_action =  $this->request->query('actions');
        $selected_timespan =  $this->request->query('timespan');
        $conditions = $this->_timespan_condition_switch($selected_action,$selected_timespan);
        $result = $this->FeedFinderTransaction->find('all',
        array('fields'=>array('FeedFinderTransaction.created'),
              'order'=>'FeedFinderTransaction.created',
              'conditions'=>$conditions));
        echo json_encode($this->_calc_graph_data($result));
      }
    }

    public function basic_data_counts(){
      $this->autoRender = false;
      if($this->request->is('ajax')){
      $counts = array();
      $counts['review'] =$this->FeedFinderTransaction->find('count',array('conditions'=>array(
                                                         'FeedFinderTransaction.action'=>'review')));

      $counts['register']=$this->FeedFinderTransaction->find('count',array('conditions'=>array(
                                                        'FeedFinderTransaction.action'=>'register')));
      $counts['venues'] = $this->Venue->find('count');

      $counts['users']=$this->UserLookupTable->find('count');

        echo json_encode($counts);
      }
    }

    public function fetchRelevantData(){
      $this->autoRender =false;

      if($this->request->is('ajax')){
        $lat = $this->request->data('lat');
        $lon =  $this->request->data('lng');
        $rad =5; $R = 6371;
        //src http://www.movable-type.co.uk/scripts/latlong-db.html
        $maxLat = $lat + rad2deg($rad/$R);
        $minLat = $lat - rad2deg($rad/$R);

        $maxLon = $lon + rad2deg($rad/$R/cos(deg2rad($lat)));
        $minLon = $lon - rad2deg($rad/$R/cos(deg2rad($lat)));

        $query_result = $this->FeedFinderTransaction->find('all',array(
          'fields'=>array('FeedFinderTransaction.lat','FeedFinderTransaction.lng'),
          'conditions'=>array("FeedFinderTransaction.lat BETWEEN $minLat AND $maxLat",
                             "FeedFinderTransaction.lng BETWEEN $minLon AND $maxLon")));
          // $arrayName = array('lat' => $lat,'lng'=>$lng );
         echo json_encode($query_result);
      }
    }


    public function _calc_graph_data($query_result)
    {
        $index_date = new DateTime();
        $index_date->format('Y-m-d');
        $array_months = array();
        $action_count = array();
        $counter = 0;

        foreach ($query_result as $result) {
            $index_date->modify($result['FeedFinderTransaction']['created']);
        // if the month of the year is not in the array, add it
        if (!in_array($index_date->format('M y'), $array_months)) {
            $array_months[] = $index_date->format('M y');
            $counter = 0;
            ++$counter;
            $action_count[] = $counter;
        } else {
            ++$counter;
            $index = count($action_count) - 1;
            if ($index >= 0) {
                $action_count[$index] = $counter;
            }
        }
        }
        $result_array = array();
        $result_array['month'] = $array_months;
        $result_array['counts'] = $action_count;

        return $result_array;
    }



    public function _timespan_condition_switch($selected_action, $selected_timespan)
    {
        switch ($selected_timespan) {
        case 0://Lifetime
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action]);
        break;

        case 1: //Today
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'DATE(FeedFinderTransaction.created) = CURRENT_DATE');
        break;

        case 2://yesterday
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'DATE(FeedFinderTransaction.created) = CURRENT_DATE - INTERVAL 1 DAY');
        break;

        case 3://this week
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'FeedFinderTransaction.created > DATE_SUB(NOW(), INTERVAL 1 WEEK)');
        break;

        case 4: //last week
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'FeedFinderTransaction.created >= CURRENT_DATE() - INTERVAL DAYOFWEEK(CURRENT_DATE())+6 DAY',
                           'FeedFinderTransaction.created < CURRENT_DATE() - INTERVAL DAYOFWEEK(CURDATE())-1 DAY');
        break;

        case 5://this month
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'YEAR (FeedFinderTransaction.created) = YEAR(CURRENT_DATE())',
                           'MONTH(FeedFinderTransaction.created) = MONTH(CURRENT_DATE())');
        break;

        case 6: // last month
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'YEAR (FeedFinderTransaction.created) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH)',
                           'MONTH(FeedFinderTransaction.created) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH)');
        break;

        case 7:// 3 months
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'FeedFinderTransaction.created >= NOW() - INTERVAL 3 month');
        break;

        case 8: // 6 months
              return array('FeedFinderTransaction.action' => $this->actions[$selected_action],
                           'FeedFinderTransaction.created >= NOW() - INTERVAL 6 month' );
        break;

        case 9://custom date
              // to do !!!!
        break;
        default:
          # code...
          break;
      }
    }

    public function _print_array($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
