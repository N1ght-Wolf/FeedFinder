<?php

App::uses('Model', 'Model');
App::uses('County', 'Model');
App::uses('Review', 'Model');


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
            'className' => 'Review',),);

    public function route($query)
    {
        $timeRange = $this->getVenuesInTimeRange($query);
        $interq = $this->calculateInterquartile($query);
        $result = array("time_range" => $timeRange,
            "interq" => $interq);
        return $result;
    }

    /*
    gets all the venues that fall within a specific time raneg
    */
    public function getVenuesInTimeRange($query)
    {
        $from = $query['time']['range']['from'];
        //$to = $query['time']['range']['to'];
        //print_r(date('Y-m-d H:i:s', strtotime($from)));
        $conditions = array(
            'Venue.flag' => 0,
            'Venue.created >=' => date('Y-m-d H:i:s', strtotime($from)),
            'Venue.created <=' => date('Y-m-d H:i:s', strtotime('tomorrow -1 second')),
            'Venue.county_id  !=' => -1,
//            'Venue.soa_id IS NOT NULL'
        );

        return $this->find('all', array(
            'fields' => array(
                'Venue.name,
                Venue.address,
                Venue.city,
                Venue.country,
                Venue.postcode, 
                Venue.created,
                Venue.county_id,
                Venue.latitude, Venue.longitude'),
            'conditions' => $conditions
        ));
    }

    public function venuesWithId($id, $from, $to)
    {
        return $this->find('count', array(
            'conditions' => array('
                Venue.postgre_admin_one_id' => $id,
                'Venue.created >=' => $from,
                'Venue.created <=' => $to,
            )));
    }

    public function calculateInterquartile($query)
    {
        $Model = $AnotherModel = ClassRegistry::init($query['explore']['pg_table']);
        $quartile = array();
        $category = strtolower($query['category']['name']);
        $attr_name = $query['time']['attr_name'];
        //e.g. venue_all
        $column = $category . $attr_name;
        $results = $Model->query("select $column, ntile(5) over (order by $column) as quartile from counties group by $column order by $column desc");
//        echo "<pre>";
//        print_r($results["0"]["0"][$column]);
//        echo "</pre>";
//        foreach ($results as $result => $value) {
//            $q = $value['0']['quartile'];
//            if (!array_key_exists($q, $quartile)) {
//                $quartile[$q] = $value['0'][$column];
//            }
//        }

        $style = "feedfinder_" . strtolower($query["category"]["name"]) . $query["time"]["attr_name"] . "_sld";

        return array("quartiles" => $results["0"]["0"][$column], "style" => $style, "layer" => $Model->table);
    }
    /***Functions used by the Venues page **/

    public function getVenueInfo($query)
    {
        return array(
            'venue_reviews' => $this->getVenueReviews($query),
            'venue_address' => $this->getVenueAddress($query),
          //  'venue_ratings' => $this->getVenueRatings($query)
        );
    }

    public function getVenueReviews($query){
        $Review = new Review();
        $Review->Behaviors->load('Containable');
        $Review->contain('User');
        return $Review->find('all',array(
            'conditions' => array(
                'Review.venue_id' => $query['id'],
                'Review.created >=' => $query['from'],
                'Review.created <=' => $query['to'],
            )
        ));
    }

    public function getVenueAddress($query){
        $this->Behaviors->load('Containable');
        $this->contain();

        return $this->find('first',array(
            'conditions' => array('Venue.id' => $query['id'])
        ));
    }
}






