<?php
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AdminOne extends Model {
  public $useDbConfig = 'postgresql';

  public function updateReview($results)
  {
      $this->updateAll(array('AdminOne.review' => 0));

      foreach ($results as $result => $value) {
          $lat = $value['Venue']['lat'];
          $lng = $value['Venue']['lng'];
          $count = $value[0]['mycount'];
          $ans = $this->find('first',
     array('conditions' => array("st_contains(AdminOne.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
          if(count($ans) > 0){
            $this->id = $ans['AdminOne']['id'];
            $this->saveField('review', $this->field('review') + $count);
          }

      }

   return $this->getInterquatile('review');
  //return $arrayName = array('a' => 'a');
  }

  public function getInterquatile($column)
  {
      $results = $this->find('all', array('order' => array("AdminOne.$column ASC"),
                                        'fields' => array("AdminOne.$column"),
                                        'conditions' => array("AdminOne.$column > 0"), ));

      $vals = array();
      foreach ($results as $key => $value) {
          $vals[] = $value['AdminOne']["$column"];
      }
      $count = count($results);

      $first = round(.25 * ($count + 1)) - 1;
      $second = round($count / 2);
      $third = round(.75 * ($count + 1)) - 1;

      $quartiles = array('first_q' => floatval($vals[$first]),
                     'second_q' => floatval($vals[$second]),
                     'third_q' => floatval($vals[$third]),
                     'geo_layer_name'=>'admin_ones',
                     'results' => $vals[$count - 1], );

      return $quartiles;
  }
  public function updateUserCount($data){
    $this->updateAll(array('AdminOne.users' => 0));

    foreach ($data as $d => $value) {

      $lat = $value['lat'];
      $lng = $value['lng'];

      $ans = $this->find('first',
       array('conditions' => array("st_contains(AdminOne.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
          if(!empty($ans)){
            $this->id = $ans['AdminOne']['id'];
            $this->saveField('users', $this->field('users') + 1);
          }
    }
    return $this->getInterquatile('users');
  }

  public function updateVenueRating($data){
    $this->updateAll(array('AdminOne.venues' => 0),array('AdminOne.review' => 0));
    $arrayName = array();

    foreach ($data as $d => $value) {
      $lat = $value['lat'];
      $lng = $value['lng'];
      $rating = $value['avg'];
      $count = $value['venue_count'];
      $ans = $this->find('first',
       array('conditions' => array("st_contains(AdminOne.geom,ST_GeomFromText('POINT($lng $lat)', 4326))")));
          if(!empty($ans)){
            $this->id = $ans['AdminOne']['id'];
            $this->saveField('venues', $rating);
            $this->saveField('review', $this->field('review') + $count);
          }
    }
    return $this->getInterquatile('venues');
  }
}

?>
