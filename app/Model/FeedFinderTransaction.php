<?php
App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class FeedFinderTransaction extends Model {

  public $belongsTo = array(
         'Venue' => array(
             'type' => 'INNER'
         ),
         'User'=>array('type'=>'INNER')
     );
  // 
  // public $belongsTo = array(
  //   'User' => array(
  //     'className' => 'User'
  //   ),
  //   'Venue' => array(
  //     'className' => 'Venue'
  //   )
  // );
}

?>
