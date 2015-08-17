<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');
App::import('vendor', 'geoPHP/geoPHP.inc');


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
    public $uses = array('Venue','FeedFinderTransaction','UserLookupTable', 'World','AdminOne','UkAdminThree');
    // public $layout = 'Highcharts.chart.demo';

    private $timespan_options = array('Life time','Today','Yesterday','This week','Last week','This month',
                          'Last month','3 month','6 month','custom');

    public $actions = array('add existing venue',
    'add new venue',
    'review',
    'register',
    'survey',
    'survey get',
    'venues',
    'venue get');
    public $dynamic;


    public function index()
    {
      ini_set('memory_limit', '2048M');
      ini_set('max_execution_time', 300);
        $this->set('timespan_options', $this->timespan_options);
        $this->set('actions', $this->actions);
        //
        // $result = $this->FeedFinderTransaction->find('all',array(
        //   'fields'=>array('Venue.address','Venue.lat','Venue.lng','COUNT(Venue.address) AS mycount'),
        //   'group' =>array('Venue.address'),
        //   'conditions'=>array('FeedFinderTransaction.action'=>'review', 'Venue.iso'=>'GBR')
        // ));
        //
        //
        //         //$this->_print_array($query);
        //         foreach ($result as $key => $value) {
        //           $lat = $value['Venue']['lat'];
        //           $lng = $value['Venue']['lng'];
        //           $count = $value['0']['mycount'];
        //
        //           $ans = $this->UkAdminThree->find('first',
        //            array('conditions' => array("st_contains(UkAdminThree.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
        //           $this->UkAdminThree->id = $ans['UkAdminThree']['id'];
        //           $this->UkAdminThree->saveField('review', $this->UkAdminThree->field('review')+$count);
        //         }
        // //
        // //



    }

      public function world_review_range(){
        $this->autoRender =false;

        if ($this->request->is('ajax')){
          $model_name = $this->request->query['model'];
          $variable='model_name';
          $max = $this->$$variable->find('all',array('order'=>array("$model_name.review ASC"),
                                                'fields'=>array("$model_name.review"),
                                                'conditions' => array("$model_name.review > 0")));

          $vals = array();
          foreach ($max as $key => $value) {
            $vals[] = $value[$model_name]['review'];
          }
          $count = count($max);

          $first = round( .25 * ( $count + 1 ) ) - 1;
          $second = round($count/2);
          $third = round( .75 * ( $count + 1 ) ) - 1;

          $quartiles = array('first_q'=> floatval($vals[$first]),
                             'second_q'=> floatval($vals[$second]),
                             'third_q'=> floatval($vals[$third]),
                             'max' => $vals[$count-1]);
          echo json_encode($quartiles);

        }

      }



    public function actions()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) { //access the values by index
        $selected_action = $this->request->query('actions');
            $selected_timespan = $this->request->query('timespan');
            $conditions = $this->_timespan_condition_switch($selected_action, $selected_timespan);
            $result = $this->FeedFinderTransaction->find('all',
        array('fields' => array('FeedFinderTransaction.created'),
              'order' => 'FeedFinderTransaction.created',
              'conditions' => $conditions));
            echo json_encode($this->_calc_graph_data($result));
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
                           'FeedFinderTransaction.created >= NOW() - INTERVAL 6 month');
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
