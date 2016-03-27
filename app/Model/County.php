<?php

App::uses('Model', 'Model');
App::uses('Review', 'Model');
App::uses('Venue', 'Model');
App::uses('User', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class County extends Model
{
    public $useDbConfig = 'postgresql';
    public $timeRange = array('2013-04-25 15:43:18','today','this week','this month', '-3 month','-6 month','January this year');
    public $reviewColumn = array(
        'review_all'=>'2013-04-25 15:43:18','review_today'=>'today','review_this_week'=>'this week today',
        'review_this_month'=>'first day of this month today','review_three_month'=>'-3 month today',
        'review_six_month'=>'-6 month today','review_this_year'=>'January this year'

        );
    public $venueColumn = array(
        'venue_all','venue_today','venue_this_week',
        'venue_this_month','venue_three_month',
        'venue_six_month','venue_this_year'
        );
    public $userColumn = array(
        'user_all','user_today','user_this_week',
        'user_this_month','user_three_month',
        'user_six_month','user_this_year'
        );
    public function update(){
        //update all the users
        return $this->updateReview();
    }

    public function updateReview(){
        $Review = new Review();
        $fields = array('Venue.county_id','count(Review.review_text) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->reviewColumn as $key => $value) {
            print_r($key);
            $conditions = array(
                'Review.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'Review.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));
            print_r($conditions);
            $results = $Review->find('all', array(
            'fields'=>$fields,
            'conditions'=>$conditions,
            'group'=>$group
          ));
            $this->updatePgTable($results, $key);
        }
    }

    public function updateVenue(){
        $Venue = new Venue();
        $fields = array('Venue.county_id','count(Review.review_text) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->reviewColumn as $key => $value) {
            print_r($key);
            $conditions = array(
                'Review.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'Review.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));
            print_r($conditions);
            $results = $Review->find('all', array(
            'fields'=>$fields,
            'conditions'=>$conditions,
            'group'=>$group
          ));
            $this->updatePgTable($results, $key);
        }
    }


    public function updatePgTable($results, $column_name){
        $id = 0;
        $saveMany = array();
        $this->updateAll(array($column_name => 0));
        foreach ($results as $result => $value) {

            $id = $value['Venue']['county_id'];
            $count = $value['0']['count'];
            if (isset($id) && isset($count)) {
                $saveMany[] = array('County' => array('id' => $id, $column_name => $count));
            }
        }
        //print_r($saveMany);
        $this->saveMany($saveMany);
    }

    public function getFeatureInfo($query)
    {  
        //    SELECT id FROM admin_ones WHERE ST_Contains(geom, ST_GeomFromText('POINT($lng $lat)',4326))
        $lat = $query['latitude'];
        $lng = $query['longitude'];
        $column_name = $query['pg_column'];
        $result = $this->find('first',array(
            'conditions' => array("ST_Contains(geom, ST_GeomFromText('POINT($lng $lat)',4326))"),
            'fields'=> array($column_name)
            ));
        return $result;
    }
}
