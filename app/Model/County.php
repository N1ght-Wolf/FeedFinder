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
        'venue_all'=>'2013-04-25 15:43:18','venue_today'=>'today','venue_this_week'=>'this week today',
        'venue_this_month'=>'first day of this month today','venue_three_month'=>'-3 month today',
        'venue_six_month'=>'-6 month today','venue_this_year'=>'January this year'
        );

    public $userColumn = array(
        'user_all'=>'2013-04-25 15:43:18','user_today'=>'today','user_this_week'=>'this week today',
        'user_this_month'=>'first day of this month today','user_three_month'=>'-3 month today',
        'user_six_month'=>'-6 month today','user_this_year'=>'January this year'
        );

    public $friendlinessColumn = array(
        'friendliness_all'=>'2013-04-25 15:43:18','friendliness_today'=>'today','friendliness_this_week'=>'this week today',
        'friendliness_this_month'=>'first day of this month today','friendliness_three_month'=>'-3 month today',
        'friendliness_six_month'=>'-6 month today','friendliness_this_year'=>'January this year'
        );

    public function update(){
        $this->updateReview();
        $this->updateVenue();
        //$this->updateUser();
        $this->updateFriendliness();
    }

    public function updateReview(){
        $Review = new Review();
        $fields = array('Venue.county_id','count(Review.review_text) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->reviewColumn as $key => $value) {
            $conditions = array(
                'Review.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'Review.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));
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
        $fields = array('Venue.county_id','count(*) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->venueColumn as $key => $value) {
            $conditions = array(
                'Venue.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'Venue.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));
            $results = $Venue->find('all', array(
            'fields'=>$fields,
            'conditions'=>$conditions,
            'group'=>$group
          ));
            $this->updatePgTable($results, $key);
        }
    }

    public function updateUser(){
        $Review = new Review();
        $fields = array('Venue.county_id','count(*) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->userColumn as $key => $value) {
            $conditions = array(
                'User.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'User.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));

            $results = $Review->find('all', array(
            'fields'=>$fields,
            'conditions'=>$conditions,
            'group'=>$group
          ));
        $this->updatePgTable($results, $key);
        }

    }

    public function updateFriendliness(){
        $Review = new Review();
        $fields = array('Venue.county_id',
            'Round(AVG(Review.q1+Review.q2+Review.q3+Review.q4)/4,1) as count');
        $group = array('Venue.county_id');
        $conditions = array();

        foreach ($this->friendlinessColumn as $key => $value) {
            $conditions = array(
                'Review.created >=' => date('Y-m-d H:i:s', strtotime($value)),
                'Review.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')));

            $results = $Review->find('all', array(
            'fields'=>$fields,
            'conditions'=>$conditions,
            'group'=>$group
          ));
        //print_r($results);
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

    public function updateCountyId(){
        $Venue = new Venue();
        $saveMany = array();
        $latitude=0;
        $longitude=0;
        $results = $Venue->find('all',array(
            'conditions'=>array('Venue.county_id'=> null),
            'fields' => array('Venue.latitude','Venue.longitude','Venue.id')
            ));
        foreach ($results as $key => $value) {
            $latitude = $value['Venue']['latitude'];
            $longitude = $value['Venue']['longitude'];
            $venueId = $value['Venue']['id'];

            $shapeId = $this->find('all',array(
            'conditions' => array("ST_Contains(geom, ST_GeomFromText('POINT($longitude $latitude)',4326))"),
            'fields'=> array('County.id')
            ));

            if(empty($shapeId)){
                $saveMany[] =  array('Venue'=>array('id'=>$venueId,'county_id'=>-1));   
            }else{
                echo "<pre>";
                print_r($venueId);
                echo "</pre>";
                $saveMany[] = array('Venue' =>array('id'=>$venueId,'county_id'=>$shapeId['0']['County']['id']));
            }
        }
        //$Venue->saveMany($saveMany);
    }
}
