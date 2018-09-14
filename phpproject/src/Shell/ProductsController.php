<?php
  namespace App\Controller;
  use App\Controller\AppController;
  use Cake\ORM\TableRegistry;
  use Cake\Datasource\ConnectionManager;
  use Cake\Core\Configure;
  
   class ProductsController extends AppController
   {
	     public function initialize()
         {
			  parent::initialize();
			   $this->Friend  	= TableRegistry::get('Friends');
			   $this->Product 	= TableRegistry::get('Products');
			   $this->Interest  = TableRegistry::get('Interests');
			   $this->Wishlist = TableRegistry::get('Wishlists');
			   $this->Recommend = TableRegistry::get('Recommends');
			   $this->Post = TableRegistry::get('Posts') ; 
			   $this->Users = TableRegistry::get('Users') ;
			   $Uploadfolder = Configure::read('Site.Uploadfolder');
		 }
	   
	    public function lists(){
			  $data = $this->paginate($this->Products);
			  if(!empty($data)){
				  $status = 1 ;
			  } else {
				  $status = 0 ;
			  }
			  $this->set(compact('data','status'));
		 }
		 
		 public function view($id = null)
			{  
		   if ($this->request->is('post')) {
			         if(empty($id)){
						  $id = @$this->request->data['product_id'] ;
					 }
			         
					$data = $this->Products->get($id, [
						'contain' => []
					]);
					$userId 		= $this->request->data['user_id'] ;
					$Url 	= Configure::read('Site.SiteUrl');
					$allStore = '' ;
					$isWishlist = 0 ;
					$sizeoption = $compairproduct = $similarproduct = '' ;
					  if(!empty($data)){
						  $productcategory = $data['category_id'];
						  $productbrands  = $data['brands'];
						  $name  = $data['name'];
						  $data['shareUrl'] = $Url.'products/'.$id; 
						  if(!empty($data['size_variants'])){
							   $sizeVariants = $data['size_variants'];
							   $sizeVariantsarray = array();
							   $sizeVariantsarray = explode(',',$sizeVariants);
							   $sizeoptions = array();
							   $sizeoptions = $this->Product->find()->select(['size'=>'DISTINCT(size)'])->where(['productid IN'=>$sizeVariantsarray,'size !='=>''])->hydrate(false)->all();
                             //  $sizeoptions = array_unique($sizeoptions);						    
							 //  $sizeoption = $sizeoptions['size'] ;
						  }
						  
						  $similarproduct = $this->Product->find()->where(['category_id IN' =>$productcategory])->orWhere(['brands IN' =>$productbrands])->limit(10);
						//  $compairproduct = $this->Product->find()->where(['name IN' =>$name])->limit(10);
						  $wishlistdata =   $this->Wishlist->find()->where(['user_id' =>$userId,"find_in_set('$id',products)"])->first();
						  $allStore = $this->Product->find()->select(['id','affiliate_type','name','producturl','price','offer_price'])->where(['name IN' =>$name])->limit(1);
						  
						  if(!empty($wishlistdata)){
							  $isWishlist = 1 ;
						  }
						 $status = 1 ;
					  } else {
						  $status = 0 ;
					  }
					// debug($sizeoption);
					 $this->set(compact('data','sizeoptions','allStore','status','isWishlist','similarproduct'));
				}
		  }
		 
		   public function personalize(){
			   if ($this->request->is('post')) {
					$userId 		= $this->request->data['user_id'] ;
					$gender 		= $this->request->data['gender'] ;
					$type 			= '2' ;
					$genderstatus 	= '0';
					//$data			=	array();
					if(!empty($gender)){
						$genderstatus ='1';
						if($gender=='male'){
							$type = '1' ;
						}
					}
					
					$interestsdata 	= $this->Interest->find()->where(['user_id' => $userId])->first();
					$page 			= 1 ;
					if((!empty($interestsdata)) && (!empty($interestsdata['brands']) || !empty($interestsdata['categories']))){
						$brands 	= $interestsdata['brands'];
						$categories = $interestsdata['categories'];
						$categoriearray = explode(",", $categories);
						$brandarray = explode(",", $brands);
						if(!empty($gender)){ 
							$data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['category_id IN' =>$categoriearray,'type IN'=>array($type,'3')])->orWhere(['brands IN' =>$brandarray,'type IN'=>array($type,'3')])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
					    } else {
							$data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['category_id IN' =>$categoriearray])->orWhere(['brands IN' =>$brandarray])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
						}
					} else {
						if(!empty($gender)){
						  $data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['type IN'=>array($type,'3')])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
					    } else {
						   $data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
						}
						
					}
					if(@$data->isEmpty()) {
						if(!empty($gender)){
						  $data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['type IN'=>array($type,'3')])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
					    } else {
						   $data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->order(['RAND()'])->group('name')->page(1)->all();
						}
					}
					$conn = ConnectionManager::get('default');
					
					    $notificationqry ="(select count(id) as friendrequest,(select count(comments.id) from posts left join comments on posts.id=comments.parent_id where posts.user_id='$userId' and comments.type=0 and comments.user_id !=$userId and posts.status=1 and comments.is_viewed=0 ) as postcommentscount,(select count(likes.id) from posts left join likes on posts.id=likes.parent_id where posts.user_id='$userId' and likes.user_id !=$userId and posts.status=1 and likes.is_viewed=0 and likes.type=0 ) as postlikecount,(select count(comments.id) from sets left join comments on sets.id=comments.parent_id where sets.user_id='$userId' and comments.type=1 and comments.user_id !=$userId and sets.status=1 and comments.is_viewed=0 ) as setcommentscount,(select count(likes.id) from sets left join likes on sets.id=likes.parent_id where sets.user_id='$userId' and likes.user_id !=$userId and sets.status=1 and likes.is_viewed=0 and likes.type=1 ) as setlikecount from friends where friend_id=$userId and status=0)" ;
						$notificationstmt = $conn->execute($notificationqry);
						$notification = $notificationstmt->fetchAll('assoc');
						$totalNotification =$notification[0]['friendrequest']+$notification[0]['postcommentscount']+$notification[0]['postlikecount']+$notification[0]['setcommentscount']+$notification[0]['setlikecount'];
					
					$status = "0" ;
					if(!empty($data)){
						$status ="1" ;
					}
			   	}
				$this->set(compact('status','totalNotification','genderstatus','data','page'));
		   }
		   
		   
		/* 
		 public function personalizeproduct(){
			 //   $this->autoRender = false ;
			   
			    $userId 		= $this->request->data['user_id'] ;
			    $recommenddata 	= $this->Recommend->find()->where(['user_id' => $userId])->first();
				$productId 		= explode(",",$recommenddata['likeproducts']);
				$recommendproducts = $this->Product->find()->where(['id IN' =>$productId])->order(['id'=>'DESC'])->all();
				$price 			= array();
				$categorys 		= array();
				$brand 			= array();
				$size 			= array();
				$nextpage 		= 1 ;
				foreach($recommendproducts as $recommendproduct){
					if(!empty($recommendproduct['price'])) {
						$price[] 		= $recommendproduct['price'];
					}
					if(!empty($recommendproduct['category_id'])) {
						$categorys[]   = $recommendproduct['category_id'];
					}
					if(!empty($recommendproduct['brands'])) {
						$brand[]		= $recommendproduct['brands'];
					}
					if(!empty($recommendproduct['size'])) {
						$size[] 		= $recommendproduct['size'];
					}
				 }
				
				
				$product = $this->Product->find()->where(['category_id IN' =>$categorys])->orWhere(['brands IN' =>$brand])->orWhere(['price IN' =>$price])->orWhere(['size IN'=>$size])->limit(10)->order(['id'=>'DESC'])->all();
			//	debug($recommendproducts);
				$this->set(compact('message','recommendproducts','product','nextpage'));
			 
		 }  */
		 /*
		 public function recommends(){
			   if ($this->request->is('post')) {

					$userId = $this->request->data['user_id'] ;
					$recommenddata = $this->Recommend->find()->where(['user_id' => $userId])->first();
					$interestsdata = $this->Interest->find()->where(['user_id' => $userId])->first();
					if(!empty($recommenddata))	{
						 $recommend = $recommenddata;
					} else {
						 $recommend = $this->Recommend->newEntity();	
					}		
					$recommends = $this->Recommend->patchEntity($recommend, $this->request->data);
					$brands 	= $interestsdata['brands'];
					$categories = $interestsdata['categories'];
					$categoriearray = explode(",", $categories);
					$brandarray = explode(",", $brands);
					$data = $this->Product->find()->where(['category_id IN' =>$categoriearray])->orWhere(['brands IN' =>$brandarray])->limit(10)->order(['id'=>'DESC']);
					if ($this->Recommend->save($recommends)) {
						$status ="1" ;
						$message = 'you recommends saved successfully.' ;
					} else {
						  $status ="0" ;
						  $message = 'your recommends could not saved . Please try again.' ;
					}
				}
				$this->set(compact('message','status','data'));
		 }
		 */
		 
		 public function recommends(){
			  if ($this->request->is('post')) {
				  $productdata 		= array();
			      $userId 			= $this->request->data['user_id'] ;
				  $likeproducts   	= $this->request->data['likeproducts'] ;
				  $dislikeproducts 	= $this->request->data['dislikeproduct'] ;
				  $page 			= $this->request->data['page'] ;
				  $gender 			= $this->request->data['gender'] ;
				   $type = '2' ;
				   $genderstatus ='0';
					if(!empty($gender)){
						$genderstatus ='1';
						 if($gender=='male'){
							$type = '1' ;
						 }
					}
					
				  $showrecommend = 0 ;
				  $recommenddata = $this->Recommend->find()->where(['user_id' => $userId])->first();
				  $totallike = 0 ;
					if(!empty($recommenddata))	{
						   $recommend = $recommenddata;
						   $newlikeproductarray = explode(',',$likeproducts);
						   $olddislikeproductarray = array();
						   if($page==1){
								$oldlikeproductarray = array();
								
						   } else {
							    $oldlikeproductarray = explode(',',$recommenddata['likeproducts']);
								if(!empty($recommenddata['dislikeproduct'])) {
									$olddislikeproductarray = explode(',',$recommenddata['dislikeproduct']);
								}
						   }
						   
						   $like = array_merge($newlikeproductarray,$oldlikeproductarray);
						   $productdata['likeproducts'] = implode(',',array_unique($like));
						   $newdislikeproductarray = array();
						   
						   if(!empty($dislikeproducts)) {
								$newdislikeproductarray = explode(',',$dislikeproducts);
						   }
						   
						   $dislike = array_merge($newdislikeproductarray,$olddislikeproductarray);
						   $productdata['dislikeproduct'] = implode(',',array_unique($dislike));
						   if(!empty($like)){
								$totallike = count(array_unique($like));

						   }
						   
					} else {
						  $productdata['user_id'] = $userId;
						  $productdata['likeproducts'] = $likeproducts;
						  $productdata['dislikeproduct'] = $dislikeproducts;
						  $recommend = $this->Recommend->newEntity();
						  $totallike = count(explode(',',$likeproducts));
					 }	
                       if($totallike>=10){
						   
						   $showrecommend = 1 ;
					   }
					
					 $recommends = $this->Recommend->patchEntity($recommend, $productdata);
				     if ($this->Recommend->save($recommends)) {
						$status ="1" ;
						$message = 'your recommends saved successfully.' ;
					 } else {
						  $status ="0" ;
						  $message = 'your recommends could not saved . Please try again.' ;
					 }
					   $interestsdata = $this->Interest->find()->where(['user_id' => $userId])->first();
					  
					   $page = $page+1 ;
					   
						if(!empty($interestsdata)) {
							$brands 	= $interestsdata['brands'];
							$categories = $interestsdata['categories'];
							$categoriearray = explode(",", $categories);
							$brandarray = explode(",", $brands); 
							if(!empty($gender)){
							   $data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->where(['type IN'=>array($type,'3')])->order(['RAND()'])->group('name')->page($page)->all();
							} else {
								$data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->order(['RAND()'])->group('name')->page($page)->all();
							}
							// $data = $this->Product->find()->where(['category_id IN' =>$categoriearray,'type IN'=>array($type,'3')])->orWhere(['brands IN' =>$brandarray,'type IN'=>array($type,'3')])->limit(10)->group('name')->order(['RAND()'])->page($page)->all();
						} else {  
						    if(!empty($gender)){
								$data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->where(['type IN'=>array($type,'3')])->order(['RAND()'])->group('name')->page($page)->all();
						    } else {
								$data = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->limit(20)->order(['RAND()'])->group('name')->page($page)->all();
							}
						}
							
					
				    $this->set(compact('status','genderstatus','data','totallike','page','showrecommend'));
			  }
		 }
		 
		  public function recommendproduct(){
			     if ($this->request->is('post')) {
					   $userId 			= $this->request->data['user_id'] ;
					   $recommenddata 	= $this->Recommend->find()->where(['user_id' => $userId])->first();
					   $gender 			= $this->request->data['gender'] ;
					   $genderstatus ='0';
						$type = '2' ;
						if(!empty($gender)){
							if($gender=='male'){
								$type = '1' ;
							}
							$genderstatus ='1';
						}
						
					   
					   $data = array() ;
					   $status = 0 ;
					   if(!empty($recommenddata))
						    {
								$recommendProductIds = explode(',',$recommenddata['likeproducts']) ;
								$recommendcategory 	= array();
								$recommendbrands 	= array();
								$recommendprice 	= array();
								$recommendsize 		= array();
							    $recommendProducts 	= $this->Product->find()->where(['id IN'=>$recommendProductIds])->all();
								if(!empty($recommendProducts)){
									foreach($recommendProducts as $recommendProduct){
										$recommendcategory[] 	= $recommendProduct['category_id'];
										$recommendbrands[] 		= $recommendProduct['brands'];
										$recommendprice[] 		= $recommendProduct['price'];
										$recommendsize[]		= $recommendProduct['size'];
									}
									if(!empty($recommendcategory)){
										$recommendcategory = array_unique($recommendcategory);
									}
									if(!empty($recommendbrands)){
										 $recommendbrands = array_unique($recommendbrands);
									}
									if(!empty($recommendprice)){
										 $recommendprice = array_unique($recommendprice);
									}
									if(!empty($recommendsize)){
										 $recommendsize = array_unique($recommendsize);
									}
									if(!empty($gender)){
										$data = $this->Product->find()->where(['category_id IN'=>$recommendcategory,'type IN'=>array($type,'3')])->orWhere(['brands IN'=>$recommendbrands,'type IN'=>array($type,'3')])->limit(20)->group('name')->order(['RAND()'])->all() ;
									} else {
										$data = $this->Product->find()->where(['category_id IN'=>$recommendcategory])->orWhere(['brands IN'=>$recommendbrands])->limit(20)->group('name')->order(['RAND()'])->all() ;
									}
								//	$data = $this->Product->find()->where(['category_id IN'=>$recommendcategory])->orWhere(['brands IN'=>$recommendbrands])->orWhere(['price IN'=>$recommendprice])->orWhere(['size IN'=>$recommendsize])->limit(20)->order(['RAND()'])->all() ;
									if(!empty($data)){
										 $status = 1;
										
									}
								}
							
					    }
					   $this->set(compact('status','genderstatus','data'));
				 }
		  }
		 
		 public function homepage(){
			  $posts = array();
			  $status = 0 ; 
			  $nextpage = '2' ;	
			  if($this->request->is('post')){ 
			        // $userId = '1'  ;
				    $userId 		= $this->request->data['user_id'] ;
				    $gender 		= $this->request->data['gender'] ;
					$type = '2' ;
					$justforyou = '' ;
					$genderstatus ='0';
					 if(!empty($gender)){
							$genderstatus ='1';
							if($gender=='male'){
								$type = '1' ;
							}
					 }
				   $interestsdata = $this->Interest->find()->where(['user_id' => $userId])->first(); 
				   $friends = $this->Friend->find('all',array('conditions'=>array('user_id'=>$userId,'status'=>1)));
				   $author_ids = $userId.',';
				   if(!empty($friends)){
					   foreach($friends as $friend){
					      $author_ids .= $friend['friend_id'].','; 
					   }
				   }
				    $author_ids =rtrim($author_ids,',') ;
					if((!empty($interestsdata)) && (!empty($interestsdata['brands']) || !empty($interestsdata['categories']))){
						$brands 	= $interestsdata['brands'];
						$categories = $interestsdata['categories'];
						$categoriearray = explode(",", $categories);
						$brandarray = explode(",", $brands);
						if(!empty($gender)){
							$justforyou = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['category_id IN' =>$categoriearray,'type IN'=>array($type,'3')])->orWhere(['brands IN' =>$brandarray,'type IN'=>array($type,'3')])->order(['RAND()'])->limit(20)->group('name')->all();
						} else {
							$justforyou = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['category_id IN' =>$categoriearray])->orWhere(['brands IN' =>$brandarray])->order(['RAND()'])->limit(20)->group('name')->all();
						}
						$status = 1 ;
					} else {
						if(!empty($gender)){
							$justforyou = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->where(['type IN'=>array($type,'3')])->order(['RAND()'])->group('name')->limit(20)->all() ;
						} else {
							$justforyou = $this->Product->find()->select(['id','type','affiliate_type','name','image','price','offer_price','availability','description','producturl'])->order(['RAND()'])->group('name')->limit(20)->all() ;
						}
					}
					     $Uploadfolder 	= Configure::read('Site.Uploadfolder');
						 $siteurl = $Uploadfolder.'posts/';  
						 $Url 	= Configure::read('Site.SiteUrl');
						 $shareurl = $Url.'posts/';  
					     $userurl 		= $Uploadfolder.'users/';
					     $conn = ConnectionManager::get('default');
						 $setshareurl = $Url.'sets/'; 
						 $setimageurl = $Uploadfolder.'setmain/';  
						
                        $qry ="select posts.id,posts.created as orders ,CONCAT('',0) as type,posts.user_id,CONCAT('$shareurl',posts.id) as shareUrl,users.name as authorname,posts.width,posts.height,posts.content,users.img_path as authorimagename,CONCAT('$userurl',users.img_path) as authorimageurl,posts.img_name as postimagename,CONCAT('$siteurl',posts.img_name) as imageurl ,DATE_FORMAT(posts.created, '%d-%m-%Y %H:%i:%s %p') as posttime,(select count(comments.id) from comments left join posts as p2 on p2.id=comments.parent_id where (comments.parent_id=posts.id and comments.type=0)) as countcomment,(select count(likes.id) from likes left join posts as p3 on p3.id=likes.parent_id where (likes.parent_id=posts.id and likes.type=0 and likes.is_like=1)) as countlike,(select count(checklike.id) from likes as checklike left join posts as p4 on p4.id=checklike.parent_id where (checklike.parent_id=posts.id and checklike.type=0 and checklike.is_like=1 and checklike.user_id=$userId)) as isLike  from posts left join users on posts.user_id=users.id where posts.user_id IN ($author_ids) and posts.status=1 GROUP BY posts.id  UNION select sets.id,sets.created as orders,CONCAT('',1) as type,sets.user_id,CONCAT('$setshareurl',sets.id) as shareUrl,users.name as authorname,CONCAT('',600) as width,CONCAT('',600) as height,CONCAT('','') as content,users.img_path as authorimagename,CONCAT('$userurl',users.img_path) as authorimageurl,sets.img_name as postimagename,CONCAT('$setimageurl',sets.img_name) as imageurl,DATE_FORMAT(sets.created, '%d-%m-%Y %H:%i:%s %p') as posttime,(select count(comments.id) from comments left join sets as s2 on s2.id=comments.parent_id where (comments.parent_id=sets.id and comments.type=1)) as countcomment,(select count(likes.id) from likes left join sets as s3 on s3.id=likes.parent_id where (likes.parent_id=sets.id and likes.type=1 and likes.is_like=1)) as countlike,(select count(checklike.id) from likes as checklike left join sets as s4 on s4.id=checklike.parent_id where (checklike.parent_id=sets.id and checklike.type=1 and checklike.is_like=1 and checklike.user_id=$userId)) as isLike  from sets left join users on sets.user_id=users.id where sets.user_id IN ($author_ids) and sets.status=1 GROUP BY sets.id order by orders desc limit 15" ;
     					$stmt = $conn->execute($qry);
						$posts = $stmt ->fetchAll('assoc');
						
						$countqry ="select count(posts.id) as totalpost,(select count(sets.id) from sets left join users on sets.user_id=users.id where sets.user_id IN ($author_ids) and sets.status=1) as totalset from posts left join users on posts.user_id=users.id where posts.user_id IN ($author_ids) and posts.status=1 " ;
     					$countstmt = $conn->execute($countqry);
						$countposts = $countstmt ->fetchAll('assoc');
						$totaldata = $totalpage = '' ;
						if(!empty($countposts)) {
							$totaldata = @$countposts[0]['totalpost']+@$countposts[0]['totalset'] ;
							$totalpage = ceil($totaldata/15) ;
						}
						
						if($totaldata<15){
							$morepost = 15-(int)$totaldata ;
							$mqry ="select sets.id,sets.created as orders,CONCAT('',1) as type,sets.user_id,CONCAT('$setshareurl',sets.id) as shareUrl,users.name as authorname,CONCAT('',600) as width,CONCAT('',600) as height,CONCAT('','') as content,users.img_path as authorimagename,CONCAT('$userurl',users.img_path) as authorimageurl,sets.img_name as postimagename,CONCAT('$setimageurl',sets.img_name) as imageurl,DATE_FORMAT(sets.created, '%d-%m-%Y %H:%i:%s %p') as posttime,(select count(comments.id) from comments left join sets as s2 on s2.id=comments.parent_id where (comments.parent_id=sets.id and comments.type=1)) as countcomment,(select count(likes.id) from likes left join sets as s3 on s3.id=likes.parent_id where (likes.parent_id=sets.id and likes.type=1 and likes.is_like=1)) as countlike,(select count(checklike.id) from likes as checklike left join sets as s4 on s4.id=checklike.parent_id where (checklike.parent_id=sets.id and checklike.type=1 and checklike.is_like=1 and checklike.user_id=$userId)) as isLike  from sets left join users on sets.user_id=users.id where sets.user_id NOT IN ($author_ids) and  sets.status=1 order by countlike desc limit 0,$morepost";
						    $mstmt = $conn->execute($mqry);
						    $mposts = $mstmt ->fetchAll('assoc');
							$posts = array_merge($posts,$mposts) ;
						}
					     
						$notificationqry ="(select count(id) as friendrequest,(select count(comments.id) from posts left join comments on posts.id=comments.parent_id where posts.user_id='$userId' and comments.type=0 and comments.user_id !=$userId and posts.status=1 and comments.is_viewed=0 ) as postcommentscount,(select count(likes.id) from posts left join likes on posts.id=likes.parent_id where posts.user_id='$userId' and likes.user_id !=$userId and posts.status=1 and likes.is_viewed=0 and likes.type=0 ) as postlikecount,(select count(comments.id) from sets left join comments on sets.id=comments.parent_id where sets.user_id='$userId' and comments.type=1 and comments.user_id !=$userId and sets.status=1 and comments.is_viewed=0 ) as setcommentscount,(select count(likes.id) from sets left join likes on sets.id=likes.parent_id where sets.user_id='$userId' and likes.user_id !=$userId and sets.status=1 and likes.is_viewed=0 and likes.type=1 ) as setlikecount from friends where friend_id=$userId and status=0)" ;
						$notificationstmt = $conn->execute($notificationqry);
						$notification = $notificationstmt->fetchAll('assoc');
						
						$totalNotification =$notification[0]['friendrequest']+$notification[0]['postcommentscount']+$notification[0]['postlikecount']+$notification[0]['setcommentscount']+$notification[0]['setlikecount'];
					  if(!empty($posts)){
						  $status = 1 ;
					  }
					
					 $this->set(compact('status','nextpage','totaldata','totalpage','posts','totalNotification','genderstatus','justforyou')); 
			  }
		 }
		  
		 public function addwishlist(){
				if($this->request->is('post')){
				   $userId 		= $this->request->data['user_id'];
				   $productId 	= $this->request->data['products'];
				   $type 		= $this->request->data['type'];
				   $productdata	= $this->Wishlist->find()->where(['user_id'=>$userId])->first();
				   $newproductarray = array($productId);
				   $status ="0" ;
				   if(!empty($productdata)){
					   $wishlist = $productdata ;
					   $oldproductarray = explode(',',$productdata['products']);
					   if($type==0){
						 $finalproducts = array_diff($oldproductarray,$newproductarray);
					   } else {
					     $finalproducts = array_merge($newproductarray,$oldproductarray);
					   }
					    $finalproducts = array_values(array_filter($finalproducts));
					    $mergeproducts 	= array_unique($finalproducts);
					  
					    $this->request->data['products'] = implode(',',$mergeproducts);
				   } else {
					   $wishlist = $this->Wishlist->newEntity() ;
				   }
					   $wishlistpatch = $this->Wishlist->patchEntity($wishlist,$this->request->data);
				   if($this->Wishlist->save($wishlistpatch)){
						 
						  if($type==0){ 
						    $status ="2" ;
						    $message = 'product remove successfully.' ;
						  } else {
							$status ="1" ;
							$message = 'product added successfully.' ;
						  }
				   } else {
						 
						 $message = 'product could not added . Please try again.' ;
				   }
			   }
			    $this->set(compact('message','status'));
		  }
		  
		 public function mywishlist(){
			 
			 if($this->request->is('post')){
				    $userId = $this->request->data['user_id'];
					$wishlistdata= $this->Wishlist->find()->where(['user_id'=>$userId])->first();
					$products = array();
					$status 	= '0' ;
					
					 $Uploadfolder = Configure::read('Site.Uploadfolder');
					 $userurl = $Uploadfolder.'users/';
					 $userdetail = $this->Users->find()
							->hydrate(false)
							->join([
							   'table' =>'friends',
							   'type' =>'left',
							   'conditions' =>'Users.id=friends.user_id'
							])->select(['id','name','city','gender','wishlist_privacy','birthday'=>"DATE_FORMAT(Users.dob, '%M %d')",'img_name'=>'img_path','imageurl'=>"CONCAT('$userurl',Users.img_path)",'friendcount'=>"count(friends.user_id)"])->where(['Users.id'=>$userId,'friends.status'=>1])->first();
					 
					if(!empty($wishlistdata)){
						 $productarray = explode(',',$wishlistdata['products']);
						 $products 	   = $this->Product->find('all',array('conditions'=>array('id IN'=>$productarray))) ;
						 if(!empty($products)){
								 $status = 1;
						 }
					}
					$this->set(compact('status','products','userdetail'));
			 }
		 } 
		 
		 public function wishlist(){
			 if($this->request->is('post')){
				    $userId 	= $this->request->data['user_id'];
					$friendId 	= $this->request->data['friend_id'];
					$isFriend 		= 0;
					$Uploadfolder = Configure::read('Site.Uploadfolder');
					$userurl = $Uploadfolder.'users/';
					$userdetail = $this->Users->find()
							->hydrate(false)
							->join([
							   'table' =>'friends',
							   'type' =>'left',
							   'conditions' =>'Users.id=friends.user_id'
							])->select(['id','name','city','gender','wishlist_privacy','birthday'=>"DATE_FORMAT(Users.dob, '%M %d')",'img_name'=>'img_path','imageurl'=>"CONCAT('$userurl',Users.img_path)",'friendcount'=>"count(friends.user_id)"])->where(['Users.id'=>$friendId,'friends.status'=>1])->first();
					
					$frienddetail = $this->Friend->find()->where(['user_id'=>$userId,'friend_id'=>$friendId])->orWhere(['user_id'=>$friendId,'friend_id'=>$userId])->first();
					$userdetail['isFriend']= 0 ;
					if(!empty($frienddetail)){
						if($frienddetail['status']=='1'){
							$userdetail['isFriend'] = 1;
						} else if(($frienddetail['status']=='0') &&($frienddetail['action_user_id']==$userId)){
							$userdetail['isFriend'] = 2;
						}  else if(($frienddetail['status']=='0') &&($frienddetail['action_user_id']==$friendId)){  
							$userdetail['isFriend'] = 3;
						}
					} 
					if($friendId==$userId){
						 $userdetail['isFriend'] = 4 ;
						 
					}
					
					$products 	= array();
					$status 	= '0' ;
					if($userdetail['wishlist_privacy']==0){
						$wishlistdata= $this->Wishlist->find()->where(['user_id'=>$friendId])->first();
						$products = array();
						if(!empty($wishlistdata)){
							 $productarray = explode(',',$wishlistdata['products']);
							 $products 	   = $this->Product->find('all',array('conditions'=>array('id IN'=>$productarray))) ;
							 if(!empty($products)){
								 $status = 1;
							 }
						}
					}
					$this->set(compact('status','products','userdetail'));
			 }
		 }

          public function categorieslist(){
			     if($this->request->is('post')){
			            $gender 	= $this->request->data['gender'];
						$status = 0 ;
						$this->Category 	= TableRegistry::get('Categories');
						$categories = $this->Category->find('list')->where(['parent_id'=>$gender])->toArray();
						$data = array();
						 foreach($categories as $key=>$value){
							$data[] = array('name'=>$value,'id'=>$key,'lavel'=>'0','child'=>$this->subcategory($key,0))   ;
						 }
						 if(!empty($data)){
							 $status = 1 ;
						 }
					    $this->set(compact('data','status'));
					 }
			       
		  }	
		  
		   function subcategory($id=null ,$lavel=null,$child=null){   
				$this->Category 	= TableRegistry::get('Categories');		   
				$lavel =$lavel+1;
                $data =  array();			
			    $subcategory = $this->Category->find('list')->where(['parent_id'=>$id])->toArray();
				  if(!empty($subcategory)) {                   
						  foreach($subcategory as $key=>$value){    
							                  
							  $countchild=$this->countchild($key); 
                              							  
							  if(!empty($countchild)){ 
                                   $data[]  =  array('name'=>$value,'id'=>$key,'lavel'=>$lavel,'child'=>$this->subcategory($key,$lavel,'1')) ;                                  
							   }  else {
								   $data[]  =  array('name'=>$value,'id'=>$key,'lavel'=>$lavel,'child'=>array()) ;  
							   }                                                                   
						  }                
				  } 
				return	$data ;			  
		   }
		  
		  function countchild($cid=null){
              $this->Category 	= TableRegistry::get('Categories');
			  return $this->Category->find()->where(['parent_id'=>$cid])->count() ; 
		  } 

		public function brands(){
					$this->Brand 	= TableRegistry::get('Brands');
					$Uploadfolder = Configure::read('Site.Uploadfolder');
					$siteurl = $Uploadfolder.'brands/';  
					$status  = 0 ;
					$userId = $this->request->data['user_id'];
					$userdata = $this->Users->find()->where(['id' => $userId])->first();
					$type = '2';
					$genderstatus ='0';
					if(!empty($userdata['gender'])){
						  if($userdata['gender']=='male'){
							  $type = '1';
						  }
						  $genderstatus ='1';
						    $brands = $this->Brand->find()->where(['type'=>$type,'is_top'=>'1'])->orWhere(['type'=>'3','is_top'=>'1'])->order(['is_top'=>'DESC'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN brands as b1 ON find_in_set(b1.name,interests.brands) where interests.user_id = '$userId' and Brands.id=b1.id)"])->hydrate(false)->all();
						//  $brands = $this->Brand->find()->where(['type'=>$type])->orWhere(['type'=>'3'])->order(['is_top'=>'DESC'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"CONCAT('0')" ]);
					  } else {
						  $brands = $this->Brand->find()->where(['is_top'=>'1'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN brands as b1 ON find_in_set(b1.name,interests.brands) where interests.user_id = '$userId' and Brands.id=b1.id)"])->hydrate(false)->all();
					  }
					if(!empty($brands)){
						 $status =1 ;
					}
					$this->set(compact('status','genderstatus','brands'));
				
		  }	
		  
		  public function brandstest(){
			        $this->autoRender = false;
					$this->Brand 	= TableRegistry::get('Brands');
					$Uploadfolder = Configure::read('Site.Uploadfolder');
					$siteurl = $Uploadfolder.'brands/';  
					$status  = 0 ;
					$userId = @$this->request->data['user_id'];
					$userId = 1;
					$userdata = $this->Users->find()->where(['id' => $userId])->first();
					$type = '2';
					$genderstatus ='0';
					if(!empty($userdata['gender'])){
						  if($userdata['gender']=='male'){
							  $type = '1';
						  }
						  $genderstatus ='1';
						    $brands = $this->Brand->find()->where(['type'=>$type,'is_top'=>'1'])->orWhere(['type'=>'3','is_top'=>'1'])->order(['is_top'=>'DESC'])->select(['id1','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN brands as b1 ON find_in_set(b1.name,interests.brands) where interests.user_id = '$userId' and Brands.id=b1.id)"])->hydrate(false)->all();
						//  $brands = $this->Brand->find()->where(['type'=>$type])->orWhere(['type'=>'3'])->order(['is_top'=>'DESC'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"CONCAT('0')" ]);
					  } else {
						  $brands = $this->Brand->find()->where(['is_top'=>'1'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN brands as b1 ON find_in_set(b1.name,interests.brands) where interests.user_id = '$userId' and Brands.id=b1.id)"])->hydrate(false)->all();
					  }
					if(!empty($brands)){
						 $status =1 ;
					}
					print_r($brands);
					$this->set(compact('status','genderstatus','brands'));
				
		  }	

         public function categories(){
			  if($this->request->is('post')){
				  //  $this->autoRender = false ;
				   // $userId = 1;
				    $userId = $this->request->data['user_id'];
					$userdata = $this->Users->find()->where(['id' => $userId])->first();
					$type = '0';
					$genderstatus ='0';
					 
				
					$this->Category 	= TableRegistry::get('categories');
					$Uploadfolder = Configure::read('Site.Uploadfolder');
			        
					$siteurl = $Uploadfolder.'categories/';  
					$status  = 0 ;
					 if(!empty($userdata['gender'])){
						  if($userdata['gender']=='male'){
							  $type = '1';
						  }
						  $genderstatus ='1';
						  $data = $this->Category->find()->order(['is_top'=>'DESC'])->select(['id','name','img_name','type','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN categories as c ON find_in_set(c.id,interests.categories) where interests.user_id = '$userId' and categories.id=c.id)"])->where("id not in (select parent_id from categories GROUP BY parent_id) and type=$type")->hydrate(false)->all();
					  } else {
						  $data = $this->Category->find()->order(['is_top'=>'DESC'])->select(['id','name','img_name','type','image'=>"CONCAT('$siteurl',img_name)",'isSelect'=>"(select count(DISTINCT interests.user_id) from interests left JOIN categories as c ON find_in_set(c.id,interests.categories) where interests.user_id = '$userId' and categories.id=c.id)"])->where("id not in (select parent_id from categories GROUP BY parent_id)")->hydrate(false)->all();
					  }
					
					if(!empty($data)){
						 $status =1 ;
					}
					
					$this->set(compact('status','genderstatus','data'));
			  }
				
		  }	
           function preferencesubcategory($id=null,$data=null){   
				$this->Category 	= TableRegistry::get('Categories');		   
			    $subcategories = $this->Category->find()->where(['parent_id'=>$id])->all();
				  if(!empty($subcategories)) {                   
						  foreach($subcategories as $subcategory){    
							                  
							  $countchild=$this->countchild($subcategory['id']); 
                              							  
							  if(!empty($countchild)){ 
                                   $data[]  =  $this->preferencesubcategory($subcategory['id']) ;                                  
							   }  else {
								   $data[]  = array('id'=>$subcategory['id'],'name'=>$subcategory['name']);  
							   }                                                                   
						  }                
				  } 
				return	$data ;			  
		   }
		  
          public function savecomment(){
			     if($this->request->is('post')){
					        $this->Comment = TableRegistry::get('Comments');
							$Type = $this->request->data['type'];
							$parentId = $this->request->data['parent_id'];
							$comment = $this->Comment->newEntity();
							$comment = $this->Comment->patchEntity($comment, $this->request->data);
							$comments = '' ;
							$userId = $this->request->data['user_id'];
							if ($this->Comment->save($comment)) {
								$msgstring = $AuthorId = '' ;
								if($Type=='0'){
									$PostData = $this->Post->find()->select(['id','user_id'])->where(['id'=>$parentId])->first();
									$AuthorId = $PostData['user_id'];
									$msgstring = " commented on your post."; 
									$this->send_notification($AuthorId);
									
								} else if($Type=='1'){
									$this->Set = TableRegistry::get('Sets') ;
									$PostData = $this->Set->find()->select(['id','user_id'])->where(['id'=>$parentId])->first();
									$AuthorId = $PostData['user_id'];
									$msgstring = " commented on a set you created.";
									
								}
							
								if(!empty($AuthorId)){
								   $AuthorData = $this->Users->find()->select(['notification_tokens'])->where(['id'=>$AuthorId])->first();
								   $Token = $AuthorData['notification_tokens'];
								   if(!empty($Token)){
									   $UserData = $this->Users->find()->select(['name'])->where(['id'=>$userId])->first();
									   $Username = $UserData['name'];
									   $msg = $Username.$msgstring;
									   @$this->send_notification($Token,$msg);
								   }
								}
								
								 $status ="1" ;
								 $message = 'Comment save successfully.' ;
								
							} else {
								 $status ="1" ;
								 $message = 'Comment could not be saved. Please, try again.' ;
							}
						 
							 $comments = $this->Comment->find()
										 ->hydrate(false)
										 ->join([
											 'table'=>'users',
											 'type' =>'left',
											 'conditions'=>'Comments.user_id=users.id',
										 ])->select(['id','content','username'=>'users.name','imgname'=>'users.img_path','posttime'=>"DATE_FORMAT(Comments.created, '%d-%M-%Y %h:%i:%s %p')"])->where(['parent_id'=>$parentId,'type'=>$Type])->order(['Comments.id'=>'DESC'])->all();
						 	
						
						$this->set(compact('status', 'message','comments'));
					 
				 }
		  }	
		  public function savelike(){
			     if($this->request->is('post')){
					    $userId = $this->request->data['user_id'];
						$parentId = $this->request->data['parent_id'];
						$Type = $this->request->data['type'];
						$isLike = $this->request->data['is_like'];
						$this->Like = TableRegistry::get('Likes');
						$likedata = $this->Like->find()->select(['id'])->where(['user_id'=>$userId,'parent_id'=>$parentId,'type'=>$Type])->first();
						if(!empty($likedata)){
							$likes = $likedata;
						} else {
							$likes = $this->Like->newEntity();
						}
						$likes = $this->Like->patchEntity($likes, $this->request->data);
						if ($this->Like->save($likes)) {
							$msgstring = $AuthorId = '' ;
							if($Type=='0'){
								$PostData = $this->Post->find()->select(['id','user_id'])->where(['id'=>$parentId])->first();
								$AuthorId = $PostData['user_id'];
								$msgstring = " likes your post."; 
								$this->send_notification($AuthorId);
								
							} else if($Type=='1'){
								$this->Set = TableRegistry::get('Sets') ;
								$PostData = $this->Set->find()->select(['id','user_id'])->where(['id'=>$parentId])->first();
								$AuthorId = $PostData['user_id'];
								$msgstring = " likes a set you created.";
								
							}
							
							if(!empty($AuthorId)){
							   $AuthorData = $this->Users->find()->select(['notification_tokens'])->where(['id'=>$AuthorId])->first();
							   $Token = @$AuthorData['notification_tokens'];
							   if(!empty($Token)){
								   $UserData = $this->Users->find()->select(['name'])->where(['id'=>$userId])->first();
								   $Username = $UserData['name'];
								   $msg = $Username.$msgstring;
								  $t1 =  @$this->send_notification($Token,$msg);
							   }
							}
							
							 $status ="1" ;
							 if($isLike==0){
								$message = 'Unlikes successfully.' ; 
							 } else {
								$message = 'Likes save successfully.' ;
							 }
							
						} else {
							 $status ="1" ;
							 $message = 'Like could not be saved. Please, try again.' ;
						}
						$this->set(compact('status','message'));
				 }
		  }	
		  
		  function send_notification($token=null, $data=null){
                    $url ='https://fcm.googleapis.com/fcm/send' ;
				    $serverKey = "AAAA4SoPtsc:APA91bHfohT-bNZEmr10XBUES2EujdyHF0_plpEcPpvWirrQ4TMiN7o1vmWOquIK1EGAXle5Gy_BAOfcrQLheDAfMHNcFqp3MgU1UDr1yP-3yl-sr_XoHoRGDR1swDW33vfhiSvKpPuB" ;
					$fields = array();
				    $message = array("text" =>$data,"priority"=>"high",'sound'=>"default");
					$fields = array(
					 'to' =>$token,
					 'notification' => $message,
					);
					$headers = array(
					  'Content-Type:application/json',
					  'Authorization:key='.$serverKey
					);
					
					  $ch = curl_init();
					  curl_setopt($ch,CURLOPT_URL,$url);
					  curl_setopt($ch,CURLOPT_POST,true);
					  curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
					  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
					  curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,0);
					  curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
					  curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($fields));
					  $result=curl_exec($ch);
				 
				  if($result === FALSE){
					  die('Curl failed: '.curl_error($ch)) ;
				  }
				  curl_close($ch);
				  
                  return $result ; 
				  
		}

		  public function productbycategoty(){
			    if($this->request->is('post')){
					   $categoryId 	= @$this->request->data['category_id'];
					   $userId 		= $this->request->data['user_id'] ;
					   $size 		= @$this->request->data['size'];
					   $price 		= @$this->request->data['price'];
					   $sortBy 		= @$this->request->data['sort_by'] ;
					   $nextpage = '2' ;
					   $searchdata	= array();
					   $searchdata['category_id'] = $categoryId ;
					   if(!empty($size)){
						   $searchdata['size'] = $size ;
					   }
					    if(!empty($price)){
						   $searchdata['price'] = $price ;
					   }
					   $status 		= 0 ;
					   $countProduct 	=  $this->Product->find()->where(['category_id'=>$categoryId])->count();
					   $sortquery = '' ;
					   if($sortBy==1){
						    $sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"ASC");
					   } else if($sortBy==2){
						    $sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"DESC");
					   } else if($sortBy==3){
						    $sortquery =array("CAST(avaragerating) as DECIMAL(10,2))"=>"DESC");
					   } else{
						    $sortquery =array("id"=>"DESC");
					   }
					    $data = $this->Product->find()->where($searchdata)->select(['id','name','size','image','price','offer_price','image','iswishlist'=>"(select count(DISTINCT wishlists.user_id) from wishlists left JOIN products as p1 ON find_in_set(p1.id,wishlists.products) where wishlists.user_id = '$userId' and Products.id=p1.id)"])->group('name')->order($sortquery)->hydrate(false)->all();
					   
                        if(!empty($data)){
						    $status = 1;
							$totalPages 	= ceil($countProduct/45);
					    }
					   $this->set(compact('status','nextpage','totalPages','data')); 
				 } 
		   }

			public function productbycategotyloadmore(){
			    if($this->request->is('post')){
					   $categoryId 	= @$this->request->data['category_id'] ;
					   $userId 		= @$this->request->data['user_id'] ;
					   $size 		= @$this->request->data['size'];
					   $price 		= @$this->request->data['price'];
					   $sortBy 		= @$this->request->data['sort_by']  ;
					   $filter 		= @$this->request->data['filter'] ;
					 
					  // $filter		= '{"price":["5000^10000","10000^15000"],"brands":["b1","b2"]}';
					 //  $filter		= '{"price":["0^1000","1000^5000","5000^10000","10000^15000"],"brands":["b1","b2"]}';
					 //  $filter		= array('price'=>array('0^1000','1001^5000','5000^10000','10000^15000'),'brands'=>array("b1",'b2'));
					   $filterdata = json_decode($filter);
					   $pricesoption =array() ;
					   $brands =array();
					   $pricestring = '' ;
					   $brandstring = '';
					 //  
					    if(!empty($filterdata)){
						   foreach($filterdata as $filtertype=>$filtervalue){
							   if(!empty($filtervalue)) {
								   if($filtertype=='Price'){
										 $priceoptions = $filterdata->Price ;
										 if(!empty($priceoptions)){
											 $pricestring ='(' ;
											 foreach($priceoptions as $pricekey=>$pricevalue){
												   $pricerange = explode('^',$pricevalue);
												   $pricefrom = $pricerange[0]+1;
												   $priceto = $pricerange[1];
												   $pricestring .="(offer_price between $pricefrom and $priceto) or ";
												   
											 }
										 
											 $pricestring = rtrim($pricestring," or ");
											 $pricestring .= ")";
											 $pricesoption = $priceoptions ;
										 }
								   } else if($filtertype=='Brands'){
										 $brands = $filterdata->Brands ;
										 if(!empty($brands)){
											  $brandstring .= "(" ;
											  foreach($brands as $brandkey=>$brandvalue){
												  $brandstring .="brands ='$brandvalue' or ";
											  }
											  $brandstring = rtrim($brandstring," or ");
											  $brandstring .= ")";
										 }
								   }
							    }
					        }
						}
							
					   
					   $searchdata	= array();
					   $searchdata['category_id'] = $categoryId ;
					   
					   $status 		= 0 ;
					   
					   
					    $sortquery = '' ;
						   if($sortBy==1){
								$sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"ASC");
						   } else if($sortBy==2){
								$sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"DESC");
						   } else if($sortBy==3){
								$sortquery =array("CAST(avaragerating as DECIMAL(10,2))"=>"DESC");
						   } else{
								$sortquery =array("id"=>"DESC");
						   }
						
						 // $pricestring = '' ;
						    if(!empty($pricestring) && (!empty($brandstring))){
								 $searchdata[] = $pricestring.' AND '.$brandstring ;
								
							} else if(!empty($pricestring)){
								$searchdata[] = $pricestring;
								
							} else if(!empty($brandstring)){
								$searchdata[] = $brandstring;
								
							}
					   // $searchdata
					   $data = $this->Product->find()->where($searchdata)->select(['id','name','brands','size','image','price','offer_price','image','iswishlist'=>"(select count(DISTINCT wishlists.user_id) from wishlists left JOIN products as p1 ON find_in_set(p1.id,wishlists.products) where wishlists.user_id = '$userId' and Products.id=p1.id)"])->group('name')->limit(45)->order($sortquery)->hydrate(false)->all();
                      
                       $countProduct =  $this->Product->find()->where($searchdata)->count();

					  if(!empty($data)){
						    $status = 1;
							$totalPages 	= ceil($countProduct/45);
					    }
						
						$nextpage = '1' ;
						if($totalPages>1){
							$nextpage = '2' ;
						}
						
						$inputdata = $this->request->data ;
					   $this->set(compact('status','nextpage','totalPages','data')); 
				 } 
		    }		   
		   
		    public function categoryloadmore(){
			     if($this->request->is('post')){ 
				      
						$categoryId 	= $this->request->data['category_id'] ;
						$page 			= $this->request->data['page'] ;
					    $userId 		= $this->request->data['user_id'] ;
			            $sortBy 		= @$this->request->data['sort_by'] ;
						$filter 		= @$this->request->data['filter'] ;
						
					   $filterdata = json_decode($filter);
					   $pricesoption =array() ;
					   $brands =array();
					   $pricestring = '' ;
					   $brandstring = '';
					    if(!empty($filterdata)){
						   foreach($filterdata as $filtertype=>$filtervalue){
							   if(!empty($filtervalue)) {
								   if($filtertype=='Price'){
										 $priceoptions = $filterdata->Price ;
										 if(!empty($priceoptions)){
											 $pricestring ='(' ;
											 foreach($priceoptions as $pricekey=>$pricevalue){
												   $pricerange = explode('^',$pricevalue);
												   $pricefrom = $pricerange[0]+1;
												   $priceto = $pricerange[1];
												   $pricestring .="(offer_price between $pricefrom and $priceto) or ";
												   
											 }
										 
											 $pricestring = rtrim($pricestring," or ");
											 $pricestring .= ")";
											 $pricesoption = $priceoptions ;
										 }
								   } else if($filtertype=='Brands'){
										 $brands = $filterdata->Brands ;
										 if(!empty($brands)){
											  $brandstring .= "(" ;
											  foreach($brands as $brandkey=>$brandvalue){
												  $brandstring .="brands ='$brandvalue' or ";
											  }
											  $brandstring = rtrim($brandstring," or ");
											  $brandstring .= ")";
										 }
								   }
							    }
					        }
						}
						 $searchdata	= array();
					     $searchdata['category_id'] = $categoryId ;
						 if(!empty($pricestring) && (!empty($brandstring))){
								 $searchdata[] = $pricestring.' AND '.$brandstring ;
								
						} else if(!empty($pricestring)){
								$searchdata[] = $pricestring;
							
						} else if(!empty($brandstring)){
								$searchdata[] = $brandstring;
							
						}
						
						$currentpage = $page-1 ;
						$nextpage 	 = $page+1 ;
						$status		 = 0 ;
						$limitto   	 =  $currentpage*45;
						$limitfrom 	 =  $limitto+45 ;
						 $sortquery = '' ;
						   if($sortBy==1){
								$sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"ASC");
						   } else if($sortBy==2){
								$sortquery =array("CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2))"=>"DESC");
						   } else if($sortBy==3){
								$sortquery =array("CAST(avaragerating) as DECIMAL(10,2))"=>"DESC");
						   } else{
								$sortquery =array("id"=>"DESC");
						   }
					    $data = $this->Product->find()->where($searchdata)->select(['id','name','brands','size','image','price','offer_price','image','iswishlist'=>"(select count(DISTINCT wishlists.user_id) from wishlists left JOIN products as p1 ON find_in_set(p1.id,wishlists.products) where wishlists.user_id = '$userId' and Products.id=p1.id)"])->group('name')->limit(45)->page($page)->order($sortquery)->hydrate(false)->all();
						if(!empty($data)){
							 $status =1;
						} 
					    $this->set(compact('status','nextpage','data')); 
				 }

		   }
		  
		   public function filteroption(){
			     // if($this->request->is('post')){ 
				       $categoryId 	= @$this->request->data['category_id']  ;
					   $productdata = $this->Product->find()->where(['category_id'=>$categoryId])->select(["min_price"=>"min(CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2)))","max_price"=>"max(CAST(REPLACE(offer_price, ',', '') as DECIMAL(10,2)))"])->hydrate(false)->first();
				       $minprice = "0" ;
					   $steps = 5;
					   $priceRange = array();
					   $maxprice = @$productdata['max_price']  ;
					   $delta = floor(($maxprice - $minprice) / $steps);
					   $price_steps = range($minprice, $maxprice, $delta);
					   array_pop($price_steps);
					   $totalrange = count($price_steps);
                       
						foreach($price_steps as $key=>$value){
								$nextkey =$key+1 ;
								if($maxprice<'6000'){
									$firstPrice =  round($value, -2)  ;
								} else {
									$firstPrice =  round($value, -3)  ;
								}
								// echo $key ;
								if($key==$totalrange-1){
									 $showprice ='over '.$firstPrice ;
									 $valueprice =$firstPrice.'^'."10000000" ;
									 $pricearray[] = 'over '.$value ;
									 $priceRange[$key]['key'] = $valueprice ;
									 $priceRange[$key]['name']= $showprice ;
									 $priceRange[$key]['type']= "Price" ;
									// $priceRange[] = array($showprice,$valueprice) ;
									
								} else {
									$nextrange =$price_steps[$nextkey] ;
									if($maxprice<'6000'){
										$secondPrice = round($nextrange, -2)  ;
										//$secondPrice = $nextrange  ;
									} else {
										$secondPrice = round($nextrange, -3)  ;
									}
									
									$pricearray[] = $value.' to '.$nextrange ;
									$showprice =$firstPrice.' to '.$secondPrice ;
									$valueprice =$firstPrice.'^'.$secondPrice ;
									$priceRange[$key]['key']= $valueprice ;
									$priceRange[$key]['name']= $showprice ;
									$priceRange[$key]['type']= "Price" ;
									// $priceRange[] = array($showprice,$valueprice) ;
								}
								
							}	
					    $data[0]['name'] = "Price" ;	
                        $data[0]['filteroption']= $priceRange ;
						$data[0]['type'] = "Price" ;						
               
				        $brands = $this->Product->find()->where(['category_id'=>$categoryId])->select(['key'=>'id','name'=>'brands','type'=>"CONCAT('Brands','')"])->group('brands')->hydrate(false)->all();	  
					    $data[1]['name'] = "Brands" ;
					    $data[1]['filteroption'] = $brands ;
						$data[1]['type'] = "Brands" ;						
					 
					   $this->set(compact('data','maxprice')); 
				//  }
			   
		   }
		  
          public function productbycategoty1(){
			    if($this->request->is('post')){
				     //  $categoryId 	= 3;
					//   $userId 		= 1;
					   $categoryId 	= $this->request->data['category_id'];
					   $userId 		= $this->request->data['user_id'];
					   $size 		= @$this->request->data['size'];
					   $price 		= @$this->request->data['price'];
					   $searchdata	= array();
					   $searchdata['category_id'] = $categoryId ;
					   if(!empty($size)){
						   $searchdata['size'] = $size ;
					   }
					    if(!empty($price)){
						   $searchdata['price'] = $price ;
					   }
					   $status 		= 0 ;
					   $data 	= $this->Product->find()->where($searchdata)->select(['id','name','size','image','price','offer_price','image','iswishlist'=>"(select count(DISTINCT wishlists.user_id) from wishlists left JOIN products as p1 ON find_in_set(p1.id,wishlists.products) where wishlists.user_id = '$userId' and Products.id=p1.id)"])->group('name')->hydrate(false)->all();
                     /*
					   $data = $this->Product->find()
									->hydrate(false)
									->join([
									   'table' =>'wishlists',
									   'type' =>'left',
									   'conditions' =>'find_in_set(Products.id,wishlists.products)'
									])->select(['id','name','image','price','offer_price','image','iswishlist'=>'(select count(wishlists.id) from wishlists where wishlists.user_id = 1)'])->where(['Products.category_id'=>$categoryId])->group('Products.id')->all();
					  */		 
					//   debug($data);
					   
					 //  $data 		= $this->Product->find()->where(['category_id'=>$categoryId])->select(['id','name','image','price','offer_price','image'])->all();
					   if(!empty($data)){
						    $status = 1;
					   }
					   $this->set(compact('status','data','userId')); 
				}
		  }	
         public function shop(){
			    if($this->request->is('post')){
						$this->Slider 		= TableRegistry::get('Sliders');
						$this->Categories 	= TableRegistry::get('Categories');
						$userId 			= $this->request->data['user_id'];
						$gender 			= $this->request->data['gender'] ;
						$type = '2' ;
						$genderstatus ='0';
						if(!empty($gender)){
							if($gender=='male'){
								$type = '1' ;
							}
							$genderstatus ='1';
						}
						$womenrecommend = array();
						$menrecommend = array();
						$userdetail = $this->Users->find()
									->hydrate(false)
									->join([
									   'table' =>'interests',
									   'type' =>'left',
									   'conditions' =>'Users.id=interests.user_id'
									])->select(['id','gender','brands'=>'interests.brands','categories'=>'interests.categories','upperSize'=>'interests.upper_size','lowerSize'=>'interests.lower_size','footwearSize'=>'interests.footwear_size'])->where(['Users.id'=>$userId])->first();
							 
						if(!empty($userdetail['gender'])){
							
						}
						
						$Uploadfolder 	= Configure::read('Site.Uploadfolder');
						$sliderurl 		= $Uploadfolder.'sliders/';
						$categoryurl 	= $Uploadfolder.'categories/';
						$mensliders 	= $this->Slider->find()->select(['id','img_name','imageurl'=>"CONCAT('$sliderurl',img_name)"])->hydrate(false)->where(['type'=>1])->all();	
						$womensliders 	= $this->Slider->find()->select(['id','img_name','imageurl'=>"CONCAT('$sliderurl',img_name)"])->hydrate(false)->where(['type'=>0])->all();						
						if(!empty($userdetail)){
							$brands 	= $userdetail['brands'];
							$categories = $userdetail['categories'];
							$categoriearray = explode(",", $categories);
							$brandarray = explode(",", $brands);
							if(!empty($gender)){
								$products = $this->Product->find()->where(['category_id IN' =>$categoriearray,'type IN'=>array($type,'3')])->orWhere(['brands IN' =>$brandarray,'type IN'=>array($type,'3')])->hydrate(false)->group('name')->limit(20)->order(['RAND()'])->all();
							} else {
								$products = $this->Product->find()->where(['category_id IN' =>$categoriearray])->orWhere(['brands IN' =>$brandarray])->hydrate(false)->group('name')->limit(20)->order(['RAND()'])->all();
							}
							$status = 1 ;
						} else {
							if(!empty($gender)){
								$products = $this->Product->find()->limit(20)->where(['type IN'=>array($type,'3')])->group('name')->order(['RAND()'])->all();
							} else {
								$products = $this->Product->find()->limit(20)->group('name')->order(['RAND()'])->all();
							}
						}
						
						$this->Brand 	= TableRegistry::get('Brands');
						$siteurl = $Uploadfolder.'brands/';  
						$status  = 0 ;
					
						$brandslist = $this->Brand->find()->where(['is_top'=>'1'])->select(['id','name','type','img_name','is_top','image'=>"CONCAT('$siteurl',img_name)"])->hydrate(false)->all();
						
						if($gender=='male'){
							 $menrecommend  = $products ;
						} else {
							 $womenrecommend = $products ;
						}
						 $conn = ConnectionManager::get('default');
						
						$notificationqry ="(select count(id) as friendrequest,(select count(comments.id) from posts left join comments on posts.id=comments.parent_id where posts.user_id='$userId' and comments.type=0 and comments.user_id !=$userId and posts.status=1 and comments.is_viewed=0 ) as postcommentscount,(select count(likes.id) from posts left join likes on posts.id=likes.parent_id where posts.user_id='$userId' and likes.user_id !=$userId and posts.status=1 and likes.is_viewed=0 and likes.type=0 ) as postlikecount,(select count(comments.id) from sets left join comments on sets.id=comments.parent_id where sets.user_id='$userId' and comments.type=1 and comments.user_id !=$userId and sets.status=1 and comments.is_viewed=0 ) as setcommentscount,(select count(likes.id) from sets left join likes on sets.id=likes.parent_id where sets.user_id='$userId' and likes.user_id !=$userId and sets.status=1 and likes.is_viewed=0 and likes.type=1 ) as setlikecount from friends where friend_id=$userId and status=0)" ;
						$notificationstmt = $conn->execute($notificationqry);
						$notification = $notificationstmt->fetchAll('assoc');
						
						$totalNotification =$notification[0]['friendrequest']+$notification[0]['postcommentscount']+$notification[0]['postlikecount']+$notification[0]['setcommentscount']+$notification[0]['setlikecount'];
						
						$mencategories 		= $this->Categories->find()->select(['id','name','slug','img_name','urlimage'=>"CONCAT('$categoryurl',img_name)"])->where(['type'=>1,'is_top'=>'1'])->hydrate(false)->limit(10)->all();
						$womencategories 	= $this->Categories->find()->select(['id','name','slug','img_name','urlimage'=>"CONCAT('$categoryurl',img_name)"])->where(['type'=>0,'is_top'=>'1'])->hydrate(false)->limit(10)->all();
						$this->set(compact('type','genderstatus','totalNotification','mensliders','womensliders','menrecommend','womenrecommend','mencategories','womencategories','brandslist'));
			
			   }
		  }		  
		  public function mainsearch(){
			    if($this->request->is('post')){
				            $genderstatus ='0';
					        $userId  = $this->request->data['user_id'];
					        $content = $this->request->data['content'];
							$mcontent =str_replace(' ','.+',$content);
							$gender  = $this->request->data['gender'] ;
							$type = '2' ;
							if(!empty($gender)){
								$genderstatus = '1' ;
							}
							
							if($gender=='male'){
								$type = '1' ;
							}
							$status = 0;
					        $Uploadfolder = Configure::read('Site.Uploadfolder');
						    $userurl = $Uploadfolder.'users/';
							$conn = ConnectionManager::get('default');
							
						  //  $qry ="select '0' as type,'0' as actionUserId,p.id,p.name,p.image,'0' as url,p.price as text2,'4' as fstatus from products as p where p.name like '%".$content."%' and type in('$type',3) union select '1' as type,IFNULL(f.action_user_id,0) as actionUserId,u.id,u.name,u.img_path as imgname,CONCAT('$userurl',u.img_path) as url,u.city as text2,IFNULL(f.status,3) as fstatus from users as u left join friends as f on u.id IN(f.user_id,f.friend_id) and '$userId' IN (f.friend_id, f.user_id)  where u.name like '%".$content."%' and u.id!=$userId group by u.id" ;
						    $qry ="select '0' as type,'0' as actionUserId,p.id,p.name,p.image,'0' as url,p.price as text2,'4' as fstatus from products as p where p.name REGEXP '$mcontent' and type in('$type',3) union select '1' as type,IFNULL(f.action_user_id,0) as actionUserId,u.id,u.name,u.img_path as imgname,CONCAT('$userurl',u.img_path) as url,u.city as text2,IFNULL(f.status,3) as fstatus from users as u left join friends as f on u.id IN(f.user_id,f.friend_id) and '$userId' IN (f.friend_id, f.user_id)  where u.name REGEXP '$mcontent' and u.id!=$userId group by u.id" ;
							$stmt = $conn->execute($qry);
							$data = $stmt ->fetchAll('assoc');
							if(!empty($data)){
								$status = 1;
							}
							$this->set(compact('status','genderstatus','data'));
					
				 }
			  
		  }
		   public function loadmore(){
			     if($this->request->is('post')){ 
			     
						$userId 	= $this->request->data['user_id'] ;
						$page 		= $this->request->data['page'] ;
						$currentpage = $page-1 ;
						$nextpage 	= '' ;
						$status		= 0 ;
						$limitto   	=  $currentpage*15;
						$limitfrom 	=  $limitto+15 ;
						 $Uploadfolder 	= Configure::read('Site.Uploadfolder');
						 $siteurl 	= $Uploadfolder.'posts/';  
						 $Url 		= Configure::read('Site.SiteUrl');
						 $shareurl 	= $Url.'posts/';  
					     $userurl 	= $Uploadfolder.'users/';
					     $conn 		= ConnectionManager::get('default');
						 $setshareurl = $Url.'sets/'; 
						 $setimageurl = $Uploadfolder.'setmain/';  
						  $friends = $this->Friend->find('all',array('conditions'=>array('user_id'=>$userId,'status'=>1)));
						   $author_ids = $userId.',';
						   if(!empty($friends)){
							   foreach($friends as $friend){
								  $author_ids .= $friend['friend_id'].','; 
							   }
						   }
						$author_ids = rtrim($author_ids,',') ;
						$qry 		="select posts.id,posts.created as orders ,CONCAT('',0) as type,posts.user_id,CONCAT('$shareurl',posts.id) as shareUrl,users.name as authorname,posts.width,posts.height,posts.content,users.img_path as authorimagename,CONCAT('$userurl',users.img_path) as authorimageurl,posts.img_name as postimagename,CONCAT('$siteurl',posts.img_name) as imageurl ,DATE_FORMAT(posts.created, '%d-%m-%Y %H:%i:%s %p') as posttime,(select count(comments.id) from comments left join posts as p2 on p2.id=comments.parent_id where (comments.parent_id=posts.id and comments.type=0)) as countcomment,(select count(likes.id) from likes left join posts as p3 on p3.id=likes.parent_id where (likes.parent_id=posts.id and likes.type=0 and likes.is_like=1)) as countlike,(select count(checklike.id) from likes as checklike left join posts as p4 on p4.id=checklike.parent_id where (checklike.parent_id=posts.id and checklike.type=0 and checklike.is_like=1 and checklike.user_id=$userId)) as isLike  from posts left join users on posts.user_id=users.id where posts.user_id IN ($author_ids) and posts.status=1 GROUP BY posts.id  UNION select sets.id,sets.created as orders,CONCAT('',1) as type,sets.user_id,CONCAT('$setshareurl',sets.id) as shareUrl,users.name as authorname,CONCAT('',600) as width,CONCAT('',600) as height,CONCAT('','') as content,users.img_path as authorimagename,CONCAT('$userurl',users.img_path) as authorimageurl,sets.img_name as postimagename,CONCAT('$setimageurl',sets.img_name) as imageurl,DATE_FORMAT(sets.created, '%d-%m-%Y %H:%i:%s %p') as posttime,(select count(comments.id) from comments left join sets as s2 on s2.id=comments.parent_id where (comments.parent_id=sets.id and comments.type=0)) as countcomment,(select count(likes.id) from likes left join sets as s3 on s3.id=likes.parent_id where (likes.parent_id=sets.id and likes.type=1 and likes.is_like=1)) as countlike,(select count(checklike.id) from likes as checklike left join sets as s4 on s4.id=checklike.parent_id where (checklike.parent_id=sets.id and checklike.type=1 and checklike.is_like=1 and checklike.user_id=$userId)) as isLike  from sets left join users on sets.user_id=users.id where sets.user_id IN ($author_ids) and sets.status=1 GROUP BY sets.id order by orders desc limit $limitto ,15 " ;
						$stmt 		= $conn->execute($qry);
						$posts 		= $stmt ->fetchAll('assoc');
						$countpost  = count($posts);
						
						if(!empty($posts)){
						   $status = 1 ;
						   $nextpage = $page+1 ;
					    } 
					
					    $this->set(compact('status','countpost','nextpage','posts')); 
				 }

		   }
		   
		   public function collectionsearch(){
			     if($this->request->is('post')){
				        $data 	  	= array();   
						$content  	= @$this->request->data['content'];
						$categoryId = @$this->request->data['categoryId'];
						$UserId 	= @$this->request->data['UserId'];
						$nextpage 	= '2' ;
						$status 	= 0 ;
						$totalPages = '' ;
						$countProduct = '' ;
						if((!empty($content))&&(!empty($categoryId))){
							$content = trim($content);
						 //	$mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
							$mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name REGEXP'=>$mcontent,'category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->hydrate(false)->all();
							$countProduct 	=  $this->Product->find()->where(['name REGEXP'=>$mcontent,'category_id'=>$categoryId])->count();
							
						} else if(!empty($categoryId)){
							  $data 	= $this->Product->find()->where(['category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->hydrate(false)->all();
							  $countProduct 	=  $this->Product->find()->where(['category_id'=>$categoryId])->count();
							
						} else if(!empty($content)){
							$content = trim($content);
						//	$mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
						    $mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name REGEXP'=>$mcontent])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->hydrate(false)->all();
							$countProduct 	=  $this->Product->find()->where(['name REGEXP'=>$mcontent])->count(); 
						}
						if(!empty($data)){
							$status = 1;
							$totalPages 	= ceil($countProduct/45);
						}
						$this->set(compact('status','nextpage','totalPages','data'));
					
				  }
		    }
			
			 public function collectionsearch1(){
				   $this->autoRender = false ;
			    // if($this->request->is('post')){
				        $data 	  	= array();  
						$content ="Limited Mens R-neck";
					//	$content  	= @$this->request->data['content'];
						$categoryId = @$this->request->data['categoryId'];
						$UserId 	= @$this->request->data['UserId'];
						$nextpage 	= '2' ;
						$status 	= 0 ;
						$totalPages = '' ;
						$countProduct = '' ;
						if((!empty($content))&&(!empty($categoryId))){
							$content = trim($content);
						//	$mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
						    $mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name RLIKE'=>$mcontent,'category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->hydrate(false)->all();
							$countProduct 	=  $this->Product->find()->where(['name REGEXP'=>$mcontent,'category_id'=>$categoryId])->count();
							
						} else if(!empty($categoryId)){
							  $data 	= $this->Product->find()->where(['category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->hydrate(false)->all();
							  $countProduct 	=  $this->Product->find()->where(['category_id'=>$categoryId])->count();
							
						} else if(!empty($content)){
							$content = trim($content);
							// $mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
							$mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name REGEXP'=>$mcontent])->select(['id','name','size','image','price','offer_price','image'])->group('name')->order(['id'=>'DESC'])->limit('45')->first();
							$countProduct 	=  $this->Product->find()->where(['name RLIKE'=>$mcontent])->count(); 
						}
						if(!empty($data)){
							$status = 1;
							$totalPages 	= ceil($countProduct/45);
						}
						print_r($data);
						$this->set(compact('status','nextpage','totalPages','data'));
					
				 // }
		    }
			
			public function collectionsearchloadmore(){
			     if($this->request->is('post')){ 
						$content  	= @$this->request->data['content'] ;
						$categoryId = @$this->request->data['categoryId'];
						$UserId 	= @$this->request->data['UserId'];
						$page 	  	= @$this->request->data['page'] ;
			            $status	  	=  0 ;
						$nextpage 	= '' ;
						$data 	  	= array();
						$currentpage = $page-1 ;
						$nextpage 	 = $page+1 ;
						
						
						if((!empty($content))&&(!empty($categoryId))){
							$content = trim($content);
						//	$mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
						    $mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name REGEXP'=>$mcontent,'category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->limit(45)->page($currentpage)->order(['id'=>'DESC'])->hydrate(false)->all();
							$countProduct 	=  $this->Product->find()->where(['name REGEXP'=>$mcontent,'category_id'=>$categoryId])->count();
							
						} else if(!empty($categoryId)){
							  $data 	= $this->Product->find()->where(['category_id'=>$categoryId])->select(['id','name','size','image','price','offer_price','image'])->group('name')->group('name')->limit(45)->page($currentpage)->order(['id'=>'DESC'])->hydrate(false)->all();
							  $countProduct 	=  $this->Product->find()->where(['category_id'=>$categoryId])->count();
							
						} else if(!empty($content)){
							$content = trim($content);
						//	$mcontent ='(?=.*'.str_replace(' ',')(?=.*',$content).')';
						    $mcontent =str_replace(' ','.+',$content);
							$data 	= $this->Product->find()->where(['name REGEXP'=>$mcontent])->select(['id','name','size','image','price','offer_price','image'])->group('name')->limit(45)->page($currentpage)->order(['id'=>'DESC'])->hydrate(false)->all();
							$countProduct 	=  $this->Product->find()->where(['name REGEXP'=>$mcontent])->count(); 
						}
						
						if(!empty($data)){
							 $status =1;
						} 
					    $this->set(compact('status','nextpage','data')); 
				 }

		    }
		  /* 
		    public function changebrand(){
				       $this->autoRender = false ;
					  $this->Brands = TableRegistry::get('Brandsdemo');
					  
					//  $brands = $this->Brands->find()->hydrate(false)->all();
					 $brands = $this->Brands->find()->where(['type'=>1])->hydrate(false)->all();
					  foreach($brands as $brand){
						 // print_r($brand); exit;
						  if(!empty($brand)){
						     $id = $name =  '' ;
							 $mbrands =$fbrand = $sbrand = $data = $d1 =$d3 = array();
						     $id = $brand['id'];
							 $name = $brand['name'];
							 $moreproducts = $this->Brands->find()->where(['name'=>$name,'id NOT IN'=>$id])->hydrate(false)->all();
							 
							 if(!empty($moreproducts)){
								    foreach($moreproducts as $moreproduct){
										
										$mid =  '' ;
										$mid = $moreproduct['id'];
										$mbrands = $this->Brands->get($mid);
										$d3 = $this->Brands->delete($mbrands) ;
										unset($mbrands);
										unset($d3);
										//print_r($d3); exit;
									}
									
								 $data['id'] = $id ;
								 $data['type'] = '3' ;
								 
								 $sbrand = @$this->Brands->find()->where(['id'=>$id])->first();
								 $fbrand = @$this->Brands->patchEntity($sbrand,$data);
								 
								 $d1 =  $this->Brands->save($fbrand);
								 unset($fbrand);
								 unset($data);
								 unset($sbrand);
                                 unset($d1);								 
							 }
						   //  echo '<pre>' ; print_r($brand); echo '</pre>' ;
						  }  
					  }
					 
					
			 }  */
			 
	   
   }
?>