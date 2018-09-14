<?php
namespace App\Shell;
use Cake\Console\Shell;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;
use App\Utility\Flipkart;
class FlipkartShell extends Shell
{  
     public function initialize()
    {
        parent::initialize();
         $this->Category 	= 	TableRegistry::get('Categories');
		 $this->Product 	= 	TableRegistry::get('Amazon');
		 $this->Brand 		= 	TableRegistry::get('Brands');
    }
    public function main()
    {
        $this->out('Hello world.');
    }

    public function upload(){
				   $this->autoRender =false ;
				   $flipkart = new Flipkart("<affiliate-id>", "<access-token>", "json");
				   $home = $flipkart->api_home();
				  
					//Make sure there is a response.
					if($home==false){
						echo 'Error: Could not retrieve API homepage';
						exit();
					}
					
					$home = json_decode($home, TRUE);
					$list = $home['apiGroups']['affiliate']['apiListings'];
			    //	$spoturlookcategory = array('womens_footwear','womens_clothing','mens_footwear','mens_clothing','jewellery','bags_wallets_belts');
				//   $spoturlookcategory = array('womens_footwear','womens_clothing');
				    $spoturlookcategory = array('mens_footwear');
					// $url ="https://affiliate-api.flipkart.net/affiliate/feeds/dkashyap8/category/osp-iko.json?expiresAt=1487705617095&sig=c70d404ef9407d751f35db3bbee93b61";
					 // $arr = explode('sig=', $dd);
                     foreach ($list as $key => $data) {
						 //  $name = $key ;
						   if(in_array($key,$spoturlookcategory)){
							     $url = $data['availableVariants']['v0.1.0']['get'] ;
								 $this->getnextpagerecord($url);
								 $url =null ;
						   }
						   $key = $url = $data =null ;
						  unset($key);
						  unset($url);
						  unset($data);
						 
					}
			}	
			
        function getnextpagerecord($url) {
		      $this->autoRender =false ;
			  $flipkart = new Flipkart("<affiliate-id>", "<access-token>", "json");
			  $details = $flipkart->call_url($url);
			  $details = json_decode($details, TRUE);
			  $products = $details['productInfoList'];
			  $nextUrl = $details['nextUrl'];
			//  echo  count($products).'___' ;
			  $k=1;
			  if(!empty($products)){
				   
					foreach ($products as $product) {
						$productdata = array();
						$productname =@$product['productBaseInfo']['productAttributes']['title'] ;
						$categorylabel =  $product['productBaseInfo']['productIdentifier']['categoryPaths']['categoryPath'][0][0]['title'];
						$inStock = $product['productBaseInfo']['productAttributes']['inStock'];
						if(!$inStock)
						continue;
					    
						$categoryData = $this->getcategoryid($categorylabel,$productname);
						$categoryId = $categoryData['catId'];
						$type = $categoryData['type'];
					
						if(!empty($categoryId)) {
							
						   // echo '<pre>' ; print_r($categoryId); echo '</pre>' ;exit;
							// echo '<pre>' ; print_r($product); echo '</pre>' ;exit;
							$productdata['category_id'] 	= @$categoryId ;
							$productdata['affiliate_type']  = 'Flipkart';
							$productdata['type']  			= $type;
							$productdata['brands'] 			= @$product['productBaseInfo']['productAttributes']['productBrand'] ;
							$productdata['size']  			= @$product['productBaseInfo']['productAttributes']['size'] ;
							$productdata['color']  			= @$product['productBaseInfo']['productAttributes']['color'] ;
							$productdata['producturl']  	= @$product['productBaseInfo']['productAttributes']['productUrl'] ;
							$productdata['name']   			= @$product['productBaseInfo']['productAttributes']['title'] ;
							$productdata['price']  			= @$product['productBaseInfo']['productAttributes']['maximumRetailPrice']['amount'] ;
							$productdata['offer_price']  	= @$product['productBaseInfo']['productAttributes']['sellingPrice']['amount'] ;
							$productdata['availability'] 	= @$product['productBaseInfo']['productAttributes']['inStock'] ;
							$productdata['description'] 	= @$product['productBaseInfo']['productAttributes']['productDescription'] ;
							$productdata['productid'] 		= @$product['productBaseInfo']['productIdentifier']['productId'] ;
							$productdata['size_variants'] 	= @$product['productBaseInfo']['productAttributes']['sizeVariants'] ;
							$productdata['image'] 			= array_key_exists('800x800', $product['productBaseInfo']['productAttributes']['imageUrls'])?$product['productBaseInfo']['productAttributes']['imageUrls']['800x800']:'';

						   $checkproduct = $this->checkproduct($productdata['productid'],'Flipkart');
						  
							if(!empty($checkproduct)){
								$productarray = $checkproduct ;
							}
							else {
								 $productarray = $this->Product->newEntity() ;
								  $this->savebrand($productdata['brands'],$type);
							} 
							 $productpatch = $this->Product->patchEntity($productarray,$productdata);
							 
							 
							 $this->Product->save($productpatch) ;
								
						}
						$categoryId =$productarray =$productpatch =$productdata = $categoryData =$product = null ;
						 unset($categoryId);
						 unset($productarray);
						 unset($productpatch);
						 unset($checkproduct);
						 unset($productdata);
						 unset($categoryData);
						 unset($product);
						
					 }  
			  } 
		//	echo  memory_get_peak_usage(true).'__';
		 //    echo memory_get_usage().'__';
			  if(!empty($details)){
					 $this->getnextpagerecord($nextUrl);
			  
			  }
			   $details = null ;
			   unset($details);
			
		}		
		
		function getcategoryid($categorylabel=null,$productname=null){
			    $catId ='' ;
				$this->autoRender =false ;
				$categoryarray = explode('>',$categorylabel);
				$cat1 = @$categoryarray[0];
				$cat2 = @$categoryarray[1];
				$cat3 = @$categoryarray[2];
				$cat4 = @$categoryarray[3];
				$cat5 = @$categoryarray[4];
				$cat6 = @$categoryarray[5];
			    $type ='';
				/* 
				 if($cat2=='Women'){
					$type ='2';
					if($cat1=='Footwear'){
						if($cat3=='Slippers & Flip Flops'){
							$catId ='13';
						} else if($cat3=='Ethnic Shoes'){
							$catId ='22';
						} else if($cat3=='Formal Shoes'){
							$catId ='10';
						} else if($cat3=='Bellies'){
							$catId ='20';
						} else if($cat3=='Sports Shoes'){
							$catId ='9';
						} else if($cat4=='Boots'){
							$catId ='16';
						} else if($cat4=='Loafers'){
							$catId ='12';
						} else if($cat4=='Sneakers'){
							$catId ='15';
						} else if($cat3=='Casual Shoes'){
							$catId ='11';
						}
						
					} else if($cat3=='Lingerie & Sleepwear'){
						if($cat4=='Shapewears'){
							// $catId ='Shapewears';
						} else if($cat4=='Lingerie Sets'){
							// $catId ='Lingerie Sets';
						}
					}  else if($cat3=='Winter & Seasonal Wear'){
						if($cat5=='Shawls'){
							$catId ='148';
						}
					} else if($cat3=='Ethnic Wear'){
						if($cat4=='Ethnic Bottoms'){
							// $catId ='Ethnic Bottoms';
						} else if($cat4=='Kurtas & Kurtis'){
							$catId ='116';
						} else if($cat4=='Blouses'){
							$catId ='121';
						} else if($cat4=='Saris'){
							$catId ='30';
						}
					} else if($cat3=='Western Wear'){
						if($cat5=='Skirts'){
							$catId ='132';
						} else if($cat4=='Shrugs & Jackets'){
							$catId ='138';
						} else if($cat4=='Leggings & Jeggings'){
							$catId ='129';
						} else if($cat5=='Dresses'){
							$catId ='127';
						} else if($cat4=='Trousers & Capris'){
							$catId ='130';
						}  else if($cat4=='Shirts, Tops & Tunics'){
							$catId ='128';
						}
						
					} else if($cat3=='Accessories'){
						if($cat5=='Socks'){
							$catId ='108';
						} else  if($cat4=='Scarves & Stoles'){
							$catId ='107';
						 }
					} else if($cat3=='Formal Wear'){
						if($cat4=='Suits'){
							$catId ='142';
						} else if($cat4=='Trousers'){
							$catId ='141';
						}
					} else if($cat3=='Fusion Wear'){
						if($cat4=='Kurtas & Kurtis'){
							$catId ='135';
						} else if($cat5=='Leggings & Churidars'){
							// $catId ='Leggings & Churidars';
						} 
					}
						
				} else */
					if($cat2=='Men'){
					 $type ='1';
					 
					 if($cat1=='Footwear'){
						  if($cat3=='Sports Shoes'){
							 $catId ='39';
						  } 
					 }
				} 
					 
					/* if($cat1=='Footwear'){
						  if($cat3=='Slippers & Flip Flops'){
							  $catId ='47';
						  } else if($cat3=='Formal Shoes'){
							 $catId ='40';
						  } else if($cat3=='Casual Shoes'){
							 $catId ='41';
						  } else if($cat3=='Sports Shoes'){
							 $catId ='39';
						  } else if($cat3=='Ethnic Shoes'){
							 $catId ='48';
						  } else if($cat3=='Boots'){
							 $catId ='46';
						  }
					 } else if($cat1=='Apparels'){
						  if($cat3=='Innerwear & Sleepwear'){
							   if($cat4=='Trunks'){
									$catId ='83';
							   } else if($cat4=='Vests'){
									$catId ='78';
							   }
						  } else if($cat3=='Winterwear & Seasonalwear'){
							   if($cat4=='Socks'){
									$catId ='72';
							   } else  if($cat4=='Jackets'){
									$catId ='67';
							   }
						  } else if($cat3=='Suits and Blazers'){
							   if($cat4=='Suits'){
									$catId ='62';
							   }
						  } else if($cat3=='Ethnicwear'){
							   if($cat4=='Kurtas'){
									$catId ='63';
							   }
						  } else if($cat3=='Trousers'){
								$catId ='52';
						  } else if($cat3=='Accessories'){
							   if($cat4=='Scarfs'){
									$catId ='75';
							   }
						  } else if($cat3=='Polos & T-Shirts'){
								$catId ='49';
						  }  else if($cat4=='Cufflinks'){
								$catId ='73';
						  }
						 
					 }
					
				} else if(($cat2=='Makeup')||(($cat2=='Beauty Accessories')&&($cat3=='Makeup Accessories'))){
					
				      $catId ='152';
				
                }  else if($cat2=='Hair Care'){
					  $type ='2';
					  $catId ='154';
					  
				}  else if($cat2=='Fragrances'){
					  $type ='2';
				      $catId ='155';
				
				}  else if($cat2=='Deodorants'){
					  $type ='2';
				      $catId ='155';
					  
			    }  else if($cat2=='Body and Skin Care'){
					  $type ='2';
					  $catId ='153';
				
				} else if($cat2=='Jewellery'){
					    $type ='2';
					    if($cat3=='Precious Jewellery'){
							$catId ='101';
						} else if($cat3=='Artificial Jewellery'){
							$catId ='102';
						} 
				} else if($cat2=='Jewellery'){
					    $type ='2';
					    if($cat3=='Precious Jewellery'){
							$catId ='101';
						} else if($cat3=='Artificial Jewellery'){
							$catId ='102';
						} 
				} else if($cat1=='Lifestyle'){ 
					    if($cat4=='Suitcases'){
							$type ='3';
							$catId ='100';
						} else if($cat4=='Hand Bags'){
							$type ='2';
							$catId ='94';
						}  else if($cat4=='Totes'){
							$type ='2';
							$catId ='97';
						} else if($cat4=='Messenger Bags'){
							// $catId ='Messenger Bag';
						} else if($cat4=='Clutches'){
							$type ='2';
							$catId ='95';
						} else if($cat4=='Pouches and Potlis'){
							$type ='2';
							$catId ='99';
						} else if($cat4=='Sling Bags'){
							$type ='2';
							$catId ='96';
						} else if($cat4=='Duffel Bags'){
							$type ='1';
							$catId ='89';
						} 
				} else if($cat1=='Computers'){
					    if($cat2=='Laptop Accessories'){
								if($cat2=='Bags'){
									$type ='1';
									$catId ='87';
								}
						}
				 } 
				 
				 if($cat3=='Belts & Buckles'){
					 if((strpos( $productname, "Women" ) !== false)||(strpos( $productname, "Girl" ) !== false)){
						 $type ='2';
						 $catId ='104';
					 } else if((strpos( $productname, "Men" ) !== false)||(strpos( $productname, "Boy" ) !== false)){
						 $type ='1';
						 $catId ='68';
					 }
				 }  
				 if($cat4=='Dupattas'){
						 $type ='2';
						 $catId ='126';
					 
				 } else if($cat4=='Abayas & Burqas'){
						 $type ='2';
						 $catId ='124';
				 } else if($cat4=='Saree Falls'){
						 $type ='2';
						 $catId ='125';
				 } else if($cat4=='Petticoats'){
						 $type ='2';
						 $catId ='123';
				 } else if($cat4=='Dresses & Skirts'){
						 $type ='2';
						 $catId ='122';
				 } */
				 
				$data = array('catId'=>$catId,'type'=>$type);
				return $data ;
		
		}	

		function checkproduct($productid=null,$affiliateType=null){
			$this->autoRender = false ;
			$product = $this->Product->find()->where(['productid' =>$productid])->first();
			return $product ;	
            unset($product);			
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
}
?>