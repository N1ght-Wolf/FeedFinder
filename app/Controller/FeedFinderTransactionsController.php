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
    public $uses = array('Venue','Review','FeedFinderTransaction','UserLookupTable',
                         'World','AdminOne','UkAdminThree','User', );

    public function index()
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', 300);
    }

    public function stats()
    {
    }

    public function review_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewByAddress($this->request->query);
            if (count($results) > 0) {
                $quartiles = $this->UkAdminThree->updateReview($results);
                echo json_encode($quartiles);
            } else {
                echo 'no result brah ...';
            }
        }
    }

    public function review_interq_adminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewByCity($this->request->query);
            if (count($results) > 0) {
                $quartiles = $this->AdminOne->updateReview($results);
                echo json_encode($quartiles);
            } else {
                echo 'no result brah ...';
            }
        }
    }

    public function review_interq_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewByCountry($this->request->query);
          //$this->_print_array($results);
          if (count($results) > 0) {
              $quartiles = $this->World->updateReview($results);
              echo json_encode($quartiles);
          } else {
              echo 'no result brah ...';
          }
        }
    }

    public function get_stats_venues()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Venue->getVenuesWithin($this->request->query);
            echo json_encode($results);
        }
    }

    public function average_rating()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            // print_r($this->request->query);
            $data = $this->request->query;
            $results = $this->Review->getVenueRating($data);
            $arrayName = array();
            switch ($data['model']) {
            case 'World':
              $inter_q_world = $this->World->updateVenueRating($results);
              echo json_encode($inter_q_world);

              break;
            case 'AdminOne':
              $inter_q_adminone = $this->AdminOne->updateVenueRating($results);
              echo json_encode($inter_q_adminone);
              break;
            case 'UkAdminThree':
              $inter_q_uk = $this->UkAdminThree->updateVenueRating($results);
              echo json_encode($inter_q_uk);
              break;
            default:
              # code...
              break;
          }
        }
    }

    public function users_interq_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->User->getUserBaseLocation($this->request->query);
            $latlng = $this->Venue->getLatLng($results);
            $quartiles = $this->World->updateUserCount($latlng);
            echo json_encode($quartiles);
        }
    }
    public function users_interq_adminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->User->getUserBaseLocation($this->request->query);
            $latlng = $this->Venue->getLatLng($results);
            $quartiles = $this->AdminOne->updateUserCount($latlng);
            echo json_encode($quartiles);
        }
    }
    public function users_interq_ukadminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->User->getUserBaseLocation($this->request->query);
            $latlng = $this->Venue->getLatLng($results);
            $quartiles = $this->UkAdminThree->updateUserCount($latlng);
            echo json_encode($quartiles);
        }
    }

    public function stats_submit()
    {
        $this->autoRender = false;

        if ($this->request->is('ajax')) {
            $from = $this->request->query['from-date'];
            $to = $this->request->query['to-date'];
            $action = $this->request->query['action'];

            $conditions = array('FeedFinderTransaction.created >=' => $from,
                            'FeedFinderTransaction.created <=' => $to,
                            'FeedFinderTransaction.action' => strtolower($action), );
            $fields = array('UNIX_TIMESTAMP(FeedFinderTransaction.created) * 1000 AS timestamp','COUNT(FeedFinderTransaction.action) AS mycount');
            $group = array('YEAR(FeedFinderTransaction.created)', 'MONTH(FeedFinderTransaction.created)', 'DAY(FeedFinderTransaction.created)');
            $results = $this->FeedFinderTransaction->find('all', array(
          'conditions' => $conditions,
          'fields' => $fields,
          'group' => $group,
        ));

            $final_array = array();
      //  var_dump($results);

        foreach ($results as $result => $value) {
            $timestamp = floatval($value[0]['timestamp']);
            $count = intval($value[0]['mycount']);
            $some_arr = array($timestamp,$count);
            $final_array[] = $some_arr;
        // echo $this->_print_array($results);
        }

            echo json_encode($final_array);
        }
    }

    public function getVenueByCountry()
    {
        $this->autoRender = false;

        if ($this->request->is('ajax')) {
            $results = $this->Venue->getVenueByIso($this->request->data);

            echo json_encode($results);
        }
    }

    public function totalUsers()
    {
        $this->autoRender = false;

        if ($this->request->is('ajax')) {
            $results = array();
            $results['total_users'] = $this->User->getTotalUsers($this->request->query);
            $ans = $this->User->getActiveUsers($this->request->query);
            $results['active_users'] = intval($ans[0][0]['activeUsers']);

            echo json_encode($results);
        }
    }

    public function userGraphData()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            echo json_encode($this->User->getUserGraphData($this->request->query));
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
              'conditions' => $conditions, ));
            echo json_encode($this->_calc_graph_data($result));
        }
    }

    public function _print_array($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
