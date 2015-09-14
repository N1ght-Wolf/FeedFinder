<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class Review extends Model
{
    public $belongsTo = array(
         'User' => array('type' => 'INNER'),
         'Venue' => array('type' => 'INNER'), );

    public function getReviewByAddress($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
        'Review.created >=' => $from,
        'Review.created <=' => $to,
        'Venue.show_on_map' => 1);

        $fields = array('Venue.lat','Venue.lng','Venue.iso','COUNT(Venue.address) AS mycount');

        $result = $this->find('all', array(
          'conditions' => $conditions,
          'fields' => $fields,
        'group'=>array('Venue.address') ));
        //  print_r($result);
        return $result;
    }

    public function getReviewByCity($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
        'Review.created >=' => $from,
        'Review.created <=' => $to,
        'Venue.show_on_map' => 1);

        $fields = array('Venue.lat','Venue.lng','Venue.iso','COUNT(Venue.city) AS mycount');

        $result = $this->find('all', array(
          'conditions' => $conditions,
          'fields' => $fields,
        'group'=>array('Venue.city') ));
        //  print_r($result);
        return $result;
    }

    public function getReviewByCountry($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
        'Review.created >=' => $from,
        'Review.created <=' => $to,
        'Venue.show_on_map' => 1);

        $fields = array('Venue.lat','Venue.lng','Venue.iso','COUNT(Venue.iso) AS mycount');

        $result = $this->find('all', array(
          'conditions' => $conditions,
          'fields' => $fields,
        'group'=>array('Venue.iso') ));
        //  print_r($result);
        return $result;
    }

    public function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);

        return $lastLog['query'];
    }

    public function getDistinctUserReview($data)
    {
        $from = $data['from-date'];
        $to = $data['to-date'];

        $conditions = array('Review.created >=' => $from,
                        'Review.created <=' => $to,
                        );

        $field = array('COUNT(DISTINCT Review.user_id) as activeUsers');
        return $this->find('all', array(
        'fields' => $field,
        'conditions' => $conditions,
    ));
    }

    public function getVenueRating($data){
      $from = $data['form']['from'];
      $to = $data['form']['to'];
      $field = array('COUNT(Venue.iso) AS mycount',
                     'round(AVG(Review.q1),1) as q1' ,
                     'round(AVG(Review.q2),1) as q2' ,
                     'round(AVG(Review.q3),1) as q3' ,
                     'round(AVG(Review.q4),1) as q4','Venue.lat','Venue.lng');
      $group = array($data['group']);
      $conditions = array('Review.enabled'=>1,'Review.created >=' => $from,
                      'Review.created <=' => $to,);
      $results =  $this->find('all',array(
        'fields'=>$field,
        'group'=>$group,
        'conditions'=>$conditions
      ));
      $return = array();
      foreach ($results as $result => $value) {
        $q1 = $value[0]['q1'];
        $q2 = $value[0]['q2'];
        $q3 = $value[0]['q3'];
        $q4 = $value[0]['q4'];
        $avg = ($q1 + $q2+ $q3+ $q4)/4;
        $lat = $value['Venue']['lat'];
        $lng = $value['Venue']['lng'];
        $venue_count = $value[0]['mycount'];
        $return[] =array('avg'=>$avg, 'lat'=>$lat, 'lng'=>$lng,'venue_count'=>$venue_count);
      }
      return $return;
    }
    public function getAverageVenueRating($id){

      $field = array(
                     'round(AVG(Review.q1),1) as q1' ,
                     'round(AVG(Review.q2),1) as q2' ,
                     'round(AVG(Review.q3),1) as q3' ,
                     'round(AVG(Review.q4),1) as q4'
                   );
      $conditions = array('Review.venue_id'=> $id);
      $results =  $this->find('all',array(
        'fields'=>$field,
        'conditions'=>$conditions
      ));


      return $results;
    }

    public function getReviewPaginated($data){
      $id = $data['id'];
      $start = $data['start'];
      $start = ($start -1)*5;
      $end = $data['end'];
      return $this->find('all',array(
        'conditions'=>array('Venue.id'=>$id),
        'offset'=>$start,
        'limit'=>$end
    ));
    }
}
