<?php

App::uses('AppController', 'Controller');
App::uses('CakeTime', 'Utility');
App::uses('CakeEmail', 'Network/Email');
App::import('vendor', 'geoPHP/geoPHP.inc');

/**
 * Static content controller.
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class DashboardsController extends AppController
{
    public $components = array('Session', 'RequestHandler');
    public $helpers = array('Session', 'Html', 'Form', 'Js' => array('jquery'));
    public $uses = array(
        //Feed finder tables
        'Venue',
        'Review',
        'User',
        //postgre geospatial tables
        'AdminOne',
        'UkAdminThree',
        'World'
    );

    // index page action
    public function index()
    {
    }


    public function send_email()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $Email = new CakeEmail('gmail');
            $Email->to('feedfinder2013@gmail.com');
            $Email->subject('Automagically generated email');
            $Email->replyTo('the_mail_you_want_to_receive_replies@yourdomain.com');
            $Email->from($this->request->data('email'));
            $Email->message($this->request->data('Message'));
            $Email->send();
        }
    }

    /*
    Called by venuesSubmit in stats.js
    collect reviews count group by postgre_uk_id (Super Output UK)
    update the count for reviews column in postgre uk_admin_three
    after updating, the quintile is returned
    */
    public function review_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //get review and group them by their postgre_uk_id giving
            //us count for each area
            $results = $this->Review->getReviewUk($this->request->query);
            //update review column in postgre_uk_id with acquired count
            //quintile is returned
            $wms_details = $this->UkAdminThree->updateReview($results);
            //return as json to javascript (stats.js)
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            //return as json to javascript (stats.js)
            echo json_encode($json);
        }
    }

    /*
    Called by venuesSubmit in stats.js
    collect reviews count group by postgre_admin_one_id (by county or city)
    update the count for reviews column in postgre uk_admin_three
    after updating, the quintile is returned
    */
    public function review_interq_adminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //get review and group them by their postgre_admin_one_id giving
            //us count for each city or county
            $results = $this->Review->getReviewAdminOne($this->request->query);
            //update review column in postgre_admin_one_id with acquired count
            //quintile is returned
            $wms_details = $this->AdminOne->updateReview($results);
            //return as json to javascript (stats.js)
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            echo json_encode($json);
        }
    }

    /*
    Called by venuesSubmit in stats.js
    collect reviews count group by postgre_world_id (by county or city)
    update the count for reviews column in postgre_world_id
    after updating, the quintile is returned
    */
    public function review_interq_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //get review and group them by their postgre_world_id giving
            //us count for each country
            $results = $this->Review->getReviewWorld($this->request->query);
            //update review column in postgre_world_id with acquired count
            //quintile is returned
            $quartiles = $this->World->updateReview($results);
            //return as json to javascript (stats.js)
            echo json_encode($quartiles);
        }
    }

    /*
    called by venueSubmit in stats.js
    collect venues that have been added within a timespan
    */
    public function get_venue_in_timepsan()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //gets venue within a certain timespan
            $results = $this->Venue->getVenuesWithin($this->request->query);
            //result encoded as json back to stats.js
            echo json_encode($results);
        }
    }
    /*
     * Get the amount of reviews within a certain timepsan
     * */
    public function get_review_in_timespan(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
//            $results = $this->Review->getReviewsWithin($this->request->query);
            //result encoded as json back to stats.js
//            echo json_encode($results);
        }
    }

    /*
    called by venueSubmit in stats.js
    collect venues that have been added within a timespan
    */
    public function get_users_in_timepsan()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $group = array('Venue.postgre_uk_id');
            $fields = array(
                'COUNT(Venue.postgre_uk_id) as user_count',
                'MIN(Review.created)',
                'Venue.lat',
                'Venue.lng');

            $results = $this->Review->getUsersFirstLocation(
                $this->request->query,
                $group,
                $fields
            );
            echo json_encode($results);
        }
    }

    /*
    called by venueSubmit in stats.js
    collect the avg rating for each country
    update the venue rating in postgre_world for each
    */
    public function average_rating_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //  query params
            $data = $this->request->query;
            //collect avg rating for each country
            $venue_ratings = $this->Review->getVenueRatingWorld($data);
            print_r($venue_ratings);
            //update venue rating for each country
            $wms_details = $this->World->updateVenueRating($venue_ratings);
            //return wms detail for rendering
            //choropleth map (for geoserver)
            echo json_encode($wms_details);
        }
    }

    /*
    called by venueSubmit in stats.js
    collect the avg rating for each county/city
    update the venue rating in postgre_admin one for each city/county
    */
    public function friendliness_interq_admin_one()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //  query params
            $data = $this->request->query;
            //collect avg rating for each county
            $results = $this->Review->getVenueRatingAdminOne($data);

            //uodate venue rating for each county/city
            $wms_details = $this->AdminOne->updateFriendliness($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);

            echo json_encode($json);
        }
    }

    public function friendliness_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            //  query params
            $data = $this->request->query;
            //collect avg rating for each county
            $results = $this->Review->getVenueRatingUk($data);
            //uodate venue rating for each county/city
            $wms_details = $this->UkAdminThree->updateFriendliness($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            echo json_encode($json);
        }
    }


    public function average_rating_uk()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $data = $this->request->query;
            $venue_ratings = $this->Review->getVenueRatingUk($data);
            $wms_details = $this->UkAdminThree->updateVenueRating($venue_ratings);
            echo json_encode($wms_details);
        }
    }

    public function users_interq_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $group = array('Venue.postgre_world_id');
            $fields = array(
                'COUNT(Venue.postgre_world_id) as user_count',
                'MIN(Review.created)',
                'Venue.postgre_world_id',
                'Review.user_id');

            $results = $this->Review->getUsersFirstLocation(
                $this->request->query,
                $group,
                $fields
            );
            $quartiles = $this->World->updateUserCount($results);
            echo json_encode($quartiles);
        }
    }

    public function users_interq_adminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $group = array('Venue.postgre_admin_one_id');
            $fields = array(
                'COUNT(Venue.postgre_admin_one_id) as count',
                'MIN(Review.created)',
                'Venue.postgre_admin_one_id',
                'Review.user_id',
                'Venue.lat','Venue.lng');
            $results = $this->Review->getUsersFirstLocation(
                $this->request->query,
                $group,
                $fields
            );
            $result = $this->User->find('all',array('conditions'=>array('User.id'=>37)));
            print_r($result);
            $wms_details = $this->AdminOne->updateUserCount($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            echo json_encode($json);
        }
    }

    public function users_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $group = array('Venue.postgre_uk_id');
            $fields = array(
                'COUNT(Venue.postgre_uk_id) as count',
                'MIN(Review.created)',
                'Venue.postgre_uk_id',
                'Review.user_id',
                'Venue.lat','Venue.lng');
            $results = $this->Review->getUsersFirstLocation(
                $this->request->query,
                $group,
                $fields
            );

            $wms_details = $this->UkAdminThree->updateUserCount($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);

            echo json_encode($json);
        }
    }

    public function venues_interq_admin_one(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Venue->getVenuesAdminOne($this->request->query);
            $wms_details = $this->AdminOne->updateVenueCount($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            echo json_encode($json);
        }
    }
    public function venues_interq_ukadminthree(){
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Venue->getVenuesUkAdminThree($this->request->query);
            $wms_details = $this->UkAdminThree->updateVenueCount($results);
            $json = array('cluster_data'=>$results,'wms_details'=>$wms_details);
            echo json_encode($json);
        }
    }


}
