<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Network\Http\Client;
use Cake\Core\Configure;
/**
 * Universities Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UniversitiesController extends AppController
{
     public function initialize()
    {
		$this->Users = TableRegistry::get('Users');
		 parent::initialize();
		 $this->loadComponent('Flash');
	}

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function collegeslist()
    {
		$this->viewBuilder()->layout('main');
		 $this->paginate = [
			'conditions' => [
				'role_id' => 2,
			]
		 ];
        $colleges = $this->paginate($this->Users);
        $this->set(compact('colleges'));
        $this->set('_serialize', ['colleges']);
    }

    public function viewcollege($id = null)
    {
		$this->viewBuilder()->layout('main');
        $college = $this->Users->get($id, [
            'contain' => []
        ]);

        $this->set('college', $college);
        $this->set('_serialize', ['college']);
    }


   	public function approve($id = null)
    {

		$this->viewBuilder()->layout('main');
		$this->Certificates 	= TableRegistry::get('Certificates');
		$post = $this->Certificates->find()->where(['id'=>$id])->first();
		 //debug($post);
		   if(!empty($post)){
					  $url ="http://206.81.6.108:8080/com.certificateCreation/createCertificate/";
					  $data['studentId'] = $post['student_id'];
					  $data['adharId'] = $post['adhar_number'] ;
					  $data['certHash'] = $post['hash'] ;
					  $data['mobileNumber'] = $post['mobile'] ;
					  $data['pubKey'] = $post['student_public_key']  ;
					//  debug($data);
					  $ch = curl_init($url);
					  $jsonDataEncoded = json_encode($data);
            
					  curl_setopt($ch, CURLOPT_POST, 1);
					  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
					  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					  $result = curl_exec($ch);

					  $resultData = json_decode($result, true);

					//  debug($resultData);
					   if(!empty(@$resultData['result'])){
							   $transactionHash = $contractAddress ='' ;

							   if(!empty(@$resultData['result']['transactionHash'])){
									$transactionHash = $resultData['result']['transactionHash'];
							   }
								if(!empty(@$resultData['result']['contractAddress'])){

									$certificateddata['status']= 2;
									$certificateddata['blockchain_certificate_address']= $resultData['result']['contractAddress'];
									$certificateddata['transaction_hash']= $transactionHash;
									$post = $this->Certificates->patchEntity($post,$certificateddata);
										if ($this->Certificates->save($post)) {
											 $this->Flash->success(__('Certificate deployed successfully.'));
											 return $this->redirect(['controller'=>'certificates','action' => 'detail',$id]);
										} else {
											  $this->Flash->error(__('Certificate could not be deployed. Please, try again.'));
										}
							   }
						} else {
							 $this->Flash->error(__('Certificate could not be deployed. Please, try again.'));
						}
			}
		   return $this->redirect(['controller'=>'certificates','action' => 'unapproved']);

    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function addcollege()
    {
		$this->viewBuilder()->layout('main');
		$degrees =Configure::read('Site.degrees');
		$totalcollege = $this->Users->find()->where(['role_id'=>2])->count();
        $college = $this->Users->newEntity();
        if ($this->request->is('post')) {
			 $degree_offered = implode(',',$this->request->data['degree_offered']);
			 $this->request->data['degree_offered'] = $degree_offered ;
			// debug($this->request->data); exit;
            $college = $this->Users->patchEntity($college, $this->request->data);
            if ($this->Users->save($college)) {
                $this->Flash->success(__('College saved successfully.'));

                return $this->redirect(['controller'=>'universities','action' => 'collegeslist']);
            }
            $this->Flash->error(__('College could not be saved. Please, try again.'));
        }
        $this->set(compact('college','totalcollege','degrees'));
        $this->set('_serialize', ['college']);
    }


	public function login()
	{
		$this->viewBuilder()->layout('login');

		if ($this->request->is('post')) {
			$user = $this->Auth->identify();
			if ($user) {
				$this->Auth->setUser($user);
			//	debug($user);exit;
				if($user['role_id']==1){
				    return $this->redirect(['action' => 'dashboard']);
				} else if($user['role_id']==2){
				    return $this->redirect(['controller'=>'colleges','action' => 'dashboard']);
				}
			}
			$this->Flash->error(__('Invalid username or password, try again'));
		}
	}
	public function logout(){
		  $this->Flash->success('You are now logout.');
		  $this->redirect($this->Auth->logout()) ;
	 }

	  public function dashboard(){
		   $this->Certificates 	= TableRegistry::get('Certificates');
		   $this->viewBuilder()->layout('main');
		    $totalcollege = $this->Users->find()->where(['role_id'=>2])->count();
		    $requestcertificate = $this->Certificates->find()->where(['status'=>'0'])->count();
			$approvecertificate = $this->Certificates->find()->where(['status'=>'1'])->count();
			$deployecertificate = $this->Certificates->find()->where(['status'=>'2'])->count();
		   $this->set(compact('totalcollege','requestcertificate','approvecertificate','deployecertificate'));
	  }
}
