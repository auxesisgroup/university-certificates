<?php
namespace App\Controller;

use App\Controller\AppController;
use cake\orm\tableregistry;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Http\Client;
/**
 * Interests Controller
 *
 * @property \App\Model\Table\InterestsTable $Interests
 */
class HomeController  extends AppController
{
	         public function initialize()
				{
					parent::initialize();
					 $this->Auth->allow(array('search','certificate','demo','searchcertificates'));
				}

		  public function search(){

			   $this->Certificates 	= TableRegistry::get('Certificates');
			  // $requestcertificate = $this->Certificates->find()->where(['status'=>'3'])->count();
			  if ($this->request->is('post')) {
				   $text = $this->request->data['adhar'] ;
				   return $this->redirect(['action' => 'searchcertificates',$text]);
			  }
			   $this->viewBuilder()->layout('student');
		  }
		   public function certificate($id=null){
			   $http = new Client();
			   $this->Certificates 	= TableRegistry::get('Certificates');
			   $certificate = $this->Certificates->find()->where(['id'=>$id])->first();
			   $this->set('certificate',$certificate);
			   $this->viewBuilder()->layout(false);
		  }

		   public function searchcertificates($searchtext=null){

			   $this->Certificates 	= TableRegistry::get('Certificates');
			   $certificates = $this->Certificates->find()->where(['adhar_number'=>$searchtext,'status'=>'2'])->orWhere(['blockchain_certificate_address'=>$searchtext])->all();

			   $this->set('certificates',$certificates);
			   $this->viewBuilder()->layout(false);
		  }

		  public function demo($id=null){
				  $this->autoRender =false;
				  $this->Certificates = TableRegistry::get('Certificates');
				  $post = $this->Certificates->find()->where(['id'=>$id])->first();
				  $url ="http://206.81.6.108:8080/com.certificateCreation/createCertificate/";
				  $data['studentId']='5677779999999991a';
				  $data['adharId']='4abADASD544545sa';
				  $data['certHash']='asdasdas4545454aaaaa';
				  $data['mobileNumber']='9893453433';
				  $data['pubKey']='45454545';

					$ch = curl_init($url);
					$jsonDataEncoded = json_encode($data);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					$result = curl_exec($ch);
					echo '<pre>';  debug($result); echo '</pre>';
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
								return $this->redirect(['controller'=>'certificates','action' => 'approved']);
						   }



					}

				  curl_close($ch);
	     }


}
