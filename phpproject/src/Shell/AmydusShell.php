<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
require_once(ROOT . DS . 'vendor' . DS . 'simple_html_dom.php');
class AmydusShell extends Shell
	{  
		 public function initialize()
		{
			parent::initialize();
			 $this->Category 	= 	TableRegistry::get('Categories');
			 $this->Producturl 	= 	TableRegistry::get('Producturl');
			 $this->Brand 		= 	TableRegistry::get('Brands');
			 $this->Product 	= 	TableRegistry::get('Products');
		}
		public function main()
		{
			$this->out('Hello world.');
		}

			public function uploadurl(){
				   $this->autoRender =false ;
				 
				 //  $Url = "https://www.amydus.com/women/topwear/tops.html?p=20";
				   
					$totalpages = 3 ;
					for($i=1;$i<=$totalpages;$i++){ 
						//  echo "real: ".(memory_get_peak_usage(true)/1024/1024).",";
					   
						  $Url 			= "https://www.amydus.com/men/shirts.html?p=$i";
						  $pagecontent 	= file_get_html($Url);
						  $products = $pagecontent->find('.products-grid .item') ;
						  
						  if(!empty($products)){
							 $this->saveUrl($products);
						  }
						
					}
				  					 
			}	
			
			   public function saveUrl($products=null){
					   foreach($products as $product){
						    
						 $price = $oldprice = $size = $Id = $priceId ='';
						 $data['name'] 		=  @$product->children[0]->attr['title'] ;
						 
						  if(!empty($data['name'])){
							   $sizecontent = $product->find('.sizeavailable') ;
								foreach($sizecontent[0]->children as $sizedata){
									$size .= $sizedata->innertext.','  ; 
								}
							   $size = rtrim($size,',');
							   $pricecontents = $product->children[3]->children;	  
							  foreach($pricecontents as $pricecontent){
								   $priceclass =  $pricecontent->attr['class'];
								   if($priceclass=='regular-price'){
										$priceId 	=  @$pricecontent->attr['id'];
										$oldprice = $price = $pricecontent->innertext ;
									} else {
									   if($priceclass=='old-price'){
											$oldprice =  $pricecontent->children[2]->innertext ;
									   } 
									   if($priceclass=='special-price'){
											 $priceId 	=  $pricecontent->children[2]->attr['id'];
											 $price = $pricecontent->children[2]->innertext ;
										}
									}
									$Id = str_replace('product-price-','',$priceId);
									$data['price'] = $oldprice ;
									$data['offer_price'] = $price ;
							   }
							 //  echo '<pre>' ; print_r($data); echo '</pre>' ; exit;
							   if(!empty($price) && !empty($Id)){
									$data['url']  		= @$product->children[0]->attr['href'].'?acc=5' ;
									$data['type']  		= "Amydus" ;
									$data['productid']  = $Id ;
									$data['gender']     = "M" ;
									$data['category']   = "shirts" ;
									$data['image']  	= $product->children[0]->children[0]->attr['src'] ;
									$data['sizes']  	= $size ;
									
									$productarray 		= $this->Producturl->newEntity() ;
									$productdata 		= $this->Producturl->patchEntity($productarray,$data);
									$this->Producturl->save($productdata) ;
									 
									$productarray = $data = $productdata = null ;
							   }
							    //	
							}
					   }
			   }
			   
			   public function products(){
					  $productsurldata = $this->Producturl->find()->where(['type' =>"Amydus",'is_read'=>'0','category'=>'top','gender'=>'F'])->order(['id'=>'DESC'])->all();
					  
					  foreach($productsurldata as $producturldata){
						  
							$productId 	= $producturldata['productid'];
							$urlId 		= $producturldata['id'];
							$productUrl = $producturldata['url'];
							$image 	   	= $producturldata['image'];
							$sizes 	   	= $producturldata['sizes'];
							$isRead 	= $producturldata['is_read'];
							
							$phtml  = file_get_html($productUrl);
							$detaildata = $phtml->find('#pd-accordion .detail') ;
							$productDetail = $detaildata[0]->innertext ;
							$namedata = $phtml->find('.product-name') ;
							$name = $namedata[0]->children[0]->innertext ;
							
							 
							$checkproduct = $this->checkproduct($productId,'Amydus');
							
							if(empty($checkproduct)&&($isRead==0)){							
								
									$productdata['category_id'] 	=  128 ;
									$productdata['affiliate_type']  = 'Amydus';
									$productdata['type']  			= 2;
									$productdata['size']  			= $sizes;
									$productdata['producturl']  	= $productUrl ;
									$productdata['name']   			= @$name ;
									$productdata['price']  			= $producturldata['price'];
									$productdata['offer_price']  	= $producturldata['offer_price'];
									$productdata['availability'] 	= 1 ;
									
									$des = $productDetail;
									$description= preg_replace('#<a.*?>(.*?)</a>#i', '\1', $des);
									$spacialcar =array("<div>","</div>","<p>",'</p>','&nbsp;','<span>','<span>','<br>','<br />','<br/>');
									$description = str_replace($spacialcar,' ',$description);
									
									$productdata['description'] 	= trim($description) ;
									$productdata['productid'] 		= $productId;
									$productdata['size_variants'] 	= $sizes;
									$productdata['image'] 			= $image;
									 
									//echo '<pre>' ; print_r($productdata); echo '</pre>' ; exit;
									
										 
										 $productarray = $this->Product->newEntity() ;
										 $productpatch = $this->Product->patchEntity($productarray,$productdata);
										 
										 if($this->Product->save($productpatch)){
											// $urldata['id'] = $urlId ;
											 $urldata['is_read'] = 1 ;
											 $Urlpatch = $this->Product->patchEntity($producturldata,$urldata);
											 $this->Producturl->save($Urlpatch) ;
										 } 
									
								
							} else {
								 $urldata['is_read'] = 1 ;
								 $Urlpatch = $this->Product->patchEntity($producturldata,$urldata);
								 $this->Producturl->save($Urlpatch) ;
							}
							
							$product = $Urlpatch = $producturldata =null ;
					   }
				}
			   
				public function saveproduct($product=null,$url=null,$image=null,$sizes=null,$producturldata=null){
					  //   echo "real: ".(memory_get_peak_usage(true)).",";
						
						$Urlpatch = $productpatch = $productarray = $productdata =null ;
				}	

			 
			 function checkproduct($productid=null,$affiliateType=null){
				// print_r($name); exit;
				$this->autoRender = false ;
				$product = $this->Product->find()->select(['id'])->where(['productid' =>$productid,'affiliate_type'=>$affiliateType])->first();
				return $product ;
				$product = null;			
				unset($product);			
			} 
	}
?>