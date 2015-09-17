<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class Venue extends Model
{
    public $hasMany = array(
         'Review' => array(
             'className' => 'Review', ), );

    public function getVenuesWithin($data)
    {
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

    public function getLatLng($data)
    {
        $results = $this->find('all', array(
        'fields' => array('Venue.lat', 'Venue.lng'),
        'conditions' => array('Venue.id' => $data),
      ));
        $latlng = array();
      // print_r($results);
      foreach ($results as $result => $value) {
          $latlng[] = $value['Venue'];
      }
        return $latlng;
    }



    public function findRatingsById($id){
      $venue = $this->findAllById($id);
      $reviews = $venue[0]['Review'];
      $ratings = array('terrible'=>0,'poor'=>0,'average'=>0,'v-good'=>0,'excellent'=>0);
      foreach ($reviews as $review => $value) {
          switch ((int) $value['average_rating']) {
      case 1:
        $ratings['terrible']++;
        break;
      case 2:
      $ratings['poor']++;
        break;
      case 3:
      $ratings['average']++;
        break;
      case 4:
      $ratings['v-good']++;
        break;
      case 5:
      $ratings['excellent']++;
        break;

      default:
        # code...
        break;
    }
      }
      return $ratings;

    }
}
