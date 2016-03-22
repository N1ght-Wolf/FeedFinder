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

    public function route($query){
        return $this->getVenuesInTimeRange($query);
    }
    /*
    gets all the venues that fall within a specific time raneg
    */
    public function getVenuesInTimeRange($query)
    {
        $from = $query['time']['range']['from'];
        $to = $query['time']['range']['to'];

        $conditions = array(
            'Venue.flag' => 0,
            'Venue.created >=' => $from,
            'Venue.created <=' => $to
            );

        return $this->find('all', array(
            'fields'=>array(
                'Venue.name,
                Venue.address,
                Venue.created,
                Venue.city,
                Venue.country,
                Venue.postcode, Venue.latitude, Venue.longitude'
                ),
            'conditions' => $conditions
            ));
    }

    public function venuesWithId($id, $from, $to){
        return $this->find('count',array(
            'conditions'=> array('
                Venue.postgre_admin_one_id'=>$id,
                'Venue.created >=' => $from,
                'Venue.created <=' => $to,
                )));
    }


}
