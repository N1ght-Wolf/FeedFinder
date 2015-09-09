<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class UkAdminThree extends Model
{
    public $useDbConfig = 'postgresql';

    public function updateReview($results)
    {
        $this->updateAll(array('UkAdminThree.review' => 0));

        foreach ($results as $result => $value) {
            $lat = $value['Venue']['lat'];
            $lng = $value['Venue']['lng'];
            $count = $value[0]['mycount'];
            $ans = $this->find('first',
       array('conditions' => array("st_contains(UkAdminThree.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
            if(count($ans) > 0){
              $this->id = $ans['UkAdminThree']['id'];
              $this->saveField('review', $this->field('review') + $count);
            }

        }

     return $this->getInterquatile('review');
    //return $arrayName = array('a' => 'a');
    }

    public function getInterquatile($column)
    {
        $results = $this->find('all', array('order' => array("UkAdminThree.$column ASC"),
                                          'fields' => array("UkAdminThree.$column"),
                                          'conditions' => array("UkAdminThree.$column > 0"), ));
        $vals = array();
        foreach ($results as $key => $value) {
            $vals[] = $value['UkAdminThree']["$column"];
        }
        $count = count($results);

        $first = round(.25 * ($count + 1)) - 1;
        $second = round($count / 2);
        $third = round(.75 * ($count + 1)) - 1;

        $quartiles = array('first_q' => floatval($vals[$first]),
                       'second_q' => floatval($vals[$second]),
                       'third_q' => floatval($vals[$third]),
                       'geo_layer_name'=>'uk_admin_threes',
                       'results' => $vals[$count - 1]);

        return $quartiles;
    }

    public function updateUserCount($data){
      $this->updateAll(array('UkAdminThree.users' => 0));

      foreach ($data as $d => $value) {

        $lat = $value['lat'];
        $lng = $value['lng'];

        $ans = $this->find('first',
         array('conditions' => array("st_contains(UkAdminThree.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
            if(!empty($ans)){
              $this->id = $ans['UkAdminThree']['id'];
              $this->saveField('users', $this->field('users') + 1);
            }
      }
      return $this->getInterquatile('users');
    }

    public function updateVenueRating($data){
      $this->updateAll(array('UkAdminThree.venues' => 0),array('UkAdminThree.review' => 0));
      $arrayName = array();

      foreach ($data as $d => $value) {
        $lat = $value['lat'];
        $lng = $value['lng'];
        $rating = $value['avg'];
        $count = $value['venue_count'];

        $ans = $this->find('first',
         array('conditions' => array("st_contains(UkAdminThree.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
            if(!empty($ans)){
              $this->id = $ans['UkAdminThree']['id'];
              $this->saveField('venues', $rating);
              $this->saveField('review', $this->field('review') + $count);
            }
      }
      return $this->getInterquatile('venues');
    }
}
