<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
/**
 * Students Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class StudentsController extends AppController
{
     public function initialize()
    {
		 $this->Certificates = TableRegistry::get('Certificates');
		 parent::initialize();
		 $this->loadComponent('Flash');
		 $this->Auth->allow(array('login','dashboard','logout'));
	}

	
	  public function index(){
		   $this->Users 	= TableRegistry::get('Users');
		   $this->viewBuilder()->layout('student');
	  }
}
