<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class VoonikShell extends Shell
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
				  
			   $Url = "https://www.voonik.com/women-clothing/skirts.json?limit=24&page=1";
			   $pagecontent = file_get_contents($Url);
			   $results = json_decode($pagecontent, TRUE);
			   
			   if(!empty($results['results'])){
					$totalpages = @$results['page_config']['id']['0'] ;
					
					for($i=1;$i<=$totalpages;$i++){ 
						//  echo "real: ".(memory_get_peak_usage(true)/1024/1024).",";
					   // print_r($i);
						  $Url 			= "https://www.voonik.com/women-clothing/skirts.json?limit=24&page=$i";
						  $pagecontent 	= file_get_contents($Url);
						  $results 		= json_decode($pagecontent, TRUE);
						  $products 	= $results['results'] ;
						 
						  if(!empty($products)){
							  
							   foreach($products as $product){
								   
								    $checkproduct = $this->checkproduct($product['id'],'Voonik');
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
				  //   echo "real: ".(memory_get_peak_usage(true)).",";
					
					$type = "2";
					$productsizes =@$product['sizes'] ;
					$sizes ='';
					if(!empty($productsizes)){
						
						foreach($productsizes as $productsize){
							
						     $sizes .= @$productsize['name'].',' ;
							
						}
						$sizes = rtrim($sizes,',');
					}
					
					
					$url = "https://www.voonik.com/recommendations/".$product['permalink'] ;
				    $productdata['category_id'] 	= "137" ;
					$productdata['affiliate_type']  = 'Voonik';
					$productdata['type']  			= 2;
					$productdata['brands'] 			= @$product['brand'];
					$productdata['size']  			= $sizes;
					$productdata['producturl']  	= "https://linksredirect.com/?pub_id=19596CL17626&source=linkkit&url=".$url ;
					$productdata['name']   			= @$product['name'] ;
					$productdata['price']  			= ltrim(@$product['original_price'],'Rs. ');
					$productdata['offer_price']  	= ltrim(@$product['price'],'Rs. ');
					$productdata['availability'] 	= 1 ;
					
					$productdata['description'] 	= $product['description'] ;
					$productdata['productid'] 		= $product['id'];
					$productdata['size_variants'] 	= $sizes;
					$productdata['image'] 			= "https:".$product['image'];
					
						 if(!empty($productdata['brands'])){
							$this->savebrand($productdata['brands'],$type);
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