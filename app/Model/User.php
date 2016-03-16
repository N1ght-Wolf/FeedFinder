<?php
App::uses('Model', 'Model');
App::uses('Review', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends Model {
    public $useDbConfig = 'users';

  var $name = 'User';
  var $hasMany = 'Review';


  public function getUserFirstLocation($data){
    $from = $data['from'];
    $to = $data['to'];

    $conditions = array('User.created >=' => $from,
                        'User.created <=' => $to);
    $fields = array('Review.venue_id','MIN(Review.created)');
    $group = array('Review.user_id');

    $results = $this->find('all', array(
      'conditions'=>$conditions,
      'fields'=>$fields,
      'group'=>$group
    ));

    return $result;
  }


  public function getTotalUsers($data){
    $from = $data['from'];
    $to = $data['to'];

    $conditions = array('User.created >=' => $from,
                        'User.created <=' => $to);

    return $this->find('count',array('conditions' => $conditions));

  }

  public function getActiveUsers($data){
    //an active user is a user that has at least on review
    $from = $data['from-date'];
    $to = $data['to-date'];
    $action = $data['action'];
    $Review = new Review();

    return $Review->getDistinctUserReview($data);
  }

  public function getUserGraphData($data){
    $from = $data['from-date'];
    $to = $data['to-date'];

    $conditions = array('User.created >=' => $from,
                        'User.created <=' => $to);
    
    $fields = array('UNIX_TIMESTAMP(User.created) * 1000 AS timestamp','COUNT(User.created) AS mycount');
    $group = array('YEAR(User.created)', 'MONTH(User.created)', 'DAY(User.created)');

    $results = $this->find('all',array(
      'conditions'=>$conditions,
      'fields'=>$fields,
      'group'=>$group
    ));

    $final_array = array();

    foreach ($results as $result => $value) {
      $timestamp = floatval($value[0]['timestamp']);
      $count = intval($value[0]['mycount']);
      $some_arr = array($timestamp,$count);
      $final_array[] = $some_arr;
    }
    return $final_array;

  }

}

?>
