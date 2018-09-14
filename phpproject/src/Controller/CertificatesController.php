<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Universities Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class CertificatesController extends AppController
{
     public function initialize()
    {
		$this->Users = TableRegistry::get('Users');
		$this->Certificates = TableRegistry::get('Certificates');
		 parent::initialize();
		 $this->loadComponent('Flash');
		 $this->loadComponent('Paginator');
	}

    public function lists()
    {
		$collegeId = $this->Auth->user('id') ;
		
		$roleId = $this->Auth->user('role_id') ;
         if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
		 } else {
		    $this->viewBuilder()->layout('main');
		 }
		$collegeCode = $this->Auth->user('username') ;
		$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'0','Certificates.college_id'=>$collegeCode));
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
		$this->set('roleId', $roleId);
        $this->set('_serialize', ['certificates']);
    }
	public function approved()
    {
		$collegeId = $this->Auth->user('id') ;
		$collegeCode = $this->Auth->user('username') ;
		$roleId = $this->Auth->user('role_id') ;
		if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
		 } else {
		    $this->viewBuilder()->layout('main');
		 }
		$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'1','Certificates.college_id'=>$collegeCode));
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
		$this->set('roleId', $roleId);
        $this->set('_serialize', ['certificates']);
    }
	public function request()
    {
		$collegeId = $this->Auth->user('id') ;
		$roleId = $this->Auth->user('role_id') ;
		if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
		 } else {
		    $this->viewBuilder()->layout('main');
		 }
		$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'3','Certificates.college_id'=>$collegeCode));
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
		$this->set('roleId', $roleId);
        $this->set('_serialize', ['certificates']);
    }
	public function unapproved($id = null)
    {
		$collegeId = $this->Auth->user('id') ;
		$roleId = $this->Auth->user('role_id') ;
		//debug($this->Auth->user());
		if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
			$collegeCode = $this->Auth->user('username') ;
			$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'0','Certificates.college_id'=>$collegeCode));
		 } else {
		    $this->viewBuilder()->layout('main');
			$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'0'));
		 };
		
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
		$this->set('roleId', $roleId);
        $this->set('_serialize', ['certificates']);
    }
	public function deployed($id = null)
    {
		$collegeId = $this->Auth->user('id') ;
		$roleId = $this->Auth->user('role_id') ;
		if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
			$collegeCode = $this->Auth->user('username') ;
			$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('Certificates.status'=>'2','Certificates.college_id'=>$collegeCode));
		 } else {
		    $this->viewBuilder()->layout('main');
			$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('status'=>'2'));
		 }
        
		
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
        $this->set('_serialize', ['certificates']);
    }
	public function detail($id = null)
    {
		
        $certificate = $this->Certificates->get($id, [
            'contain' => []
        ]);
		$roleId = $this->Auth->user('role_id') ;
		if($roleId==2){
		    $this->viewBuilder()->layout('collegemain');
		 } else {
		    $this->viewBuilder()->layout('main');
		 }
        $this->set('certificate', $certificate);
        $this->set('_serialize', ['certificate']);
    }

}
