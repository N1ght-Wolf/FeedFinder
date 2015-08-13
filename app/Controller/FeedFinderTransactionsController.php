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
    public $uses = array('Venue','FeedFinderTransaction','UserLookupTable', 'World','AdminOne');
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
        // $result = $this->FeedFinderTransaction->find('all',array(
        //   'fields'=>array('Venue.city','Venue.lat','Venue.lng','COUNT(Venue.city) AS mycount'),
        //   'group' =>array('Venue.city'),
        //   'conditions'=>array('FeedFinderTransaction.action'=>'review')
        // ));
        //
        //
        //         //$this->_print_array($query);
        //         foreach ($result as $key => $value) {
        //           $lat = $value['Venue']['lat'];
        //           $lng = $value['Venue']['lng'];
        //           $count = $value['0']['mycount'];
        //
        //           $ans = $this->AdminOne->find('first',
        //            array('conditions' => array("st_contains(AdminOne.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
        //           $this->AdminOne->id = $ans['AdminOne']['id'];
        //           $this->AdminOne->saveField('review', $this->AdminOne->field('review')+$count);
        //         }
        //
        //



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


    public function bounding_box()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
              $north_lat = $this->request->data('north_lat');
              $south_lat = $this->request->data('south_lat');
              $east_lng = $this->request->data('east_lng');
              $west_lng = $this->request->data('west_lng');
              $geo_json = $this->request->data('geo_json');

              $this->FeedFinderTransaction->recursive = 1;

              $query_result = $this->FeedFinderTransaction->find('all',array(
                'fields'=>array('Venue.city','Venue.lat','Venue.lng','COUNT(Venue.city) AS mycount'),
                'order'=>array('mycount DESC'),
                'group' =>array('Venue.city'),
                'conditions'=>array('FeedFinderTransaction.action'=>'review',
                                    'FeedFinderTransaction.lat >=' =>$south_lat,
                                    'FeedFinderTransaction.lat <=' =>$north_lat,
                                    'FeedFinderTransaction.lng >=' =>$west_lng,
                                    'FeedFinderTransaction.lng <=' =>$east_lng)
              ));


              echo json_encode($query_result);
        }
    }

    public function fetch_location_radius()
    {
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
            echo json_encode($query_result);
       }

    }


    public function country_reviews(){
      $this->autoRender =false;
      if($this->request->is('ajax')){
        $north_lat = $this->request->data('north_lat');
        $south_lat = $this->request->data('south_lat');
        $north_lng = $this->request->data('north_lng');
        $south_lng = $this->request->data('south_lng');
        $table = $this->request->data('iso3');


        $this->FeedFinderTransaction->recursive = 1;

        $query_result = $this->FeedFinderTransaction->find('all',array(
          'fields'=>array('Venue.city','Venue.lat','Venue.lng','COUNT(Venue.city) AS mycount'),
          'order'=>array('mycount DESC'),
          'group' =>array('Venue.city'),
          'conditions'=>array('FeedFinderTransaction.action'=>'review',
                              'FeedFinderTransaction.lat >=' =>$south_lat,
                              'FeedFinderTransaction.lat <=' =>$north_lat,
                              'FeedFinderTransaction.lng >=' =>$south_lng,
                              'FeedFinderTransaction.lng <=' =>$north_lng)
        ));


        $tablename = strtolower($table);
        $upperISOFirst = ucfirst($tablename);
        $table_model = "upperISOFirst";
        $dynamic = new Model(array('table' => $tablename.'s', 'name' => $upperISOFirst, 'ds' => 'postgresql'));
        // $query_result = $this->$$table_model->find('all');
        $this->$$table_model->updateAll(array($$table_model.'.review' => 0));
        $ans;
        foreach ($query_result as $result => $value) {
          $lat = $value['Venue']['lat'];
          $lng = $value['Venue']['lng'];
          $mycount = $value[0]['mycount'];
        $ans = $this->$$table_model->find('first',
         array('conditions' => array("st_contains(".$$table_model.".geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
        $this->$$table_model->id = $ans[$$table_model.'']['id'];
        $this->$$table_model->saveField('review', $this->$$table_model->field('review')+$mycount);
        //  //  $this->_print_array($ans);
        }
           echo json_encode($query_result);
       }
       echo $$tabl;

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
