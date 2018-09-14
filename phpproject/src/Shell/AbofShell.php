<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
require_once(ROOT . DS . 'vendor' . DS . 'simple_html_dom.php');

class AbofShell extends Shell
{  
     public function initialize()
    {
        parent::initialize();
        
		 $this->Brand 		= 	TableRegistry::get('Brands');
		 $this->Product 	= 	TableRegistry::get('Products');
    }
    public function main()
    {
        $this->out('Hello world.');
    }

        public function products(){
			   $this->autoRender =false ;
			   $Url = "http://www.abof.com/search/resources/store/10154/productview/byCategory/51515?&page=2&profileName=mABOF_findProductsByCategory&catalogId=10101&searchSource=E&channel=Desktop&currency=INR&responseFormat=json&langId=-1&pageNumber=1&&pageSize=50&"; 
			   
			   $pagecontent = file_get_contents($Url);
			   $results = json_decode($pagecontent, TRUE);
			   
			   if(!empty($results['catalogEntryView'])){
				    $totalproducts= $results['recordSetTotal'] ;
					$totalpages = ceil(@$totalproducts/50) ;
					
				for($i=1;$i<=$totalpages;$i++){ 
						//  echo "real: ".(memory_get_peak_usage(true)/1024/1024).",";
					   // print_r($i);
						  $Url 			= "http://www.abof.com/search/resources/store/10154/productview/byCategory/51515?&page=2&profileName=mABOF_findProductsByCategory&catalogId=10101&searchSource=E&channel=Desktop&currency=INR&responseFormat=json&langId=-1&pageNumber=$i&&pageSize=50&";
						  $pagecontent 	= file_get_contents($Url);
						  $results 		= json_decode($pagecontent, TRUE);
						  $products 	= $results['catalogEntryView'] ;
						 
						  if(!empty($products)){
							  
							   foreach($products as $product){
								    
								    $checkproduct = $this->checkproduct($product['uniqueID'],'Abof');
									if(empty($checkproduct)){
										 $this->saveproduct($product); 
									}
							   }
						  }
						//  exit;
					}
			   }					 
		}	
		
            public function saveproduct($product=null){
					
					 $partNumber = $product['partNumber'];
					 $productName =  $product['name'];
					 $replaceFrom = array(' ','&');
					 $replaceTo = array('-','and');
					
					 $urlName =str_replace($replaceFrom,$replaceTo,$productName);
					 $detailpageurl ='http://www.abof.com/product/'.$partNumber.'-'.$urlName ;
					
					
					$phtml = file_get_html($detailpageurl);
					$detailspage = $phtml->find('.size-select') ;
					
					
					$sizes ='';
					if(!empty($detailspage)){
						foreach($detailspage as $sizeoption){
							$size  = @$sizeoption->innertext;
							$sizes .= @$size.',' ;
							
						}	
						$sizes = rtrim($sizes,',');
					}
					
					
				    $productdata['category_id'] 	= "116" ;
					$productdata['affiliate_type']  = 'Abof';
					$productdata['type']  			= 2;
					$productdata['brands'] 			= @$product['manufacturer'];
					$productdata['size']  			= $sizes;
					$productdata['producturl']  	= "https://linksredirect.com/?pub_id=19596CL17626&source=linkkit&url=".$detailpageurl ;
					$productdata['name']   			= @$product['name'] ;
					
					
					if(!empty(@$product['price']['0'])){
						$productdata['price']  			= @$product['price']['0']['value'];
					}
					if(!empty(@$product['price']['1'])){
						$productdata['offer_price']  	= @$product['price']['1']['value'];
					} else {
						$productdata['offer_price']  	= @$product['price']['0']['value'];
					}
					$productdata['availability'] 	= 1 ;
					
					$productdata['description'] 	= $product['shortDescription'] ;
					$productdata['productid'] 		= $product['uniqueID'];
					$productdata['size_variants'] 	= $sizes;
					$productdata['image'] 			= "http://images.abofcdn.com/".$product['front_large'];
					// echo "<pre>" ; print_r($productdata); echo "</pre>" ;  
					 if(!empty($productdata['brands'])){
							$this->savebrand($productdata['brands'],2);
						 }
						 $productarray = $this->Product->newEntity() ;
						 $productpatch = $this->Product->patchEntity($productarray,$productdata);
						 if($this->Product->save($productpatch)){
							
						 } 
					
					$Urlpatch = $productpatch = $productarray = $productdata =null ;
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
			
			$this->autoRender = false ;
			$product = $this->Product->find()->select(['id'])->where(['productid' =>$productid,'affiliate_type'=>$affiliateType])->first();
			return $product ;
			$product = null;			
            unset($product);			
		}
}
?>