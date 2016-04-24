<?php

App::uses('Model', 'Model');
App::uses('County', 'Model');



/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class Review extends Model
{
    public $belongsTo = array(
        'User', 
        'Venue');

    public function route($query){
        
        return $this->calculateInterquartile($query);
    }

 
    public function calculateInterquartile($query){
        $Model = $AnotherModel = ClassRegistry::init($query['explore']['pg_table']);
        $quartile = array("1"=>0,"2"=>0,3=>0,4=>0,5=>0);
        $category = strtolower($query['category']['name']);
        $attr_name = $query['time']['attr_name'];
        //e.g. venue_all
        $column = $category . $attr_name;
        $results = $Model->query("select $column, ntile(5) over (order by $column) as quartile from counties WHERE $column > 0 group by $column order by $column desc");
//        echo "<pre>";
//        print_r($results["0"]["0"][$column]);
//        echo "</pre>";

        foreach ($results as $result => $value) {
            $q = $value['0']['quartile'];
            //  if (!array_key_exists($q, $quartile)) {
            $quartile[$q] = $value['0'][$column];
            // }
        }

        $style = "feedfinder_map_style";

        return array("quartiles" => $quartile, "style" => $style, "layer" => $Model->table);
    }

    public function getVenueReviews($query){
        $id = $query['id'];
        print_r($id);
        return $this->find('all',array(
            'conditions'=>array('Review.venue_id'=>$id)
            ));
    }

}
