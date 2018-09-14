<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
require_once(ROOT . DS . 'vendor' . DS . 'simple_html_dom.php');

class LimeroadShell extends Shell
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
				  
			   $firstpageUrl = "https://www.limeroad.com/listing/get_listing_objects.json?p_queryparam=%7B%22classification%22%3A%5B%22.0.1116.1217.1248.1254%22%5D%2C%22stock%22%3A%5B%221%22%5D%7D&p_sortorder=threeQuarterStock_i%2Bdesc%2CscCount%2Bdesc&st_queryparam=undefined&st_queryparam_or=undefined&p_groupoffset=null&p_start=30&p_rows=10&p_searchquery=%2A%3A%2A&s_queryparam=%7B%22classification%22%3A%5B%22.0.1116.1217.1248.1254%22%5D%2C%22stock%22%3A%5B%221%22%5D%7D&tag=&isScrapOnlySearch=&s_sortorder=priority%2Bdesc%2Crandom_5034324%2Bdesc&tag_condition=&ext_img=false&template=&badge=&catEditor=&gender=&disableFixedScraps=&tagPageStories=&s_start=1&s_rows=1&df_type=null&s_searchquery=%2A%3A%2A&issearch=false&group=false&product_id=ms14197&story_id=ms1254st&newUserSort=undefined&story_start=0&story_rows=3&stories=false&src_id=&fetchDynAsWell=true&scrollAjaxCall=true&promoteFixedStories=true&tag_sort=";
			   $firstpagecontent = file_get_contents($firstpageUrl);
			   $firstresults = json_decode($firstpagecontent, TRUE);
			   $totalpages= '' ;
			   if(!empty($firstresults['products']['response']['docs'])){
				       $totalProduct = $firstresults['products']['response']['numFound'];
				    
				 if(!empty($totalProduct)){
					   $totalproducts = (int)$totalProduct ;
					   $totalpages = ceil($totalproducts/10) ;
					 
				 }
					//$totalpages = '4';
					$pagecontent = '' ;
					$results = array();
				   for($i=2;$i<=$totalpages;$i++){ 
				         // echo $i ;
						//  echo "real: ".(memory_get_peak_usage(true)/1024/1024).",";
					   // print_r($i);
					     // echo $i ;
					      $sStart 		= $i ;
						//  $storyStart   = 3*$i ;
						  $pStart 		= $i*10 ;
						  $Url ="" ;
						  $Url 			= "https://www.limeroad.com/listing/get_listing_objects.json?p_queryparam=%7B%22classification%22%3A%5B%22.0.1116.1217.1248.1254%22%5D%2C%22stock%22%3A%5B%221%22%5D%7D&p_sortorder=threeQuarterStock_i%2Bdesc%2CscCount%2Bdesc&st_queryparam=undefined&st_queryparam_or=undefined&p_groupoffset=null&p_start=$pStart&p_rows=10&p_searchquery=%2A%3A%2A&s_queryparam=%7B%22classification%22%3A%5B%22.0.1116.1217.1248.1254%22%5D%2C%22stock%22%3A%5B%221%22%5D%7D&tag=&isScrapOnlySearch=&s_sortorder=priority%2Bdesc%2Crandom_5034324%2Bdesc&tag_condition=&ext_img=false&template=&badge=&catEditor=&gender=&disableFixedScraps=&tagPageStories=&s_start=$sStart&s_rows=1&df_type=null&s_searchquery=%2A%3A%2A&issearch=false&group=false&product_id=ms14197&story_id=ms1254st&newUserSort=undefined&story_start=3&story_rows=3&stories=false&src_id=&fetchDynAsWell=true&scrollAjaxCall=true&promoteFixedStories=true&tag_sort=";
						  $pagecontent 	= @file_get_contents($Url);
						  $results 		= json_decode($pagecontent, TRUE);
						  $products 	= @$results['products']['response']['docs'] ;
						   
						  if(!empty($products)){
							  
							   foreach($products as $product){
								   
								    $checkproduct = $this->checkproduct($product['id'],'Limeroad');
								
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
					
					$detailpageurl ='https://www.limeroad.com'.$product['seoUrl'] ;
					
					$phtml = file_get_html($detailpageurl);
					$imagedata = @$phtml->find('.product_image') ;
					$detailspage = @$phtml->find('.size') ;
					$sizes ='';
					if(!empty($detailspage)){
						foreach($detailspage as $sizeoption){
							$size  = trim(@$sizeoption->innertext);
							$sizes .= @$size.',' ;
							
						}	
						$sizes = rtrim($sizes,',');
					}
					
					
				    $productdata['category_id'] 	= "133" ;
					$productdata['affiliate_type']  = 'Limeroad';
					$productdata['type']  			= 2;
					$productdata['brands'] 			= @$product['brand'];
					$productdata['size']  			= $sizes;
					$productdata['producturl']  	= "https://linksredirect.com/?pub_id=19596CL17626&source=linkkit&url=".$detailpageurl ;
					$productdata['name']   			= @$product['name'] ;
					
					$productdata['price']  			= @$product['price'];
					
					if(!empty(@$product['discounted']==true)){
						$productdata['offer_price']  	= @$product['selling_price'];
					} else {
						$productdata['offer_price']  	= @$product['price'];
					}
					
					
					$productdata['availability'] 	= 1 ;
					
					$productdata['description'] 	= $product['description'] ;
					$productdata['productid'] 		= $product['id'];
					$productdata['size_variants'] 	= $sizes;
					
					// echo "<pre>" ; print_r($productdata); echo "</pre>" ; 
					$productdata['image'] = '' ;
					if(!empty(@$imagedata['0'])){
						$productdata['image'] 			= "https:".$imagedata['0']->attr['data-large'];
					}
					
						 if(!empty($productdata['brands'])){
							$this->savebrand($productdata['brands'],$type);
						 }
						 $productarray = $this->Product->newEntity() ;
						 $productpatch = $this->Product->patchEntity($productarray,$productdata);
						 if(!empty($productdata['image'])){
							$this->Product->save($productpatch);
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