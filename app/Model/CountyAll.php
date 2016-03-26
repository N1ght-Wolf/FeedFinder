<?php

App::uses('Model', 'Model');
App::uses('Review', 'Model');
App::uses('Venue', 'Model');
App::uses('User', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class CountyAll extends Model
{
    public $useDbConfig = 'postgresql';

    public function updateUser(){
        $results = $this->find('first');
        // $User = new User();
        // $fields = array('Review.venue_id','MIN(Review.created)');
        // $group = array('Review.user_id');

        // $results = $User->find('all', array(
        //   'fields'=>$fields,
        //   'group'=>$group
        // ));
        print_r($results);
    }

    public function updateReview(){

    }

    public function updateVenue(){

    }

    public function updateFriendliness(){

    }
}
