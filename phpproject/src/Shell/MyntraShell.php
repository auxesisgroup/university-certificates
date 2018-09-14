<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Utility\Flipkart;
class MyntraShell extends Shell
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
			   $mendata = array('tshirts'=>'men-tshirts','casual-shirts'=>'men-casual-shirts','formal-shirts'=>'men-formal-shirts','jackets'=>'men-jackets','sweatshirts'=>'men-sweatshirts','blazers'=>'men-blazers');
		     
				  //  Ho gaya   // ,men-jeans,men-briefs-and-trunks,men-blazers,men-jewellery,men-track-pants,men-sports-jackets,men-nightwear,men-flip-flops,men-belts-ties,men-sandals,sherwani,men-socks,men-sports-shoes,mens-watches,men-casual-shoes,men-innerwear-vests,mens-shorts,men-kurtas,men-belts,men-caps,men-gloves-mufflers-scarves,men-bags-backpacks,men-tshirts,men-casual-shirts

				// Nahi hua :  men-formal-trousers,men-casual-trousers,men-boxers,
		  
			  // lehenga-choli-menu ,fusion-skirts-trousers-menu,women-shirts-tops-tees,women-gloves-mufflers-scarves-menu,sunglasses-and-frames-women-menu,luggage-and-trolley-bags-menu,women-ethnic-wear-jackets,skirts,women-jackets,women-blazers,women-jeans-jeggings,women-shrug-menu,kurtas-and-suits-menu,'dupatta-shawl-menu',dress-material-menu,'bras-and-sets-menu',leggings-churidar-salwar-menu,women-western-bottomwear-menu,sarees-and-blouses-menu,tops-and-tees-menu,women-belts-menu,sleep-and-lounge-wear-women-menu,flats-and-casual-shoes-menu,sports-footwear-women-menu,briefs-women-menu,sweaters-and-sweatshirts-women-menu

			  	// array('sports-footwear-women-menu','women-socks-menu');
			   $Url = "https://www.myntra.com/web/v2/search/data/men-sports-shoes?f=&p=1&rows=48";
			   $pagecontent = file_get_contents($Url);
			   $results = json_decode($pagecontent, TRUE);
			   
			   if(!empty($results['data']['results']['totalProductsCount'])){
					$totalProductsCount =  $results['data']['results']['totalProductsCount'] ;
					$recordprepage = "48" ;
					$totalpages = $totalProductsCount/$recordprepage ;
					
					for($i=1;$i<=$totalpages;$i++){ 
						//  echo "real: ".(memory_get_peak_usage(true)/1024/1024).",";
					   // print_r($i);
						  $Url 			= "https://www.myntra.com/web/v2/search/data/men-sports-shoes?f=&p=$i&rows=48";
						  $pagecontent 	= file_get_contents($Url);
						  $results 		= json_decode($pagecontent, TRUE);
						  $products 	= $results['data']['results']['products'] ;
						  
						  if(!empty($products)){
							 $this->saveUrl($products);
						  }
						//  exit;
					}
			   }					 
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
				 if($type=='1'){
					   if($categorylabel=='Tshirts'){
							$catId ='49';
					   } else if($categorylabel=='Ties'){
							$catId ='71';
					   } else if(($categorylabel=='Lounge Pants')||($categorylabel=='Track Pants')||($categorylabel=='Trousers')||($categorylabel=='Lounge Set')||($categorylabel=='Pyjamas')){
							$catId ='80';
					   } else if($categorylabel=='Jewellery'){
							$catId ='70';
					   } else if($categorylabel=='Cufflinks'){
							$catId ='73';
					   } else if($categorylabel=='Blazers'){
							$catId ='60';
					   } else if($categorylabel=='Dhotis'){
							$catId ='66';
					   } else if($categorylabel=='Belts'){
							$catId ='68';
					   } else if(($categorylabel=='Caps')||($categorylabel=='Hat')){
							$catId ='74';
					   } else if($categorylabel=='Mufflers'){
							$catId ='75';
					   } else if($categorylabel=='Gloves'){
							$catId ='76';
					   } else if($categorylabel=='Backpacks'){
							$catId ='84';
					   } else if($categorylabel=='Sweatshirts'){
							$catId ='112';
					   } else if($categorylabel=='Sweaters'){
							$catId ='113';
					   } else if(($categorylabel=='Jackets')||($categorylabel=='Rain Jacket')){
							$catId ='67';
					   } else if($categorylabel=='Shirts'){
							$catId ='59';
					   } else if($categorylabel=='Messenger Bag'){
							$catId ='90';
					   } else if($categorylabel=='Laptop Bag'){
							$catId ='87';
					   } else if($categorylabel=='Jeans'){
							$catId ='51';
					   } else if($categorylabel=='Briefs'){
							$catId ='79';
					   } else if($categorylabel=='Trunk'){
							$catId ='83';
					   } else if($categorylabel=='Boxers'){
							$catId ='81';
					   } else if($categorylabel=='Watches'){
							$catId ='37';
					   } else if($categorylabel=='Innerwear Vests'){
							$catId ='78';
					   } else if($categorylabel=='Sports Shoes'){
							$catId ='39';
					   } else if($categorylabel=='Casual Shoes'){
							$catId ='41';
					   } else if($categorylabel=='Formal Shoes'){
							$catId ='40';
					   } else if($categorylabel=='Flip Flops'){
							$catId ='43';
					   } else if($categorylabel=='Sherwani'){
							$catId ='64';
					   } else if($categorylabel=='Socks'){
							$catId ='72';
					   } else if(($categorylabel=='Kurtas')||($categorylabel=='Kurta Sets')){
							$catId ='63';
					   } else if(($categorylabel=='Sandals')||($categorylabel=='Sports Sandals')){
							$catId ='44';
					   } else if(($categorylabel=='Shorts')||($categorylabel=='Lounge Shorts')){
							$catId ='57';
					   } else if(($categorylabel=='Waistcoat') ||($categorylabel=='Coats')){
							$catId ='61';
					   } else if(($categorylabel=='Sunglasses')||($categorylabel=='Frames')){
							$catId ='69';
					   } else if(($categorylabel=='Duffel Bag')||($categorylabel=='Trolley Bag')||($categorylabel=='Rucksacks')){
							$catId ='100';
					   } 
					 
				 } else if($type=='2'){
					   if($categorylabel=='Heels'){
							$catId ='18';
					   } else if($categorylabel=='Kurtas'){
							$catId ='116';
					   } else if($categorylabel=='Kurta Sets'){
							$catId ='117';
					   } else if($categorylabel=='Sarees'){
							$catId ='30';
					   } else if($categorylabel=='Saree Blouse'){
							$catId ='121';
					   } else if($categorylabel=='Lehenga Choli'){
							$catId ='119';
					   } else if($categorylabel=='Shrug'){
							$catId ='138';
					   } else if(($categorylabel=='Jeans')||($categorylabel=='Jeggings')){
							$catId ='133';
					   }  else if($categorylabel=='Dress Material'){
							$catId ='118';
					   } else if($categorylabel=='Leggings'){
							$catId ='129';
					   } else if($categorylabel=='Bra'){
							$catId ='91';
					   } else if($categorylabel=='Tshirts'){
							$catId ='137';
					   } else if(($categorylabel=='Shirts')||($categorylabel=="Tunics")||($categorylabel=='Tops')){
							$catId ='128';
					   } else if($categorylabel=='Dupatta'){
							$catId ='126';
					   } else if($categorylabel=='Shawl'){
							$catId ='148';
					   } else if($categorylabel=='Sports Shoes'){
							$catId ='9';
					   } else if($categorylabel=='Sports Sandals'){
							$catId ='14';
					   } else if($categorylabel=='Casual Shoes'){
							$catId ='11';
					   } else if($categorylabel=='Flats'){
							$catId ='17';
					   } else if($categorylabel=='Flip Flops'){
							$catId ='13';
					   } else if(($categorylabel=='Watches')||($categorylabel=='Smart Watches')){
							$catId ='29';
					   } else if($categorylabel=='Belts'){
							$catId ='104';
					   } else if($categorylabel=='Sweaters'){
							$catId ='144';
					   } else if($categorylabel=='Sweatshirts'){
							$catId ='145';
					   } else if($categorylabel=='Handbags'){
							$catId ='94';
					   } else if($categorylabel=='Clutches'){
							$catId ='95';
					   } else if($categorylabel=='Wallets'){
							$catId ='156';
					   } else if($categorylabel=='Trousers'){
							$catId ='141';
					   } else if($categorylabel=='Capris'){
							$catId ='157';
					   } else if(($categorylabel=='Nightdress')||($categorylabel=='Night suits')||($categorylabel=='Lounge Set')||($categorylabel=='Sleep Shirts')||($categorylabel=='Robe')||($categorylabel=='Pyjamas')){
							$catId ='93';
					   } else if($categorylabel=='Briefs'){
							$catId ='92';
					   } else if(($categorylabel=='Lipstick')||($categorylabel=='Nail Polish')||($categorylabel=='Makeup')||($categorylabel=='Highlighter and Blush')){
							$catId ='152';
					   } else if($categorylabel=='Perfume and Body Mist'){
							$catId ='155';
					   } else if(($categorylabel=='Face Moisturiser and Night Cream')||($categorylabel=='Bodycare')){
							$catId ='153';
					   } else if($categorylabel=='Hair'){
							$catId ='154';
					   } else if($categorylabel=='Skirts'){
							$catId ='132';
					   } else if($categorylabel=='Palazzos'){
							$catId ='158';
					   } else if($categorylabel=='Jackets'){
							$catId ='146';
					   } else if($categorylabel=='Shorts'){
							$catId ='136';
					   } else if($categorylabel=='Blazers'){
							$catId ='131';
					   } else if($categorylabel=='Coats'){
							$catId ='149';
					   } else if($categorylabel=='Stoles'){
							$catId ='140';
					   } else if($categorylabel=='Scarves'){
							$catId ='107';
					   } else if(($categorylabel=='Duffel Bag')||($categorylabel=='Trolley Bag')||($categorylabel=='Rucksacks')){
							$catId ='100';
					   } else if(($categorylabel=='Sunglasses')||($categorylabel=='Frames')){
							  $catId ='69';
					   } else if($categorylabel=='Socks'){
							$catId ='108';
					   }
				 } else if($type=='3'){
					   if($categorylabel=='Backpacks'){
							$catId ='98';
					   } else if(($categorylabel=='Sunglasses')||($categorylabel=='Frames')){
							  $catId ='69';
					   } else if(($categorylabel=='Duffel Bag')||($categorylabel=='Trolley Bag')||($categorylabel=='Rucksacks')){
							$catId ='100';
					   } else if(($categorylabel=='Jackets')||($categorylabel=='Rain Jacket')){
							$catId ='67';
					   } else if($categorylabel=='Socks'){
							$catId ='108';
					   }
				  }
				
				$data = array('catId'=>$catId,'type'=>$type);
				return $data ;
		
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