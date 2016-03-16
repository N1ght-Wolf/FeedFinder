<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class World extends Model
{
    public $useDbConfig = 'postgresql';

    public function getInterquatileUser(){
      $results = $this->find('all',array(
        'fields'=>array('World.users', 'ntile(5) over (order by "users") as quartile'),
        'group'=>'World.users',
        'order'=>'World.users DESC'
      ));
    $quartile = array();
    foreach ($results as $result => $value) {
      $q = $value[0]['quartile'];
        if(!array_key_exists($q,$quartile)){
          $quartile[$q]=$value['World']['users'];
        }
    }
      $quartile['geo_layer_name']='worusers_interq_adminonelds';
      $quartile['geo_layer_style']='venue_sld_style';

      return $quartile;
    }

    public function getInterquatileReview(){
      $results = $this->find('all',array(
        'fields'=>array('World.review', 'ntile(5) over (order by "review") as quartile'),
        'group'=>'World.review',
        'order'=>'World.review DESC'
      ));
    $quartile = array();
    foreach ($results as $result => $value) {
      $q = $value[0]['quartile'];
        if(!array_key_exists($q,$quartile)){
          $quartile[$q]=$value['World']['review'];
        }
    }
      $quartile['geo_layer_name']='worlds';
      $quartile['geo_layer_style']='review_sld_style';
      return $quartile;
    }

    public function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);

        return $lastLog['query'];
    }

    public function updateUserCount($data)
    {
        $saveMany =array();
        $this->updateAll(array('World.users' => 0));
        foreach ($data as $d => $value) {
            $user_count = $value[0]['user_count'];
            $postgre_world_id = $value['Venue']['postgre_world_id'];
            if(isset($user_count) && isset($postgre_world_id))
            {
              $saveMany[]=array('World'=>array('id'=>$postgre_world_id,'users'=>$user_count));
          }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileUser();
    }

    public function updateVenueRating($data){
      $saveMany = array();
      $this->updateAll(array('World.venues' => 0));
      foreach ($data as $d => $value) {
          $avg_rating = $value['0']['avg_rating'];
          $postgre_world_id = $value['Venue']['postgre_world_id'];

          if(isset($avg_rating) && isset($postgre_world_id))
          {
            $saveMany[]=array('World'=>array('id'=>$postgre_world_id,'venues'=>$avg_rating));
          }
      }
      $this->saveMany($saveMany);
      $wms_details = array();
      $wms_details['geo_layer_name']='worlds';
      $wms_details['geo_layer_style']='venue_sld_style';
      return $wms_details;
    }

    public function updateReview($data)
    {
      $saveMany = array();
      $this->updateAll(array('World.review' => 0));
      foreach ($data as $d => $value) {
          $review_count = $value[0]['review_count'];
          $postgre_world_id = $value['Venue']['postgre_world_id'];
          if(isset($review_count) && isset($postgre_world_id))
          {
            $saveMany[]=array('World'=>array('id'=>$postgre_world_id,'review'=>$review_count));
          }
      }
      $this->saveMany($saveMany);
      return $this->getInterquatileReview();
    }

}
