<?php

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 */
class UkAdminThree extends Model
{
    public $useDbConfig = 'postgresql';


    public function getInterquatileUser()
    {
        $results = $this->find('all', array(
            'fields' => array('UkAdminThree.users', 'ntile(5) over (order by "users") as quartile'),
            'group' => 'UkAdminThree.users',
            'order' => 'UkAdminThree.users DESC',
        ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['UkAdminThree']['users'];
            }
        }
        $quartile['geo_layer_name'] = 'uk_admin_threes';
        $quartile['geo_layer_style'] = 'venue_sld_style';

        return $quartile;
    }

    public function getInterquatileReview()
    {
        $results = $this->find('all', array(
            'fields' => array('UkAdminThree.review', 'ntile(5) over (order by "review") as quartile'),
            'group' => 'UkAdminThree.review',
            'order' => 'UkAdminThree.review DESC',
        ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['UkAdminThree']['review'];
            }
        }
        $quartile['geo_layer_name'] = 'uk_admin_threes';
        $quartile['geo_layer_style'] = 'review_sld_style';

        return $quartile;
    }

    public function getInterquatileVenue()
    {
        $results = $this->find('all', array(
            'fields' => array('UkAdminThree.venues', 'ntile(5) over (order by "venues") as quartile'),
            'group' => 'UkAdminThree.venues',
            'order' => 'UkAdminThree.venues DESC'
        ));
        $quartile = array();
        foreach ($results as $result => $value) {
            $q = $value[0]['quartile'];
            if (!array_key_exists($q, $quartile)) {
                $quartile[$q] = $value['UkAdminThree']['venues'];
            }
        }
        $quartile['geo_layer_name'] = 'uk_admin_threes';
        $quartile['geo_layer_style'] = 'review_sld_style';
        return $quartile;
    }

    public function updateUserCount($data)
    {
        $saveMany = array();
        $this->updateAll(array('UkAdminThree.users' => 0));

        foreach ($data as $d => $value) {
            $user_count = $value[0]['count'];
            $postgre_uk_id = $value['Venue']['postgre_uk_id'];
            if (isset($user_count) && isset($postgre_uk_id)) {
                $saveMany[] = array('UkAdminThree' => array('id' => $postgre_uk_id, 'users' => $user_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileUser();
    }

    public function updateVenueRating($data)
    {
        $saveMany = array();
        $this->updateAll(array('UkAdminThree.venues' => 0));
        foreach ($data as $d => $value) {
            $avg_rating = $value['0']['avg_rating'];
            $postgre_uk_id = $value['Venue']['postgre_uk_id'];

            if (isset($avg_rating) && isset($postgre_uk_id)) {
                $saveMany[] = array('UkAdminThree' => array('id' => $postgre_uk_id, 'venues' => $avg_rating));
            }
        }

        $this->saveMany($saveMany);
        $wms_details = array();
        $wms_details['geo_layer_name'] = 'uk_admin_threes';
        $wms_details['geo_layer_style'] = 'venue_sld_style';
        return $wms_details;
    }

    public function updateVenueCount($data)
    {
        $saveMany = array();

        $this->updateAll(array('UkAdminThree.venues' => 0));
        foreach ($data as $d => $value) {
            $venue_count = $value[0]['count'];
            $postgre_uk_id = $value['Venue']['postgre_uk_id'];
            if (isset($venue_count) && isset($postgre_uk_id)) {
                $saveMany[] = array('UkAdminThree' => array('id' => $postgre_uk_id, 'venues' => $venue_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileVenue();
    }

    public function updateFriendliness($data)
    {
        $saveMany = array();
        $this->updateAll(array('UkAdminThree.friendliness' => 0));
        foreach ($data as $d => $value) {
            $avg_rating = $value['0']['count'];
            $postgre_uk_id = $value['Venue']['postgre_uk_id'];

            if (isset($avg_rating) && isset($postgre_uk_id)) {
                $saveMany[] = array('UkAdminThree' => array('id' => $postgre_uk_id, 'friendliness' => $avg_rating));
            }
        }

        $this->saveMany($saveMany);
        $wms_details = array();
        $wms_details['geo_layer_name'] = 'admin_ones';
        $wms_details['geo_layer_style'] = 'venue_sld_style';
        return $wms_details;
    }


    public function updateReview($data)
    {
        $saveMany = array();
        $this->updateAll(array('UkAdminThree.review' => 0));
        foreach ($data as $d => $value) {
            $review_count = $value[0]['count'];
            $postgre_uk_id = $value['Venue']['postgre_uk_id'];
            if (isset($review_count) && isset($postgre_uk_id)) {
                $saveMany[] = array('UkAdminThree' => array('id' => $postgre_uk_id, 'review' => $review_count));
            }
        }
        $this->saveMany($saveMany);
        return $this->getInterquatileReview();
    }

}
