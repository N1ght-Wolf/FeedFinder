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
class Venue extends Model {
  public $belongsTo = array('FeedFinderTransaction');


}

?>
