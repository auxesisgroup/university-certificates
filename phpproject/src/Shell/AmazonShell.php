<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class AmazonShell extends Shell
{  
     public function initialize()
    {
        parent::initialize();
         $this->Category 	= 	TableRegistry::get('Categories');
		 $this->Product 	= 	TableRegistry::get('Products');
		 $this->Brand 		= 	TableRegistry::get('Brands');
    }
    public function main()
    {
        $this->out('Hello world.');
    }

      public function upload(){
			 $this->autoRender =false ;
			   $TotalPages = 10 ;
			   $productdata = array();
			  //   $ManCategory = array('2454169031','1983356031','2917451031','2454169031','2917497031','2917442031','1983351031');
			 //  $ManCategory = array('1968120031','1968093031','1968076031','1968032031','1968097031','1968062031','1968126031','1968103031','1968098031','1968025031','1968248031','1968249031','7124359031','1968125031','1968088031','1968107031','11960414031','1968077031','1571272031','1968082031','1983519031','1983572031','1983577031','1983573031','1983575031','1983568031','1983571031','1983576031','1983567031','2563504031','1350388031','1968036031','1571283031','1571284031','1983396031','1983518031','1983550031') ;
		      //  $WomenCategory = array('1968255031','1968256031','3723380031','1968457031','1968467031','1968474031','1968542031','1968445031','1968447031','1968547031','1968505031','1968449031','11400133031','1571271031','1597453031','1597454031','1400136031','1968428031','1983633031','1983631031','1983639031','9780815031','1983629031','1983579031','1983638031','1983627031','4068645031','1983634031','1968401031','1953602031','1597455031','7424748031','1350387031','1499791031','1499793031','4371849031','2563505031','4371850031','5210069031','3044925031','1983355031','2917497031') ;
			    $ManCategory = array('1968255031') ;
			   
			   $sorts =array('reviewrank_authority','relevancerank','price','-price');
			   foreach($ManCategory as $key=>$amzcatId){
				   for($i=1;$i<$TotalPages;$i++){
					   foreach($sorts as $skey=>$svalue){
					       $productdata[]= $this->getnextpagerecord($i,$TotalPages,$amzcatId,$svalue);
						   
						   $productdata = null ;
					   }
					 
				   }
			   } 
			  //  $productdata[]= $this->getnextpagerecord(1,10,'1968107031');
			  //  echo '<pre>';  print_r($productdata); echo '</pre>'; 
			 
		 }
		 
		 
		 function getnextpagerecord($i=null,$TotalPage=null,$amzcatId=null,$sort=null){

				$aws_access_key_id = "AKIAIPBO32C5TQNJLD7Q";
				// Your AWS Secret Key corresponding to the above ID, as taken from the AWS Your Account page
				$aws_secret_key = "e7Y6n+QUY3Z0ZRAI65TFNBNrkWscuB5axx6WlvVw";
				// The region you are interested in
				$endpoint = "webservices.amazon.in";

				$uri = "/onca/xml";
				$amzcatId ="";

				$params = array(
				   "Service" => "AWSECommerceService",
					"Operation" => "ItemSearch",
					"AWSAccessKeyId" => "AKIAIPBO32C5TQNJLD7Q",
					"AssociateTag" => "spoturlook-21",
					"SearchIndex" => "Shoes",
				//	"SearchIndex" => "Apparel",
					"ResponseGroup" => "BrowseNodes,Images,ItemAttributes,ItemIds,Offers,Variations",
				//	"BrowseNode" => $amzcatId,
				    "Keywords" =>"Dynaflyte",
					"ItemPage"=>$i,
					"Availability" => "Available",
					"Sort" => $sort
				//	"ItemPage"=>'30'
				);
               // 1953602031
			 //man  1968024031
				// Set current timestamp if not set
				if (!isset($params["Timestamp"])) {
					$params["Timestamp"] = gmdate('Y-m-d\TH:i:s\Z');
				}

				// Sort the parameters by key
				ksort($params);

				$pairs = array();

				foreach ($params as $key => $value) {
					array_push($pairs, rawurlencode($key)."=".rawurlencode($value));
				}

				// Generate the canonical query
				$canonical_query_string = join("&", $pairs);

				// Generate the string to be signed
				$string_to_sign = "GET\n".$endpoint."\n".$uri."\n".$canonical_query_string;

				// Generate the signature required by the Product Advertising API
				$signature = base64_encode(hash_hmac("sha256", $string_to_sign, $aws_secret_key, true));

				// Generate the signed URL
				 $request_url = 'http://'.$endpoint.$uri.'?'.$canonical_query_string.'&Signature='.rawurlencode($signature);
				 $result = @simplexml_load_file($request_url);
				// echo '<pre>' ; print_r($result); echo '<pre>' ; 
				 $Title = array();
				 if(!empty($result->Items->Item)){
					 $categorylabel = '' ;
					  foreach($result->Items->Item as $product){
						  
						     $Department  = @$product->ItemAttributes->Department ;
							 $productname = @$product->ItemAttributes->Title;
							 $type = $categorylabel = '' ;
						     if(($Department=='mens') ||($Department=='Men') ||($Department=='men') ){
								  $type = 1 ;
							  } else if(($Department=='Women') || ($Department=='womens') || ($Department=='girls') || ($Department=='Girls')){
								  $type = 2 ;
							  }  else if( (strpos( $productname, "Women" ) !== false)||(strpos( $productname, "women" ) !== false)||(strpos( $productname, "Girl" ) !== false) ) {
								  $type=2;
							  } else if( (strpos( $productname, "men" ) !== false)||(strpos( $productname, "Men" ) !== false) || (strpos( $productname, "Man" ) !== false)||($categorylabel=="MEN")||($categorylabel=="Boys")) {
								  $type = 1;
							  } else {
								  $type = 3 ;
							  }
							 if(!empty(@$product->BrowseNodes->BrowseNode[0])){
								
								 $categorylabel = @$product->BrowseNodes->BrowseNode[0]->Name ; 
							 } else {
								 $categorylabel = @$product->BrowseNodes->BrowseNode->Name ; 
							 }
							 if(($categorylabel=="Women")||($categorylabel=="Women's")||($categorylabel=="Men")||($categorylabel=="Men's")||($categorylabel=="MEN'S")){
								  
								    $categorylabel = @$product->BrowseNodes->BrowseNode->Ancestors->BrowseNode->Name ;
								   if($categorylabel=="Categories"){
									  
									    $categorylabel =@$product->BrowseNodes->BrowseNode->Ancestors->BrowseNode->Ancestors->BrowseNode->Name ;
								   }
							 } else if(($categorylabel=='Formals')||($categorylabel=='Casuals')||($categorylabel=='Categories')||($categorylabel=='Clothing & Accessories')){
								
								 $categorylabel = @$product->BrowseNodes->BrowseNode->Ancestors->BrowseNode->Name ;
							 }
							 
							$productUrl = $product->DetailPageURL ;
							$categoryId = @$this->getcategoryid($categorylabel,$type);
						    $variations = @$product->Variations ;
							$productimageUrl = @$product->ImageSets->ImageSet->LargeImage->URL ;
							if(empty($categoryId)){
								// $categoryId = $categorylabel ;
							}
							$imageUrl = $sizeVariants = '' ;
							
							
							if(!empty($variations)){
								 //  
								   $totalVariations = $variations->TotalVariations ;
								   
								   for($k=0;$k<$totalVariations;$k++){
									    $sizeVariants .=@$variations->Item->$k->ASIN.',' ;
								   }
								    $sizeVariants = rtrim($sizeVariants,",");
								   for($j=0;$j<$totalVariations;$j++){
									        $subproducts =$variations->Item->$j ;
										 if(!empty($subproducts)){
										    $imageUrl = @$subproducts->ImageSets->ImageSet->$j->LargeImage->URL ;
										 if(empty($imageUrl)){
											 $imageUrl = @$subproducts->LargeImage->URL ;
										 }
										  if(empty($imageUrl)){
											 $imageUrl = $productimageUrl ;
										  }
										    $this->saveproduct($productname,$subproducts,$categoryId,$type,$productUrl,$amzcatId,$sizeVariants,$imageUrl);
										 }
										 $imageUrl = $subproducts = null ;
								   }
								   
								
							} else {
								          $imageUrl = @$product->LargeImage->URL ;
                                        
										 if(empty($imageUrl)){
											  $imageUrl = @$product->ImageSets->ImageSet->LargeImage->URL ;
										 }
								   $this->saveproduct($productname,$product,$categoryId,$type,$productUrl,$amzcatId,$sizeVariants,$imageUrl);
								
							} 
							$sizeVariants = $productimageUrl = $productUrl = $categoryId = null ;
						  
					  }
					  
				 }
				 $result = null ;
					  
				 //  return $result ;
		}
		
		    function saveproduct($productname=null,$product=null,$categoryId=null,$type=null,$productUrl=null,$amzcatId=null,$sizeVariants=null,$imageUrl=null){
				
				             $price = '' ;
							 $offerprice = '' ;
							 
							// $categoryId = $categoryData['catId'];
						//	 $categorylabel = @$product->BrowseNodes->BrowseNode->Ancestors->BrowseNode->Ancestors->BrowseNode->Name ;
						//	 $ca = @$product->BrowseNodes->BrowseNode->Ancestors->BrowseNode->Name ;
						  if(!empty($categoryId)) {
							     $offerprice =  $price = '' ;
								if(!empty(@$product->ItemAttributes->ListPrice->FormattedPrice)){
									 $price = str_replace('INR ','',@$product->ItemAttributes->ListPrice->FormattedPrice);
								 } else if(!empty(@$product->Offers->Offer->OfferListing->Price->FormattedPrice)) {
									 $price = str_replace('INR ','',@$product->Offers->Offer->OfferListing->Price->FormattedPrice);
								 } else if(!empty(@$product->VariationSummary->LowestPrice->FormattedPrice)) {
									 $price = str_replace('INR ','',@$product->VariationSummary->LowestPrice->FormattedPrice);
								 } else if(!empty(@$product->OfferSummary->LowestNewPrice->FormattedPrice)) {
									 $price = str_replace('INR ','',@$product->OfferSummary->LowestNewPrice->FormattedPrice);
								 }
								$offerprice = str_replace('INR ','',@$product->Offers->Offer->OfferListing->SalePrice->FormattedPrice);
								
								if(empty($offerprice)){
									$offerprice =  $price ;
								}
								
								$size = '' ;
								if(!empty(@$product->ItemAttributes->Size)){
									$size =@$product->ItemAttributes->Size;
								} else if(!empty(@$product->ItemAttributes->ClothingSize)){
									$size =@$product->ItemAttributes->ClothingSize;
								}
								$description = '';
								if(!empty(@$product->ItemAttributes->Feature)){
									$description = implode(' . ',(array)$product->ItemAttributes->Feature);
								} else {
									$description = @$product->EditorialReviews->EditorialReview->Content;
								}
								$productdata['category_id'] 	= @$categoryId ;
								$productdata['affiliate_type']  = 'Amazon';
								$productdata['type']  			= $type;
								$productdata['brands'] 			= @$product->ItemAttributes->Brand;
								$productdata['size']  			= $size;
								$productdata['color']  			= @$product->ItemAttributes->Color;
								$productdata['producturl']  	= @$productUrl;
								$productdata['name']   			= $productname;
								$productdata['price']  			= $price;
								$productdata['offer_price']  	= $offerprice;
								$productdata['availability'] 	= 1;
								$productdata['description'] 	= $description ;
								$productdata['productid'] 		= @$product->ASIN ;
						//		$productdata['test'] 			= @$amzcatId ;
								$productdata['size_variants'] 	= $sizeVariants ;
								$productdata['image'] 			= $imageUrl;
								
								$checkproduct = $this->checkproduct(@$product->ASIN,'Amazon');
							//    $categorylabel;
							//  echo '<pre>';  print_r($productdata); echo '</pre>';  exit;
								if(!empty($checkproduct)){
									$productarray = $checkproduct ;
								} else {
									 $productarray = $this->Product->newEntity() ;
									 $this->savebrand($productdata['brands'],$type);
								} 
								 $productpatch = $this->Product->patchEntity($productarray,$productdata);
								 if(!empty($price)){ 
									  $this->Product->save($productpatch) ;
								 }
								$productdata = $checkproduct = $productpatch = null ;
								 
								// $Title[] = array('amzcatId'=>$amzcatId,'Department'=>$Department,'categorylabel'=>$categorylabel);
							  // $Title[]['categorylabel'] =$categorylabel;
					 	  }
				
				
				
			}
		
		
		function getcategoryid($categorylabel,$type){
			    $catId ='' ;
				$this->autoRender =false ;
				
				
				
				 if($type=='3'){
					      if($categorylabel=='Fan Shop'){
							 $catId = '49';
						  } 
					  /*
					   if($categorylabel=='Gym Bags'){
						 $catId = '85';
					  } else if(($categorylabel=='Laptop Backpacks')||($categorylabel=='Laptop Bags')||($categorylabel=='Laptop Briefcases')||($categorylabel=='Laptop Messenger & Shoulder Bags')){
						 $catId = '87';
					  } else if($categorylabel=='Luggage'){
						 $catId = '100';
					  } else if($categorylabel=='Messenger Bags'){
						 $catId = '90';
					  } else if($categorylabel=='Travel Duffles'){
						 $catId = '89';
					  } else if($categorylabel=='Casual Backpacks'){
						 $catId = '84';
					  } else if($categorylabel=='Clutches'){
						 $catId = '95';
					  } else if($categorylabel=='Handbags'){
						 $catId = '94';
					  } else if($categorylabel=='Totes'){
						 $catId = '97';
					  } 
					  if(($categorylabel=='Sunglasses')||($categorylabel=='Spectacle Frames')||($categorylabel=='Eye Care')){
							  $catId ='69';
					   } else if($categorylabel=='Gloves'){
							 $catId ='76';
					   }  else if($categorylabel=='Fashion Slippers'){
							 $catId ='13';
					   } else if($categorylabel=='Flip-Flops & House Slippers'){
							 $catId ='43';
					   } else if($categorylabel=='Loafers & Moccasins'){
							 $catId ='12';
					   } else if($categorylabel=='Backpack Handbags'){
							 $catId ='84';
					   } else if($categorylabel=='Sneakers'){
							 $catId ='45';
					   } else if($categorylabel=='Thong Sandals'){
							 $catId ='44';
					   } else if($categorylabel=='Watches'){
							 $catId ='37';
					    } else if($categorylabel=='Snow & Rainwear'){
							 $catId ='21';
					    } else if($categorylabel=='Safety Shoes'){
							 $catId ='41';
					    } else if($categorylabel=='Sandals & Floaters'){
							 $catId ='44';
					    }  else if(($categorylabel=='Laptop Messenger & Shoulder Bags')||($categorylabel=='Laptop Backpacks')){
							 $catId ='87';
					    } else if(($categorylabel=='Pendants')||($categorylabel=='Earrings')){
							 $catId ='103';
						 } 
					*/	 
					 
				} else if($type=='2'){ 
				         if($categorylabel=='Running Shoes'){
							  $catId = '9';
						  }
				       /*
				         if($categorylabel=='Sports Clothing'){
							 // $catId = '95';
						  } 
					       if($categorylabel=='Clutches'){
							$catId = '95';
						  } else if($categorylabel=='Handbags'){
							 $catId = '94';
						  } else if($categorylabel=='Totes'){
							 $catId = '97';
						  } else if($categorylabel=='Belts'){
							 $catId = '104';
						  } else if($categorylabel=='Backpack Handbags'){
							 $catId = '98';
						  } else  if($categorylabel=='Messenger Bags'){
								$catId = '96';
						  } 
						  if(($categorylabel=='Bras')||($categorylabel=='Everyday Bras')||($categorylabel=='Sports Bras')){
							$catId = '91';
						  } else if($categorylabel=='Panties'){
							 $catId ='92';
						  } */
					   /*  if($categorylabel=='Sarees'){
							 $catId ='30';
						  } else if($categorylabel=='Dress Material'){
							 $catId ='118';
						  }  else if($categorylabel=='Kurtas'){
							 $catId ='135';
						  }  else if($categorylabel=='Lehenga Cholis'){
							 $catId ='119';
						  }  else if(($categorylabel=='Nightdresses & Nightshirts')||($categorylabel=='Sleep & Lounge Wear')||($categorylabel=='Tankinis')){
							 $catId ='93';
						  }  else if($categorylabel=='Salwar Suit Sets'){
							 $catId ='117';
						  }  else if($categorylabel=='Leggings'){
							 $catId ='129';
						  } else if($categorylabel=='Everyday Bras'){
							 $catId ='91';
						  } else if(($categorylabel=='Panties')||($categorylabel=='Briefs')){
							 $catId ='92';
						  } else if($categorylabel=='Scarves & Wraps'){
							 $catId ='107';
						  }  else if($categorylabel=='Pants & Capris'){
							 $catId ='130';
						  }  else if(($categorylabel=='Boy Shorts')||($categorylabel=='Shorts')){
							 $catId ='136';
						  }  else if($categorylabel=='Jeans'){
							 $catId ='133';
						  }  else if($categorylabel=='Shrugs'){
							 $catId ='138';
						  }  else if($categorylabel=='Jackets'){
							 $catId ='146';
						  }  else if($categorylabel=='Cardigans'){
							 $catId ='149';
						  }  else if($categorylabel=='Sweatshirts & Hoodies'){
							 $catId ='145';
						  }  else if(($categorylabel=='Fashion Sandals') || ($categorylabel=="Pumps") || ($categorylabel=="Ballerinas") || ($categorylabel=="Ethnic Footwear")|| ($categorylabel=="Thong Sandals") || ($categorylabel=="Sandals & Floaters")){
							 $catId ='14';
						  }  else if(($categorylabel=='Fashion Slippers') || ($categorylabel=="Flip-Flops & House Slippers")){
							 $catId ='13';
						  } else if(($categorylabel=='Boots') || ($categorylabel=="Boat Shoes")){
							 $catId ='16';
						  } else if($categorylabel=='Casual Shoes'){
							 $catId ='11';
						  } else if($categorylabel=='Sneakers'){
							 $catId ='15';
						  } else if($categorylabel=='Loafers & Moccasins'){
							 $catId ='12';
						  } else if(($categorylabel=='Running Shoes') || ($categorylabel=="Dance Shoes") || ($categorylabel=="Walking Shoes") || ($categorylabel=="Sport Shoes") || ($categorylabel=="Outdoor Multisport Training Shoes") || ($categorylabel=="Trail Running Shoes") || ($categorylabel=="Shoes & Handbags") || ($categorylabel=="Tennis Shoes") || ($categorylabel=="Cycling Shoes")){
							 $catId ='9';
						  } else if($categorylabel=='Formal Shoes'){
							 $catId ='10';
						  } else if(($categorylabel=='Dressing Gowns & Kimonos') || ($categorylabel=="Cover-Ups & Sarongs") || ($categorylabel=="Gilets")|| ($categorylabel=="Bikinis")|| ($categorylabel=="Dresses")|| ($categorylabel=="Western Wear")||($categorylabel=="Ponchos & Capes")||($categorylabel=="Knitted Tank Tops")){
							 $catId ='127';
						  } else if(($categorylabel=='Socks & Hosiery') || ($categorylabel=="Liners & Ankle Socks")){
							 $catId ='108';
						  } else if(($categorylabel=='Overalls & Bodies')|| ($categorylabel=="Shapewear")|| ($categorylabel=="Ballet Flats")){
							//  $catId ='104';
						  } else if(($categorylabel=='Pendants')|| ($categorylabel=="Necklaces")|| ($categorylabel=="Earrings") || ($categorylabel=="Nose Rings & Pins") || ($categorylabel=="Rings")|| ($categorylabel=="Bangles & Bracelets")|| ($categorylabel=="Hair Jewellery")){
							 $catId ='103';
						  } else if($categorylabel=='Precious Coins'){
							 $catId ='101';
						  } else if(($categorylabel=='Backpack Handbags')|| ($categorylabel=="Bags, Wallets and Luggage")|| ($categorylabel=="Handbags")){
							 $catId ='94';
						  } else if($categorylabel=='Bottoms'){
							 $catId ='129';
						  } else if(($categorylabel=='Coats')|| ($categorylabel=="Coats & Jackets")){
							 $catId ='149';
						  } else if(($categorylabel=='One Pieces')|| ($categorylabel=="Sports Bras")|| ($categorylabel=="Sportswear")|| ($categorylabel=="Tank Tops")|| ($categorylabel=="Swim")){
							 $catId ='27';
						   } else if($categorylabel=='Pullovers'){
							 $catId ='144';
						   } else if(($categorylabel=='Shirts')|| ($categorylabel=="Shirts, Tops & Tees")|| ($categorylabel=="Tops")|| ($categorylabel=="Tops & Tees")){
							 $catId ='128';
						   } else if(($categorylabel=='Smart Watches & Accessories') || ($categorylabel=="Watches")){
							 $catId ='29';
						   }  if(($categorylabel=='Sunglasses')||($categorylabel=='Spectacle Frames')){
							  $catId ='69';
					       } else if($categorylabel=='T-Shirts'){
							 $catId ='137';
						   } else if($categorylabel=='Trousers'){
							 $catId ='141';
						   } else if(($categorylabel=='Clutches') ||($categorylabel=='Wallets')){
							 $catId ='95';
						   }  else if($categorylabel=='Gloves'){
							  $catId ='76';
						   } else if($categorylabel=='Snow & Rainwear'){
							 $catId ='21';
					        } else if($categorylabel=='Blazers'){
							 $catId ='131';
					        } else if($categorylabel=='Stoles & Dupattas'){
							 $catId ='140';
					        } else if($categorylabel=='Saree Blouses'){
							  $catId ='121';
					        } else if(($categorylabel=='Totes') || ($categorylabel=='Sling & Cross-Body Bags') || ($categorylabel=='Hobos & Shoulder Bags')){
							  $catId ='97';
					        } else if($categorylabel=='Shawls'){
							  $catId ='148';
					        } else if($categorylabel=='Skirts'){
							  $catId ='132';
					        }
						*/
				} else if($type=='1'){
					
					     if($categorylabel=='Running Shoes'){
							  $catId = '39';
						  }
					     /*  if(($categorylabel=='Clothing')||($categorylabel=="Sports T-Shirts")||($categorylabel=="T-Shirts")){
							   $catId = '49';
						   }
						   if(($categorylabel=='Athletic Socks')||($categorylabel=='Sportswear')||($categorylabel=='Sports T-Shirts')||($categorylabel=='Basketball')||($categorylabel=='Track Pants') ){
							 $catId = '53';
						  } 
						 if(($categorylabel=='T-Shirts') || ($categorylabel=='Polos') || ($categorylabel=='T-Shirts & Polos')){
							 $catId = '49';
						  } else if($categorylabel=='Belts & Suspenders'){
							 $catId = '68';
						  } else if($categorylabel=='Hats & Caps'){
							 $catId = '74';
						  } else if($categorylabel=='Belts & Suspenders'){
							 $catId = '68';
						  } else if($categorylabel=='Briefs'){
							 $catId = '79';
						  } else if(($categorylabel=='Vests') || ($categorylabel=='Packs')){
							 $catId = '78';
						  } else if(($categorylabel=='Casual Shirts')||($categorylabel=='Sleeveless T-Shirts')){
							 $catId ='59';
						  } else if($categorylabel=='Boxers'){
							 $catId ='81';
						  } else if($categorylabel=='Jeans'){
							 $catId ='51';
						  } else if(($categorylabel=='Shorts')||($categorylabel=='Bathrobes')){
							 $catId ='57';
						  } else if($categorylabel=='Sportswear'){
							 $catId ='53';
						  } else if(($categorylabel=='Track Pants & Trousers')||($categorylabel=='Trousers')){
							 $catId ='52';
						  } else if($categorylabel=='Sweatshirts & Hoodies'){
							 $catId ='112';
						  } else if($categorylabel=='Thermal'){
							 $catId ='115';
						  } else if($categorylabel=='Trunks'){
							 $catId ='83';
						  } else if($categorylabel=='Snow & Rainwear'){
							 $catId ='114';
						  } else if(($categorylabel=='Socks & Hosiery')||($categorylabel=='Liners & Ankle Socks')){
							 $catId ='72';
						  } else if($categorylabel=='Sherwanis'){
							 $catId ='64';
						  } else if($categorylabel=='Dhotis, Mundus & Lungis'){
							 $catId ='109';
						  } else if(($categorylabel=='Kurta Pyjamas')||($categorylabel=='Kurtas')){
							 $catId ='63';
						  } else if(($categorylabel=='Ethnic Jackets') || ($categorylabel=='Jackets') || ($categorylabel=='Jackets & Gilets')|| ($categorylabel=='Gilets')){
							 $catId ='67';
						  } else if($categorylabel=='Waistcoats'){
							 $catId ='61';
						  } else if(($categorylabel=='Blazers')||($categorylabel=='Suits & Blazers')){
							 $catId ='60';
						  } else if($categorylabel=='Pullovers'){
							 $catId ='114';
						  } else if(($categorylabel=='Bow Ties')||($categorylabel=='Neckties')||($categorylabel=='Ties')){
							 $catId ='71';
						  } else if(($categorylabel=='Athletic Socks')||($categorylabel=='Liners & Ankle Socks')||($categorylabel=='Socks')||($categorylabel=='Calf Socks')){
							 $catId ='72';
					      } else if($categorylabel=='Gloves'){
							 $catId ='76';
					      } else if(($categorylabel=='Watches')||($categorylabel=='Watchbands')){
							 $catId ='37';
					      } else if($categorylabel=='Cardigans'){
							 $catId ='115';
					      } else if($categorylabel=='Formal Shirts'){
							 $catId ='58';
					      } if(($categorylabel=='Sunglasses')||($categorylabel=='Spectacle Frames')||($categorylabel=='Sunglasses & Spectacle Frames')){
							  $catId ='69';
					      } else if($categorylabel=='Suits'){
							 $catId ='62';
					      } else if($categorylabel=='Sweaters'){
							 $catId ='113';
					      } else if(($categorylabel=='Boat Shoes')||($categorylabel=='Safety Shoes')||($categorylabel=='Casual Shoes')){
							 $catId ='41';
					      } else if($categorylabel=='Boots'){
							 $catId ='46';
					      } else if(($categorylabel=='Flip-Flops & House Slippers')||($categorylabel=='Fashion Slippers')){
							 $catId ='47';
					      } else if(($categorylabel=='Football Shoes')||($categorylabel=='Running Shoes')||($categorylabel=='Trail Running Shoes')||($categorylabel=='Tennis Shoes')||($categorylabel=='Sport Shoes')||($categorylabel=='Outdoor Multisport Training Shoes')||($categorylabel=='Cricket Shoes')||($categorylabel=='Trekking & Hiking Footwear')||($categorylabel=='Basketball Shoes')){
							 $catId ='39';
					      } else if($categorylabel=='Formal Shoes'){
							 $catId ='40';
					      } else if($categorylabel=='Loafers & Moccasins'){
							 $catId ='42';
					      } else if(($categorylabel=='Pyjama & Lounge Bottoms')||($categorylabel=='Pyjama Sets')){
							 $catId ='80';
					      } else if(($categorylabel=='Sandals & Floaters')||($categorylabel=='Thong Sandals')||($categorylabel=='Fashion Sandals')){
							 $catId ='44';
					      } else if($categorylabel=='Sneakers'){
							 $catId ='45';
					      } else if(($categorylabel=='Scarves')||($categorylabel=='Scarves')){
							 $catId ='75';
					      } else if($categorylabel=='Laptop Messenger & Shoulder Bags'){
							 $catId ='87';
					      } else if($categorylabel=='Precious Coins'){
							 $catId ='70';
					      }
					     */
				} 
				
				return $catId ;
		
		}
		
		function checkproduct($productid=null,$affiliateType=null){
			// print_r($name); exit;
			$this->autoRender = false ;
			$product = $this->Product->find()->where(['productid' =>$productid])->first();
			return $product ;	 
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
			
		 }	
}
?>