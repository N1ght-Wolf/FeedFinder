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
class FeedFinderTransactionsController extends AppController
{
    public $components = array('Session','RequestHandler');
    public $helpers = array('Session', 'Html', 'Form','Js' => array('jquery'));
    public $uses = array('Venue','Review','FeedFinderTransaction','UserLookupTable',
                         'World','AdminOne','UkAdminThree','User', );

    public function index()
    {
    }

    public function stats()
    {
    }

    public function about()
    {
    }
    public function contact()
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
                          $Email->from ($this->request->data('email'));
                          $Email->message($this->request->data('Message'));
                          $Email->send();
        }
    }

    public function review_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewUk($this->request->query);
            $quartiles = $this->UkAdminThree->updateReview($results);
            echo json_encode($quartiles);
        }
    }

    public function review_interq_adminone()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewAdminOne($this->request->query);
            $quartiles = $this->AdminOne->updateReview($results);
            echo json_encode($quartiles);
        }
    }

    public function review_interq_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Review->getReviewWorld($this->request->query);
            $quartiles = $this->World->updateReview($results);
            echo json_encode($quartiles);
        }
    }

    public function get_stats_venues()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $results = $this->Venue->getVenuesWithin($this->request->query);
            echo json_encode($results);
        }
    }

    public function average_rating_world()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $data = $this->request->query;
            $venue_ratings = $this->Review->getVenueRatingWorld($data);
            $wms_details = $this->World->updateVenueRating($venue_ratings);
            echo json_encode($wms_details);
        }
    }

    public function average_rating_admin_one()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $data = $this->request->query;
            $venue_ratings = $this->Review->getVenueRatingAdminOne($data);
            $wms_details = $this->AdminOne->updateVenueRating($venue_ratings);
            echo json_encode($wms_details);
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
          'Review.user_id', );

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
            'COUNT(Venue.postgre_admin_one_id) as user_count',
            'MIN(Review.created)',
            'Venue.postgre_admin_one_id',
            'Review.user_id', );

            $results = $this->Review->getUsersFirstLocation(
          $this->request->query,
          $group,
          $fields
        );
            $quartiles = $this->AdminOne->updateUserCount($results);
            echo json_encode($quartiles);
        }
    }
    public function users_interq_ukadminthree()
    {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $group = array('Venue.postgre_uk_id');
            $fields = array(
              'COUNT(Venue.postgre_uk_id) as user_count',
              'MIN(Review.created)',
              'Venue.postgre_uk_id',
              'Review.user_id', );

            $results = $this->Review->getUsersFirstLocation(
          $this->request->query,
          $group,
          $fields
          );

            $quartiles = $this->UkAdminThree->updateUserCount($results);
            echo json_encode($quartiles);
        }
    }
}
