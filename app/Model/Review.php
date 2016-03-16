<?php

App::uses('Model', 'Model');

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

    public function getVenueAndReviewCount($data)
    {
        $conditions = array('Review.created >=' => $data['from'],
            'Review.created <=' => $data['to'],
            'Venue.flag' => 0,
            'Review.flag' => 0,
        );


        $group = array('Review.venue_id');
        return $this->find('all', array(
            'conditions' => $conditions,
            'group' => array('Review.venue_id')
        ));
    }

    public function getUsersFirstLocation($data, $group, $fields)
    {
        $conditions = array(
            'Review.created >=' => $data['from'],
            'Review.created <=' => $data['to'],
            'Review.flag' => 0,
        );

        $results = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => $fields,
            'group' => $group,
        ));

        return $results;
    }

    public function reviewsFromVenue($id,$from,$to){
        return $this->find('count',array(
            'conditions'=> array(
                'Venue.postgre_admin_one_id'=>$id,
                'Review.created >=' => $from,
                'Review.created <=' => $to,
                )
            ));
    }

    public function averageFriendlinessVenue($id, $from, $to){
        return $this->find('all',array(
            'fields'=>array('Round(AVG(Review.q1+Review.q2+Review.q3+Review.q4)/4,1) as count'),
            'conditions'=> array(
                'Venue.postgre_admin_one_id'=>$id,
                'Review.created >=' => $from,
                'Review.created <=' => $to,
                )
            ));
    }

    public function getReviewUk($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.flag' => 0,
            'Venue.flag' => 0,
        );

        $fields = array(
            'Venue.latitude',
            'Venue.longitude',
            'Venue.postgre_uk_id',
            'COUNT(Venue.postgre_uk_id) AS count'
        );

        $result = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => $fields,
            'group' => array('Venue.postgre_uk_id'),));
        return $result;
    }

    public function getReviewAdminOne($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.flag' => 0,
            'Venue.flag' => 0,
        );

        $fields = array(
            'Venue.latitude',
            'Venue.longitude',
            'Venue.postgre_admin_one_id',
            'COUNT(Venue.postgre_admin_one_id) AS count'
        );
        $result = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => $fields,
            'group' => array('Venue.postgre_admin_one_id'),));
        return $result;
    }

    public function getReviewWorld($data)
    {
        $from = $data['from'];
        $to = $data['to'];

        $conditions = array(
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.flag' => 0,
            'Venue.flag' => 0,
        );

        $fields = array(
            'Venue.postgre_world_id',
            'COUNT(Venue.postgre_world_id) AS review_count'
        );

        $result = $this->find('all', array(
            'conditions' => $conditions,
            'fields' => $fields,
            'group' => array('Venue.postgre_world_id'),));

        return $result;
    }

    public function getLastQuery()
    {
        $dbo = $this->getDatasource();
        $logs = $dbo->getLog();
        $lastLog = end($logs['log']);

        return $lastLog['query'];
    }

    public function getDistinctUserReview($data)
    {
        $from = $data['from-date'];
        $to = $data['to-date'];

        $conditions = array('Review.created >=' => $from,
            'Review.created <=' => $to,
        );

        $field = array('COUNT(DISTINCT Review.user_id) as activeUsers');

        return $this->find('all', array(
            'fields' => $field,
            'conditions' => $conditions,
        ));
    }

    public function getVenueRatingWorld($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $group = array('Venue.postgre_world_id');
        $field = array(
            'AVG(Review.average_rating) as avg_rating',
            'Venue.postgre_world_id',
        );

        $conditions = array(
            'Review.flag' => 1,
            'Review.created >=' => $from,
            'Review.created <=' => $to,
        );
        $results = $this->find('all', array(
            'fields' => $field,
            'group' => $group,
            'conditions' => $conditions,
        ));
        return $results;
    }

    public function getVenueRatingAdminOne($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $group = array('Venue.postgre_admin_one_id');
        $field = array(
            'Round(AVG(Review.q1+Review.q2+Review.q3+Review.q4)/4,1) as count',
            'Venue.postgre_admin_one_id',
            'Venue.latitude',
            'Venue.longitude'
        );

        $conditions = array(
            'Review.flag' => 0,
            'Review.created >=' => $from,
            'Review.created <=' => $to,
        );
        $results = $this->find('all', array(
            'fields' => $field,
            'group' => $group,
            'conditions' => $conditions,
        ));

        return $results;
    }

    public function getVenueRatingUk($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $group = array('Venue.postgre_uk_id');
        $field = array(
            'Round(AVG(Review.q1+Review.q2+Review.q3+Review.q4)/4,1) as count',
 'Venue.postgre_admin_one_id',
            'Venue.latitude',
            'Venue.longitude'        );

        $conditions = array(
            'Review.flag' => 0,
            'Review.created >=' => $from,
            'Review.created <=' => $to,
        );
        $results = $this->find('all', array(
            'fields' => $field,
            'group' => $group,
            'conditions' => $conditions,
        ));

        return $results;
    }

    public function getVenueAttributeRating($data)
    {
        $id = $data['id'];
        $from = $data['from'];
        $to = $data['to'];

        $field = array(
            'round(AVG(Review.q1),1) as comfort',
            'round(AVG(Review.q2),1) as cleanliness',
            'round(AVG(Review.q3),1) as privacy',
            'round(AVG(Review.q4),1) as baby_fac',
            'round(AVG(Review.q5),1) as avg_spend',
        );
        $conditions = array(
            'Review.venue_id' => $id,
            'Review.created >=' => $from,
            'Review.created <=' => $to,
        );
        $results = $this->find('all', array(
            'fields' => $field,
            'conditions' => $conditions,
        ));

        return $results;
    }

    public function getVenueAvgRating($id)
    {
        $field = array('AVG(Review.average_rating) AS average_rating');
        $condition = array('Review.venue_id' => $id);

        return $this->find('first', array(
            'fields' => $field,
            'conditions' => $condition,
        ));
    }

    public function getReviewPaginated($data)
    {
        //the venue id
        $id = $data['id'];
        //start index
        $start = $data['start'];
        $start = ($start - 1) * 5;
        //end index
        $end = $data['end'];
        //order for sorting the result
        $order = $data['order'];
        //the date bounds from and to
        $from = $data['from'];
        $to = $data['to'];
        //used for deciding what review rating type we want
        //e.g. average, excellent etc
        $rating_low_bound = $data['rating_low'];
        $rating_high_bound = $data['rating_high'];
        //if the rating bounds are both zero the do normal query request
        if ($rating_high_bound == 0 && $rating_low_bound == 0) {
            $conditions = array('Review.created >=' => $from,
                'Review.created <=' => $to,
                'Venue.id' => $id,
            );
        } else {
            // else use the bounds to select review type
            $conditions = array('Review.created >=' => $from,
                'Review.created <=' => $to,
                'Venue.id' => $id,
                'Review.average_rating >=' => $rating_low_bound,
                'Review.average_rating <' => $rating_high_bound,
            );
        }

        return $this->find('all', array(
            'conditions' => $conditions,
            'offset' => $start,
            'limit' => $end,
            'order' => array('Review.created ' . $order),
        ));
    }

    public function getVenueReviews($data)
    {
        $from = $data['from'];
        $to = $data['to'];
        $id = $data['id'];
        $conditions = array('Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.venue_id' => $id,
        );

        return $this->find('all', array(
            'conditions' => $conditions,
        ));
    }

    public function getReviewCountByPerf($data, $low_end, $high_end)
    {
        $from = $data['from'];
        $to = $data['to'];
        $id = $data['id'];
        $conditions = array(
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.venue_id' => $id,
            'Review.average_rating >=' => $low_end,
            'Review.average_rating <' => $high_end,
        );

        return $this->find('all', array('conditions' => $conditions));
    }

    public function getVenuePerformance($data, $low_end, $high_end)
    {
        $from = $data['from'];
        $to = $data['to'];
        $id = $data['id'];
        $conditions = array(
            'Review.created >=' => $from,
            'Review.created <=' => $to,
            'Review.venue_id' => $id,
            'Review.average_rating >=' => $low_end,
            'Review.average_rating <' => $high_end,
        );
        return $this->find('count', array('conditions' => $conditions));
    }
}
