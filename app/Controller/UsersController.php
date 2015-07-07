<?php
App::uses('AppController', 'Controller');

/**
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UsersController extends AppController {

  public $helpers = array('Html', 'Form','Session');
  public $components = array('Session','Highcharts.Highcharts');



  public function index () {
    //pass data from controller to the view
    $this->set('users', $this->User->find('all', array('conditions'=> $arrayName = array('User.role' => 'admin' ))));
  }

  public function view ($id=null){
    //check if the id is null
    if(!$id){
      //throw Exception
      throw new NotFoundException(__('invalid post'));
    }
    //otherwise find the user by id
    $user = $this->User->findById($id);

    //check user returned is not null
    if(!$user){
      //throw Exception
      throw new NotFoundException(__('invalid post'));
    }
    //send back a single user
    $this->set('user',$user);
  }

  public function userCount(){
    $this->set('count',$this->User->find('count', array('conditions'=>array('User.role'=>'admin'))));
  }


  }
?>
