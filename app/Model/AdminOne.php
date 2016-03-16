<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class AdminOne extends Model
{
    public $useDbConfig = 'postgresql';


    public function getInterquatileUser()
    {
        $results = $this->find('all', array(
            'fields' => array('AdminOne.users', 'ntile(5) over (order by "users") as quartile'),
            'group' => 'AdminOne.users',
            'order' => 'AdminOne.users DESC'
            ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['AdminOne']['users'];
            }
        }
        $quartile['geo_layer_name'] = 'admin_ones';
        $quartile['geo_layer_style'] = 'user_sld_style';
        return $quartile;
    }

    public function getInterquatileReview()
    {
        $results = $this->find('all', array(
            'fields' => array('AdminOne.review', 'ntile(5) over (order by "review") as quartile'),
            'group' => 'AdminOne.review',
            'order' => 'AdminOne.review DESC'
            ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['AdminOne']['review'];
            }
        }
        $quartile['geo_layer_name'] = 'admin_ones';
        $quartile['geo_layer_style'] = 'review_sld_style';

        return $quartile;
    }

    public function getInterquatileVenue()
    {
        $results = $this->find('all', array(
            'fields' => array('AdminOne.venues', 'ntile(5) over (order by "venues") as quartile'),
            'group' => 'AdminOne.venues',
            'order' => 'AdminOne.venues DESC'
            ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['AdminOne']['venues'];
            }
        }
        $quartile['geo_layer_name'] = 'admin_ones';
        $quartile['geo_layer_style'] = 'venue_sld_style';
        return $quartile;
    }

    public function updateUserCount($data)
    {
        $saveMany = array();

        $this->updateAll(array('AdminOne.users' => 0));
        foreach ($data as $d => $value) {
            $user_count = $value[0]['count'];
            $postgre_admin_one_id = $value['Venue']['postgre_admin_one_id'];
            if (isset($user_count) && isset($postgre_admin_one_id)) {
                $saveMany[] = array('AdminOne' => array('id' => $postgre_admin_one_id, 'users' => $user_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileUser();
    }

    public function updateVenueCount($data)
    {
        $saveMany = array();

        $this->updateAll(array('AdminOne.venues' => 0));
        foreach ($data as $d => $value) {
            $venue_count = $value[0]['count'];
            $postgre_admin_one_id = $value['Venue']['postgre_admin_one_id'];
            if (isset($venue_count) && isset($postgre_admin_one_id)) {
                $saveMany[] = array('AdminOne' => array('id' => $postgre_admin_one_id, 'venues' => $venue_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileVenue();
    }


    public function updateFriendliness($data)
    {
        $saveMany = array();
        $this->updateAll(array('AdminOne.friendliness' => 0));
        foreach ($data as $d => $value) {
            $avg_rating = $value['0']['count'];
            $postgre_admin_one_id = $value['Venue']['postgre_admin_one_id'];

            if (isset($avg_rating) && isset($postgre_admin_one_id)) {
                $saveMany[] = array('AdminOne' => array('id' => $postgre_admin_one_id, 'friendliness' => $avg_rating));
            }
        }

        $this->saveMany($saveMany);
        $wms_details = array();
        $wms_details['geo_layer_name'] = 'admin_ones';
        $wms_details['geo_layer_style'] = 'friendliness_sld_style';
        return $wms_details;
    }

    public function updateReview($data)
    {
        $saveMany = array();
        $this->updateAll(array('AdminOne.review' => 0));
        foreach ($data as $d => $value) {
            $review_count = $value[0]['count'];
            $postgre_admin_one_id = $value['Venue']['postgre_admin_one_id'];
            if (isset($review_count) && isset($postgre_admin_one_id)) {
                $saveMany[] = array('AdminOne' => array('id' => $postgre_admin_one_id, 'review' => $review_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileReview();
    }

}
