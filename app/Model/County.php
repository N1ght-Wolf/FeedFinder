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

    public function update(){
        //update all the users
        return $this->updateReviewAll();
    }

    public function updateReviewAll(){
        $Review = new Review();
        $fields = array('Venue.county_id','count(Venue.county_id) as count');
        $group = array('Venue.county_id');

        $results = $Review->find('all', array(
          'fields'=>$fields,
          'group'=>$group
          ));
        $this->updatePgTable($results, 'reviews_all');
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
