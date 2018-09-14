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
class CollegesController extends AppController
{
     public function initialize()
    {
		$this->Users = TableRegistry::get('Users');
		$this->Certificates = TableRegistry::get('Certificates');
		 parent::initialize();
		 $this->loadComponent('Flash');
		 $this->loadComponent('Paginator');
		 $this->Auth->allow(array('certificatedeploy'));
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

    public function certificates($id = null)
    {
		$this->viewBuilder()->layout('collegemain');
		$collegeId = $this->Auth->user('id') ;

		$this->paginate = array('order'=>array('Certificates.id'=>'DESC'),'limit'=>'20','conditions'=>array('college_id'=>$collegeId));
        $certificates = $this->paginate($this->Certificates);
        $this->set('certificates', $certificates);
        $this->set('_serialize', ['certificates']);
    }
    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function issuecertificate()
    {
		$this->viewBuilder()->layout('collegemain');
		$id = $this->Auth->user('id') ;
		$degreeOffered = $this->Auth->user('degree_offered');

		$certificatecount = $this->Certificates->find()->count();
		$alldegrees =Configure::read('Site.degrees');
		$degreeOfferedarray = explode(',',$degreeOffered);
		$degrees =array();
		foreach($alldegrees as $key=>$value){
			  if (in_array($key, $degreeOfferedarray)) {
				   $degrees[$key] =$value ;
			  }

		}
		// debug($degrees);
		$newcertificateId =$certificatecount+1;
        $certificate = $this->Certificates->newEntity();
        if ($this->request->is('post')) {

			$collegeId = $this->Auth->user('username') ;
			$this->request->data['college_id'] = $collegeId ;
			$this->request->data['imgname'] = '' ;
			$this->request->data['transaction_hash'] = '' ;
			$this->request->data['blockchain_certificate_address'] = '' ;

			if($this->request->data['certificate']['error']==0){
				   $file = $this->request->data['certificate']; //put the data into a var for easy use
					$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
					$arr_ext = array('jpg', 'jpeg', 'pdf','png'); //set allowed extensions
					//only process if the extension is valid
					if(in_array($ext, $arr_ext))
					{
							//do the actual uploading of the file. First arg is the tmp name, second arg is
							//where we are putting it
							$filename = str_replace(' ','_',$file['name']);
							$imagename = mt_rand(100000,999999).$filename;
							$filepath = WWW_ROOT . 'certificates/';
							move_uploaded_file($file['tmp_name'], $filepath.$imagename );

							//prepare the filename for database entry
						   $this->request->data['imgname'] = $imagename;
						   $rannum =  mt_rand(1000,9999).$filename;
						   $this->request->data['hash'] = $rannum."mfDs4PRZPa9ZDbds8kMTDZQisLN1KFykNixdv5vh".$newcertificateId;
					}
			}

            $certificate = $this->Certificates->patchEntity($certificate, $this->request->data);
            if ($this->Certificates->save($certificate)) {
                $this->Flash->success(__('Certificate issued successfully.'));

                return $this->redirect(['controller'=>'certificates','action' => 'lists']);
            }
            $this->Flash->error(__('Certificate could not be issued. Please, try again.'));
        }
        $this->set(compact('certificate','certificatecount','degrees'));
        $this->set('_serialize', ['certificate']);
    }


	public function login()
	{
		  $this->viewBuilder()->layout('login');
		if ($this->request->is('post')) {
			$user = $this->Auth->identify();

			if ($user) {
				$this->Auth->setUser($user);
				if($user['role_id']==2){
				    return $this->redirect(['action' => 'dashboard']);
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
		   $this->viewBuilder()->layout('collegemain');

			$collegeCode = $this->Auth->user('username') ;
			$totalcertificate = $this->Certificates->find()->where(['college_id'=>$collegeCode])->count();
			$requestcertificate = $this->Certificates->find()->where(['status'=>'0','college_id'=>$collegeCode])->count();
			$approvecertificate = $this->Certificates->find()->where(['status'=>'1','college_id'=>$collegeCode])->count();
			$deployecertificate = $this->Certificates->find()->where(['status'=>'2','college_id'=>$collegeCode])->count();
		   $this->set(compact('totalcertificate','requestcertificate','approvecertificate','deployecertificate'));
	  }
	  public function certificatedeploy($id=null){
		   $this->autoRender =false;
				     $post = $this->Certificates->find()->where(['id'=>$id])->first();
					  $url ="http://206.81.6.108:8080/com.certificateCreation/createCertificate/";
					  $data['studentId'] = $post['student_id'];
					  $data['adharId'] = $post['adhar_number'] ;
					  $data['certHash'] = $post['hash'] ;
					  $data['mobileNumber'] = $post['mobile'] ;
					  $data['pubKey'] = $post['student_public_key']  ;
						$ch = curl_init($url);
						$jsonDataEncoded = json_encode($data);
						curl_setopt($ch, CURLOPT_POST, 1);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
						$result = curl_exec($ch);

					  $resultData = json_decode($result, true);

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
										} else {
											  $this->Flash->error(__('Certificate could not be deployed. Please, try again.'));
										}
							   }
						} else {
							 $this->Flash->error(__('Certificate could not be deployed. Please, try again.'));
						}
				   return $this->redirect(['controller'=>'certificates','action' => 'approved']);
				  curl_close($ch);
				//  exit;
	  }
}
