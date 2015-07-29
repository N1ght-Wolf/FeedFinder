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
class Review extends Model {

  public $belongsTo = array(¢
         'User'=>array('type'=>'INNER')
     );
}

?>
