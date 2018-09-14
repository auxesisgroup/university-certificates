<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Utility\HtmlDom;
// App::import('Vendor', 'simple_html_dom', array('file'=>'simple_html_dom.php'));
 require_once(ROOT . DS . 'vendor' . DS . 'simple_html_dom.php');
class StalkbuyloveShell extends Shell
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
			   // $Htmldom = new HtmlDom();
		       $html = $this->file_get_html("google.com");				 
		}	
		
		   public function saveUrl($products=null){
			       foreach($products as $product){
					   // print_r($product);
                        $data['url']  		= "https://www.myntra.com/".strtolower($product['dre_landing_page_url']) ;
						$data['type']  		= "M" ;
						$data['productid']  = $product['styleid'] ;
						$data['image']  	= $product['search_image'] ;
						$data['sizes']  	= $product['sizes'] ;
						$productarray 		= $this->Producturl->newEntity() ;
						$productdata 		= $this->Producturl->patchEntity($productarray,$data);
						$this->Producturl->save($productdata) ;
						$productarray = $data = $productdata = null ;
				   }
		   }
		   
           public function products(){
			      $productsurldata = $this->Producturl->find()->where(['type' =>"M",'is_read'=>'0'])->order(['id'=>'DESC'])->all();
				  foreach($productsurldata as $producturldata){
					  
					    $productId 	= $producturldata['productid'];
						$urlId 		= $producturldata['id'];
						$productUrl = $producturldata['url'];
						$image 	   	= $producturldata['image'];
						$sizes 	   	= $producturldata['sizes'];
						$isRead 	= $producturldata['is_read'];
						$Url ="https://www.myntra.com/web/product/$productId";
						$content  = @file_get_contents($Url);
						$product  = json_decode($content, TRUE); 
						if(!empty($product['id']))
						{  
					        $checkproduct = $this->checkproduct($product['id'],'Myntra');
							$status = $product['flags']['outOfStock']; 
							if(empty($checkproduct)&&($isRead==0)&&empty($status)){							
								$this->saveproduct($product,$productUrl,$image,$sizes,$producturldata);
								
							} else {
								 $urldata['is_read'] = 1 ;
								 $Urlpatch = $this->Product->patchEntity($producturldata,$urldata);
								 $this->Producturl->save($Urlpatch) ;
							}
						} 
						$product = $Urlpatch = $producturldata =null ;
				   }
		    }
		   
            public function saveproduct($product=null,$url=null,$image=null,$sizes=null,$producturldata=null){
				  //   echo "real: ".(memory_get_peak_usage(true)).",";
					$analytics = @$product['analytics']['gender'];
					$type = "";
					if($analytics=="Men"){
						$type = "1";
					} else if($analytics=="Women"){
						$type = "2";
					} else if($analytics=="Unisex"){
						$type = "3";
					}
					if(empty($sizes)){
						$sizesdata = $product['sizes'];
						foreach($sizesdata as $sizedata){
							if($sizedata['available']=='1'){
								$sizes .= $sizedata['label'].',' ;
							}
						}
						$sizes = rtrim($sizes,',');
					}
					
					$categorylabel 					= @$product['analytics']['articleType'];
					$subCategory 					= @$product['analytics']['subCategory'];
					if(($subCategory=="Bath and Body")||($subCategory=="Skin Care")){
						$categorylabel ="Bodycare";
						$type = "2";
					} else if($subCategory=="Hair"){
						$categorylabel ="Hair";
						$type = "2";
					} else if(($subCategory=="Makeup")||($subCategory=="Lips")){
						$categorylabel ="Makeup";
						$type = "2";
					} else if($subCategory=="Jewellery"){
						$categorylabel ="Jewellery";
						$type = "1";
					}
					
				    $categoryData 					= $this->getcategoryid($categorylabel,$type);
					$categoryId 					= $categoryData['catId'];
				    $productdata['category_id'] 	= @$categoryId ;
					$productdata['affiliate_type']  = 'Myntra';
					$productdata['type']  			= $type;
					$productdata['brands'] 			= @$product['brand']['name'];
					$productdata['size']  			= $sizes;
					$productdata['color']  			= @$product['colours'] ;
					$productdata['producturl']  	= "https://linksredirect.com/?pub_id=19596CL17626&source=linkkit&url=".$url ;
					$productdata['name']   			= @$product['name'] ;
					$productdata['price']  			= @$product['price']['mrp'];
					$productdata['offer_price']  	= $product['price']['discounted'];
					$productdata['availability'] 	= 1 ;
					
					$descriptiontitle = @$product['descriptors'][0]['title'];
					$description = '';
					if($descriptiontitle=="description"){
						$des = @$product['descriptors'][0]['description'];
						$description= preg_replace('#<a.*?>(.*?)</a>#i', '\1', $des);
						$spacialcar =array("<div>","</div>","<p>",'</p>','&nbsp;','<span>','<span>','<br>','<br />','<br/>');
						$description = str_replace($spacialcar,' ',$description);
					}
					$productdata['description'] 	= $description ;
					$productdata['productid'] 		= $product['id'];
					$productdata['size_variants'] 	= $sizes;
					$productdata['image'] 			= $image;
					if(!empty($categoryId)){
						 if(!empty($productdata['brands'])){
							$this->savebrand($productdata['brands'],$type);
						 }
						 $productarray = $this->Product->newEntity() ;
						 $productpatch = $this->Product->patchEntity($productarray,$productdata);
						 if($this->Product->save($productpatch)){
							// $urldata['id'] = $urlId ;
							 $urldata['is_read'] = 1 ;
							 $Urlpatch = $this->Product->patchEntity($producturldata,$urldata);
							 $this->Producturl->save($Urlpatch) ;
						 } 
					}
					$Urlpatch = $productpatch = $productarray = $productdata =null ;
			}	

         function getcategoryid($categorylabel=null,$type=null){
			    $catId ='' ;
				$this->autoRender =false ;
				
		
		}
			
        function savebrand($name=null,$type=null){
			  $this->autoRender = false ;
			  $brand = $this->Brand->find()->where(['name' =>$name,'type'=>$type])->first();
			  if(empty($brand)){
				     $data['name'] = $name ;
					 $data['type'] = $type ;
				     $newbrands = $this->Brand->newEntity() ;
					 $productpatch = $this->Brand->patchEntity($newbrands,$data);
					 $this->Brand->save($productpatch) ;
			  }
			  $productpatch = $newbrands = $data = $brand =null;
			  unset($productpatch);
			  unset($newbrands);
			  unset($data);
			  unset($brand);
			 
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