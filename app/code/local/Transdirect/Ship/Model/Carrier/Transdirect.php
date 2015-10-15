<?php

class Transdirect_Ship_Model_Carrier_Transdirect extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {
	
	protected $_code = 'transdirect';

	public function collectRates(Mage_Shipping_Model_Rate_Request $request) {

		//echo '<pre>';	 print_r($request); die('request');
		if (!Mage::getStoreConfig('carriers/'.$this->_code.'/active')) {
			return false;
		}

		$price = $this->getConfigData('price'); // set a default shipping price maybe 0
		$price = 0;

		/* API Request Start */
		$account_email 			 = Mage::getStoreConfig('transdirect_section/authentication/email');
		$account_password 		 = Mage::getStoreConfig('transdirect_section/authentication/password');
		$warehouse_suburb 		 = Mage::getStoreConfig('transdirect_section/warehouseaddress/suburb');
		$warehouse_postcode 	 = Mage::getStoreConfig('transdirect_section/warehouseaddress/postcode');
		$warehouse_address 		 = Mage::getStoreConfig('transdirect_section/warehouseaddress/address');
		$dimension_width 		 = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionswidth');
		$dimension_height 		 = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionsheight');
		$dimension_dim 			 = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionsdim');
		$dimension_weight 		 = Mage::getStoreConfig('transdirect_section/defaultitemsize/weight');
		$display_carriers 		 = Mage::getStoreConfig('transdirect_section/displayoptions/availablecoriers');
		$display_quote 			 = Mage::getStoreConfig('transdirect_section/displayoptions/quotedisplay');
		$display_fixedprice 	 = Mage::getStoreConfig('transdirect_section/displayoptions/fixedpriceonerror');
		$display_fixedprice1 	 = Mage::getStoreConfig('transdirect_section/displayoptions/fixedpriceonerror1');
		$display_showcouriername = Mage::getStoreConfig('transdirect_section/displayoptions/showcouriernames');
		$display_surcharge 		 = Mage::getStoreConfig('transdirect_section/displayoptions/handlingsurcharge');
		$display_surcharge1 	 = Mage::getStoreConfig('transdirect_section/displayoptions/handlingsurcharge1');
		$display_surcharge_unit	 = Mage::getStoreConfig('transdirect_section/displayoptions/handlingunit');
		$display_includesurchage = Mage::getStoreConfig('transdirect_section/displayoptions/includesurcharge');
		$order_box_enable 		 = Mage::getStoreConfig('transdirect_section/orderbox/enableorderbox');
		$order_box_size 		 = Mage::getStoreConfig('transdirect_section/orderbox/boxsize');
		$couriers_name		 	 = Mage::getStoreConfig('transdirect_section/displayoptions/couriersname');
		$redirect_url			 = Mage::getBaseUrl();

		// Cart Total Weight Code Start by Nayan 
		$quote = Mage::getSingleton('checkout/session')->getQuote();
        $cartItems = $quote->getAllVisibleItems();
		$total_qty = Mage::helper('checkout/cart')->getSummaryCount(); 

		$weight = 0;
		$height = 0;
		$width = 0;
		$length = 0;

		$cart_total_weight = 0;  
		$cart_total_height = 0;  
		$cart_total_width = 0;
		$cart_total_length = 0;

        foreach ($cartItems as $item) {
			//echo '<pre>'; print_r($item->getQty()); die('qty');
            $productId = $item->getProductId();
			$productQty = $item->getQty();
            $product = Mage::getModel('catalog/product')->load($productId);

            //echo $product->getItemWeight();
		 	// $weight += $product->getItemWeight() * $productQty;
			// $height += $product->getItemHeight() * $productQty;
			// $width += $product->getItemWidth() * $productQty;
			// $length += $product->getItemDim() * $productQty;
			$items_list  = array();
        	$box_items = array();

		 	$weight = $product->getItemWeight();
		 	$height = $product->getItemHeight();
		 	$width  = $product->getItemWidth() ;
		 	$length = $product->getItemDim();

			// Cart Total Weight Code End by Nayan
			if(!$weight) {
				$cart_total_weight 	= $dimension_weight;
			} else {
				$cart_total_weight = $weight;
			}
			if(!$height) {
				$cart_total_height  = $dimension_height;
			} else {
				$cart_total_height = $height;
			}
			if(!$width) {
				$cart_total_width 	= $dimension_width;
			} else {
				$cart_total_width  = $width;
			}
			if(!$length) {
				$cart_total_length 	= $dimension_dim;
			} else {
				$cart_total_length = $length;
			}

			if ($order_box_enable == 1) {
				$cubic_weight = ($cart_total_length * $cart_total_width * $cart_total_height) / 250;

				if($cart_total_weight > $cubic_weight) {
					$cubic_weight = $cart_total_weight;
				}



				for($x = 1; $x <= $productQty; $x++) {
                    if ($cubic_weight > $order_box_size) {

                        for($x = $order_box_size; $x <= $cubic_weight; $x *= 2) {
                           array_push($items_list, array(
                                // 'itemidx' => $i,
                                'cubic_weight' => $order_box_size
                            ));
                        }
                        $r = 0;
                        if (($r = $cubic_weight % $order_box_size)) {
                            array_push($items_list, array(
                                // 'itemidx' => $i,
                                'cubic_weight' => $r
                            ));
                        }
                    } else {
                        array_push($items_list, array(
                            // 'itemidx' => $i,
                            'cubic_weight' => $cubic_weight
                        ));
                    }
                }
			}

        }

        if ($order_box_enable == 1) {
	        foreach ($items_list as $item) {
	            $newBox = true;
	            foreach ($box_items as $box) {
	                if($item['cubic_weight'] <= $order_box_size - $box['weight']) {
	                    $box['weight'] += $item['cubic_weight'];
	                    $box['quantity']++;
	                    $newBox = false;
	                    break;
	                }
	            }
	            if ($newBox) {
	                $length = $width = $height = pow(250 * $item['cubic_weight'], 1/3);
	                array_push($box_items, array(
	                    'weight'        => $item['cubic_weight'],
	                    'height'        => $height,
	                    'width'         => $width,
	                    'length'        => $length,
	                    'quantity'      => 1,
	                    'description'=>'item description'
	                ));
	            }
	        }
    	}
		
		// $cart_total_weight = $weight;  
		// $cart_total_height = $height;  
		// $cart_total_width = $width;
		// $cart_total_length = $length; 

		

		// Cart Total Weight Code End by Nayan 
		// if(!$cart_total_weight){ $cart_total_weight = $dimension_weight; }
		// if(!$cart_total_height){ $cart_total_height = $dimension_height; }
		// if(!$cart_total_width){ $cart_total_width = $dimension_width; }
		// if(!$cart_total_length){ $cart_total_length = $dimension_dim; }

		// echo 'width--'.$cart_total_weight; 
		// echo 'height---'.$cart_total_height; 
		// echo 'width---'.$cart_total_width; 
		// echo 'length---'.$cart_total_length; 
		// die('here');

		// Getting Cart page Quote Address Details Code Start by Nayan
		$receiver_country    = (string) Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCountryId();
		$receiver_postcode   = (string) Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode();
		$receiver_regionId   = (string) Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getRegionId();
		$receiver_region     = (string) Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getRegion();
		$region = Mage::getModel('directory/region')->load($receiver_regionId);
		$receiver_region_tmp = $region->getName();

		
		// if($receiver_region == '') {
	        // Mage::getSingleton('checkout/session')->addError("Please enter state/province.");
			// session_write_close(); 
			// //$this->_redirect('checkout/cart');	
			// return false;
		// }

		// if(!is_numeric($receiver_postcode) || $receiver_postcode == "0" || $receiver_postcode == "00" || $receiver_postcode == "000" || $receiver_postcode == "0000" || $receiver_postcode == "00000" || $receiver_postcode == "000000"  || $receiver_postcode == "0000000") {
	        // Mage::getSingleton('checkout/session')->addError("Please enter valid and correct postcode.");
			// session_write_close(); 
			// return false;
			// // $this->_redirect('checkout/cart');	
		//}

		if(!$receiver_regionId)	{ 
		  $receiver_suburb = $receiver_region; 
		} else { 
		  $receiver_suburb = $receiver_region_tmp; 
		}

		// echo $tmp_cart_postcode_val = $_COOKIE['cart_postocde']; 
  		// echo $tmp_cart_locality_val = $_COOKIE['cart_locality'];
   		//die('val');

		$receiver_postcode = $_COOKIE['cart_postocde'];
		$receiver_suburb = $_COOKIE['cart_locality']; 

		// echo Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getPostcode(); 
		// echo Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getCountryId();
		// echo Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getRegion();
		// echo Mage::getSingleton('checkout/session')->getQuote()->getShippingAddress()->getRegionId();
		//echo $receiver_suburb; die('suburb');


		// Getting Cart page Quote Address Details Code End by Nayan


        if ($order_box_enable == 1) {
			$quoteDetails = array(
				'declared_value'=>10000,
				'items' => $box_items,
				'requesting_site' => $requesting_site,
				'sender' => array(
				 	'country' => 'AU', 
				 	'suburb'=> $warehouse_suburb, 
				 	'postcode' => $warehouse_postcode, 
				 	'type'=> $warehouse_address
				),

				'receiver' => array(
					'country' => $receiver_country, 
					'suburb'=>$receiver_suburb, 
					'postcode' => $receiver_postcode, 
					'type'=> 'residential'
				)
			);
		} else  {		
			$quoteDetails = array(
				'declared_value'=>10000, 
				'requesting_site' => $requesting_site,
				'items' => array(array(
						 		'width' => $cart_total_width, 
						 		'height' => $cart_total_height, 
						 		'weight'=> $cart_total_weight, 
						 		'length'=> $cart_total_length, 
						 		'quantity'=>$total_qty, 
						 		'description'=>'item description'
						 	)),	

				'sender' => array(
					'country' => 'AU', 
					'suburb'=> $warehouse_suburb, 
					'postcode' => $warehouse_postcode, 
					'type'=> $warehouse_address
				),

				'receiver' => array(
					'country' => $receiver_country, 
					'suburb'=>$receiver_suburb, 
					'postcode' => $receiver_postcode, 
					'type'=> 'residential'
				)
			);
		}

		$json_data = json_encode($quoteDetails);
		//echo '<pre>'; print_r($json_data); die('json');

			
		$ch = curl_init();
		// curl_setopt($ch, CURLOPT_URL, "https://www.staging.transdirect.com.au/api/bookings");
		curl_setopt($ch, CURLOPT_URL, "https://www.transdirect.com.au/api/bookings");
		curl_setopt($ch, CURLOPT_USERPWD, "$account_email:$account_password");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$response = curl_exec($ch);
		$report = curl_getinfo($ch);
		//echo '<pre>'; print_r($report);

		// var_dump($response);
		if(curl_errno($ch)) {
		   echo 'Response error: ' . curl_error($ch);
		}
		curl_close($ch);

		if ($response) {
			$json_decode_varible = json_decode($response, true);
			$quotes_val = $json_decode_varible['quotes'];
			$booking_id = $json_decode_varible['id'];
			//echo "<pre>"; print_r($quotes_val); die('quotes');
			Mage::getSingleton('core/session')->unsTransitVal();
			Mage::getSingleton('core/session')->unsSomeSessionVar();
			$session_val1 = Mage::getSingleton('core/session')->setSomeSessionVar1($quotes_val);
			$session_val2 = Mage::getSingleton('core/session')->setSomeSessionVar2($booking_id);
		} else {
			echo "Failed";
		}		

		/* End */

		//$available_carriers = array($display_carriers); 
		$available_carriers = explode( ',', $display_carriers);

		// function cmp1($a, $b) {
			// return strcmp($a['transit_time'], $b['transit_time']);	
		//}	
				
		// function build_sorter($key) {
			// return function ($a, $b) use ($key) {
				// return strnatcmp($a[$key], $b[$key]);
			// };

		// }


		function getCheapest($a, $b)
		{
			// Sort row primarily by total being cheapest total on top
		  	return $a['total'] - $b['total'];
		}

		
		$display_quote = Mage::getStoreConfig('transdirect_section/displayoptions/quotedisplay'); 
		//if($display_quote == 'display_cheapest'){ uasort($quotes_val, build_sorter('total')); }
		//if($display_quote == 'display_cheapest_fastest'){ uasort($quotes_val, build_sorter('transit_time')); }
		$handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling');
		$result = Mage::getModel('shipping/rate_result');
		$show = true;

		if($show){
			if($quotes_val == ''){
				Mage::getSingleton('core/session')->unsetAll();
				//Mage::getSingleton('core/session')->addError("Please enter correct details, either suburb or postcode not entered properly.");
				//session_write_close(); 
				//return false;
				$method = Mage::getModel('shipping/rate_result_method');
				$method->setCarrier($this->_code);
				$method->setMethod($this->_code);
				$method->setCarrierTitle($this->getConfigData('title'));
				$method->setMethodTitle('Fixed Price');
	

				if($display_fixedprice == '1'){
					$method->setPrice($display_fixedprice1);
					$method->setCost($display_fixedprice1);		
				}

				$result->append($method);

			} else {
				$quotesKeys = array_keys($quotes_val);
				$couriersConfig = unserialize($couriers_name);
				$courierVal;
				$x = 0;
				while (1) {
					if ($x >= count($quotesKeys) - 1) {
						break;
					}

					$key = $quotesKeys[$x];
					$val = $quotes_val[$key];
					$skip = false;
					// find the config for this quote
					if (isset($couriersConfig[$key])) {
						// var_dump($couriersConfig[$key]['enable_surcharge_courier']);
						$courierVal = $couriersConfig[$key];


						// var_dump($courierVal);
						if($courierVal['enable_surcharge_courier'] == 1) {	
							if($courierVal['surcharge_courier_unit'] == '%') {
								$courierVal['surcharge_courier'] = $courierVal['surcharge_courier'] / 100;
							}
							$val['total'] += (int) $courierVal['surcharge_courier'];
						}


						if($courierVal['enable_courier'] == 1) {
							if ($courierVal['rename_group']) {
								$renameKey = strtolower($courierVal['rename_group']);
								if (isset($quotes_val[$renameKey])) {
									if ($val['total'] < $quotes_val[$renameKey]['total']) {
										$quotes_val[$renameKey] = $val;
										$couriersConfig[$renameKey] = $courierVal;
										$couriersConfig[$renameKey]['rename_group'] = '';
										$couriersConfig[$renameKey]['enable_surcharge_courier'] = 0;
									}
								} else {
									$quotes_val[$renameKey] = $val;
									$couriersConfig[$renameKey] = $courierVal;
									$couriersConfig[$renameKey]['rename_group'] = '';
									$couriersConfig[$renameKey]['enable_surcharge_courier'] = 0;
								}

								$quotesKeys[] = $renameKey;
								$skip = true;
							}
						} else {
							$skip = true;
						}
					}
					
					$x++;
					
					if ($skip) {
						continue;
					}


					$method_title = ucwords(str_replace('_', ' ', $key));
					
					// if($key=='fastway'){ $method_title = "Fastway"; }
					// if($key=='toll_priority_overnight'){$method_title = "Toll Priority Overnight"; }
					// if($key=='couriers_please'){$method_title = "Couriers Please"; }
					// if($key=='allied'){$method_title = "Allied Express"; }
					// if($key=='toll'){ $method_title = "Toll"; }
					// if($key=='mainfreight'){ $method_title = "Mainfreight"; }
					// if($key=='northline'){$method_title = "Northline"; }
					// if($key=='toll_priority_sameday'){$method_title = "Toll Priority Sameday"; }
					// if($key=='auspost_regular_eparcel'){$method_title = "Auspost Regular Eparcel"; }
					// if($key=='auspost_express_eparcel'){$method_title = "Auspost Express Eparcel"; }
					// if($key=='tnt_nine_express'){$method_title = "TNT Nine Express"; }
					// if($key=='tnt_overnight_express'){$method_title = "TNT Overnight Express"; }
					// if($key=='tnt_road_express'){$method_title = "TNT Road Express"; }
					// if($key=='tnt_ten_express'){$method_title = "TNT Ten Express"; }
					// if($key=='tnt_twelve_express'){$method_title = "TNT Twelve Express"; }
					// if($key=='direct_couriers_regular'){$method_title = "Direct Couriers Regular"; }
					// if($key=='direct_couriers_express'){$method_title = "Direct Couriers Express"; }
					// if($key=='direct_couriers_elite'){$method_title = "Direct Couriers Elite"; }
					 
			 		$quote_form_region_val = Mage::getSingleton('core/session')->setTransitVal($quotes_val[$key]['transit_time']);		

					//echo $method_title; die;
					// echo count($val); die;
					//echo $key.'<br>';
					//print_r($available_carriers);
					//usort($quotes_val, 'cmp1');
					// var_dump($couriersConfig[$key]);	
					$courier_title = $method_title;
					$courier_price = $val['total'];


					$method = Mage::getModel('shipping/rate_result_method');
					$method->setCarrier($this->_code);
					$method->setMethod($courier_title);
					$method->setCarrierTitle($this->getConfigData('title'));

					if($courierVal['enable_surcharge_courier'] == 1) {
						$courier_price = $courier_price + ($courier_price * $courierVal['surcharge_courier']);
					}

					if($display_surcharge == '1'){
						if($display_surcharge_unit == '%') {
							$display_surcharge1 = $display_surcharge1 / 100;

							$tmp_display_surcharge = $courier_price + ($courier_price * $display_surcharge1);
						} else {

							$tmp_display_surcharge = $courier_price + $display_surcharge1;
						}

						$tmp_method_title = $method_title.' + Handling Charge';

							// if(in_array($key,$available_carriers)) {
								$method->setMethodTitle($tmp_method_title);
								$method->setPrice($tmp_display_surcharge);
								$method->setCost($tmp_display_surcharge);	
							// }

					} else {

						// if(in_array($key,$available_carriers)) {
							//echo '<br>'.$key . ' - matched<br>';
							$method->setMethodTitle($method_title);
							$method->setSortOrder($quotes_val[$key]['transit_time']);
							$method->setPrice($courier_price);
							$method->setCost($courier_price);
						// }	
					}

					$result->append($method);
				}

				//die('arr');
			}	
			//echo '<pre>'; print_r($result);  die('model');
		} else{
			$error = Mage::getModel('shipping/rate_result_error');
			$error->setCarrier($this->_code);
			$error->setCarrierTitle($this->getConfigData('name'));
			$error->setErrorMessage($this->getConfigData('specificerrmsg'));
			$result->append($error);
		}
		return $result;
	}

	public function getAllowedMethods() {
		return array('transdirect'=>$this->getConfigData('name'));
	}

}