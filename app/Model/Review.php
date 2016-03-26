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
        $County = new County();
        $quartile = array();
        $results = $County->find('all', array(
            'fields' => array('County.review_all', 'ntile(5) over (order by "review_all") as quartile'),
            'group' => 'County.review_all',
            'order' => 'County.review_all DESC',
        ));

        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['County']['review_all'];
            }
        }
        return $quartile;
    }


}
