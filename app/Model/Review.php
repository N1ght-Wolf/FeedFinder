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
        $quartile = array();
        $category =strtolower($query['category']['name']);
        $attr_name = $query['time']['attr_name'];
        //e.g. venue_all
        $column = $category.$attr_name;
        $results = $Model->query("select $column, ntile(5) over (order by $column) as quartile from counties group by $column order by $column desc");
        
        foreach ($results as $result => $value) {
             $q = $value['0']['quartile'];
            if (!array_key_exists($q, $quartile)){
                $quartile[$q] = $value['0'][$column];
            }
        }
        
        $style='feedfinder_'.strtolower($query['category']['name']).$query['time']['attr_name'].'_sld';

        return array('quartiles'=>$quartile,'style'=>$style,'layer'=>$Model->table);
    }

}
