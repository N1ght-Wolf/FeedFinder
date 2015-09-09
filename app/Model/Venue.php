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
class Venue extends Model {
  public $hasMany = array(
         'Review' => array(
             'className' => 'Review'));


  public function getVenuesWithin($data){
    $from = $data['from'];
    $to = $data['to'];
    $group = array('Venue.iso');
    $conditions = array('Venue.show_on_map' => 1,
                        'Venue.created >=' => $from,
                        'Venue.created <=' => $to,
                        );

    // $fields = array('Venue.lat','Venue.lng');

    return $this->find('all', array(
      'conditions' => $conditions,
      // 'fields' => $fields
    ));

  }

  public function getLatLng($data){
      $results =  $this->find('all', array(
        'fields'=>array('Venue.lat','Venue.lng'),
        'conditions'=>array('Venue.id'=>$data)
      ));
      $latlng = array();
      // print_r($results);
      foreach ($results as $result => $value)
        {
          $latlng[]= $value['Venue'];
        }
        return $latlng;
     }

}

?>
