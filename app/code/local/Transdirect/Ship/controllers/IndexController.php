<?php

class Transdirect_Ship_IndexController extends Mage_Core_Controller_Front_Action

{



protected function _getCart()

    {

        return Mage::getSingleton('checkout/cart');

    }



    /**

     * Get checkout session model instance

     *

     * @return Mage_Checkout_Model_Session

     */

    protected function _getSession()

    {

        return Mage::getSingleton('checkout/session');

    }



    /**

     * Get current active quote instance

     *

     * @return Mage_Sales_Model_Quote

     */

    protected function _getQuote()

    {

			

        return $this->_getCart()->getQuote();

    }

	

	protected function _goBack()

    {   

         $returnUrl = $this->getRequest()->getParam('return_url');

        if ($returnUrl) {

			//die('if');

			

            if (!$this->_isUrlInternal($returnUrl)) {

                throw new Mage_Exception('External urls redirect to "' . $returnUrl . '" denied!');

            }



            $this->_getSession()->getMessages(true);

            $this->getResponse()->setRedirect($returnUrl);

        } elseif (!Mage::getStoreConfig('checkout/cart/redirect_to_cart')

		

            && !$this->getRequest()->getParam('in_cart')

            && $backUrl = $this->_getRefererUrl()

        ) {// die('else if');

            $this->getResponse()->setRedirect($backUrl);

        } else {

			//die('else');

			

            if (($this->getRequest()->getActionName() == 'add') && !$this->getRequest()->getParam('in_cart')) {

                $this->_getSession()->setContinueShoppingUrl($this->_getRefererUrl());

            }

            $this->_redirect('checkout/cart');

			//die('go back');

			//$this->_redirectReferer();

        }

        return $this;

    }



    function indexAction()

    {	

		//die('index');

		

		//$this->loadLayout();

		

		$account_email = Mage::getStoreConfig('transdirect_section/authentication/email');

		$account_password = Mage::getStoreConfig('transdirect_section/authentication/password');

		$warehouse_postcode = Mage::getStoreConfig('transdirect_section/warehouseaddress/postcode');

		$warehouse_address = Mage::getStoreConfig('transdirect_section/warehouseaddress/address');

		$dimension_width = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionswidth');

		$dimension_height = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionsheight');

		$dimension_dim = Mage::getStoreConfig('transdirect_section/defaultitemsize/dimensionsdim');

		$dimension_weight = Mage::getStoreConfig('transdirect_section/defaultitemsize/weight');

		$display_carriers = Mage::getStoreConfig('transdirect_section/displayoptions/availablecoriers');

		$display_quote = Mage::getStoreConfig('transdirect_section/displayoptions/quotedisplay');

		$display_fixedprice = Mage::getStoreConfig('transdirect_section/displayoptions/fixedpriceonerror');

		$display_fixedprice1 = Mage::getStoreConfig('transdirect_section/displayoptions/fixedpriceonerror1');

		$display_showcouriername = Mage::getStoreConfig('transdirect_section/displayoptions/showcouriernames');

		$display_surcharge = Mage::getStoreConfig('transdirect_section/displayoptions/handlingsurcharge');

		$display_surcharge1 = Mage::getStoreConfig('transdirect_section/displayoptions/handlingsurcharge1');

		$display_includesurchage = Mage::getStoreConfig('transdirect_section/displayoptions/includesurcharge');

	



		// Cart Total Weight Code Start by Nayan

		

		$quote = Mage::getSingleton('checkout/session')->getQuote();

        $cartItems = $quote->getAllVisibleItems();

		//$total_qty = Mage::helper('checkout/cart')->getSummaryCount(); 

		$total_qty = '1';



/*		if(Mage::registry('current_product')) {

		  //this is a product page, do some stuff

		  die('product');

		}

		else {

		  die('not product');

		  	}

			

		echo Mage::app()->getFrontController()->getRequest()->getRouteName();

		die('name');*/

		

		$weight = 0;

		$height = 0;

		$width = 0;

		$length = 0;

        foreach ($cartItems as $item)

        {

			//echo '<pre>'; print_r($item->getQty()); die;

            $productId = $item->getProductId();

			$productQty = $item->getQty();



            $product = Mage::getModel('catalog/product')->load($productId);

            //echo $product->getItemWeight();

		 	 $weight += $product->getItemWeight() * $productQty;

		 	 $height += $product->getItemHeight() * $productQty;

		 	 $width += $product->getItemWidth() * $productQty;

		 	 $length += $product->getItemDim() * $productQty;

         }

		

		$product = Mage::getModel('catalog/product')->load($productId);

		

		$cart_total_weight = $product->getItemWeight(); 

		$cart_total_height = $product->getItemHeight();  

		$cart_total_width =  $product->getItemWidth();

		$cart_total_length = $product->getItemDim(); 

		

		// Cart Total Weight Code End by Nayan 



		

		if(!$cart_total_weight){ $cart_total_weight = $dimension_weight; }

		if(!$cart_total_height){ $cart_total_height = $dimension_height; }

		if(!$cart_total_width){ $cart_total_width = $dimension_width; }

		if(!$cart_total_length){ $cart_total_length = $dimension_dim; }



/*		echo 'width--'.$cart_total_weight; 

		echo 'height---'.$cart_total_height; 

		echo 'width---'.$cart_total_width; 

		echo 'length---'.$cart_total_length; 

		die('here');

*/



		// Getting Cart page Quote Address Details Code Start by Nayan



		$receiver_country    = (string) $this->getRequest()->getParam('country_id');

		$receiver_postcode   = (string) $this->getRequest()->getParam('estimate_postcode');

		$receiver_regionId   = (string) $this->getRequest()->getParam('region_id');

		$receiver_region     = (string) $this->getRequest()->getParam('region');



		$region = Mage::getModel('directory/region')->load($receiver_regionId);

		$receiver_region_tmp = $region->getName();

		

		/*if($receiver_region == '') {

	        Mage::getSingleton('checkout/session')->addError("Please enter state/province.");

			session_write_close(); 

			$this->_redirectReferer();

		}*/

		

		/*if(!is_numeric($receiver_postcode) || $receiver_postcode == "0" || $receiver_postcode == "00" || $receiver_postcode == "000" || $receiver_postcode == "0000" || $receiver_postcode == "00000" || $receiver_postcode == "000000"  || $receiver_postcode == "0000000") {

	        Mage::getSingleton('checkout/session')->addError("Please enter valid and correct postcode.");

			session_write_close(); 

			$this->_redirectReferer();	

		}*/

		

		if(!$receiver_regionId)	

		{ 

		  $receiver_suburb = $receiver_region; 

		}

		else 

		{ 

		  $receiver_suburb = $receiver_region_tmp; 

		}

		

		

		$receiver_postcode = $_COOKIE['cart_postocde'];

		$receiver_suburb = $_COOKIE['cart_locality']; 

		

		//echo $receiver_suburb; die('suburb');

		

		// Getting Cart page Quote Address Details Code End by Nayan



		$quoteDetails = array(

						'declared_value'=>10000, 

						'items' => array

						  (

						    array('width' => $cart_total_width, 'height' => $cart_total_height, 'weight'=> $cart_total_weight, 'length'=> $cart_total_length, 'quantity'=>$total_qty, 'description'=>'item description')

						  ),	

						  'sender' => array('country' => 'AU', 'suburb'=>'SYDNEY', 'postcode' => $warehouse_postcode, 'type'=> $warehouse_address),

						  'receiver' => array('country' => $receiver_country, 'suburb'=>$receiver_suburb, 'postcode' => $receiver_postcode, 'type'=> 'residential')

					  );



		$json_data = json_encode($quoteDetails);

		//echo '<pre>'; print_r($json_data); die('encode');

				

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, "https://www.staging.transdirect.com.au/api/bookings");

		//curl_setopt($ch, CURLOPT_URL, "https://www.transdirect.com.au/api/bookings");

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

		

		if(curl_errno($ch)) {

		   echo 'Response error: ' . curl_error($ch);

		}

		

		curl_close($ch);

		

		if ($response) {

			//echo '<pre>'.$response;

			

			

			

			$json_decode_varible = json_decode($response, true);

			 

			$quotes_val = $json_decode_varible['quotes'];

			//echo "<pre>"; print_r($quotes_val); die('decode');

			

			

			// Passing Data from Controller to Template file code Start 

				

				

			//$quotes_val = Mage::register('quotes_val', $quotes_val);

			

			// End

			

			Mage::getSingleton('core/session')->unsSomeSessionVar();

			Mage::getSingleton('core/session')->unsCountryVal();

			Mage::getSingleton('core/session')->unsPostcodeVal();

			Mage::getSingleton('core/session')->unsRegionIdVal();	

			Mage::getSingleton('core/session')->unsRegionVal();			

			

			$country    = (string) $this->getRequest()->getParam('country_id');

			$postcode   = (string) $this->getRequest()->getParam('estimate_postcode');

			$regionId   = (string) $this->getRequest()->getParam('region_id');

			$region     = (string) $this->getRequest()->getParam('region');

	

			//echo $country. $postcode . $city . $regionId . $region; die('data');

			

			//echo "<pre>"; print_r($quotes_val); 

			

			/*function build_sorter($key) {

				return function ($a, $b) use ($key) {

					return strnatcmp($a[$key], $b[$key]);

				};

			}*/

			

			$display_quote = Mage::getStoreConfig('transdirect_section/displayoptions/quotedisplay'); 

					

				//if($display_quote == 'display_cheapest'){  uasort($quotes_val, build_sorter('total')); }

				//if($display_quote == 'display_cheapest_fastest'){  uasort($quotes_val, build_sorter('transit_time')); }

			

			//echo "<pre>"; print_r($quotes_val); die('after');

			

			$session_val = Mage::getSingleton('core/session')->setSomeSessionVar($quotes_val); // In the Controller



			$quote_form_country_val = Mage::getSingleton('core/session')->setCountryVal($country); // In the Controller

			$quote_form_postcode_val = Mage::getSingleton('core/session')->setPostcodeVal($postcode); // In the Controller

			$quote_form_regionId_val = Mage::getSingleton('core/session')->setRegionIdVal($regionId); // In the Controller

			$quote_form_region_val = Mage::getSingleton('core/session')->setRegionVal($region); // In the Controller

			

			

			

			//echo '<pre>'; print_r($session_val->getSomeSessionVar());

			//die('set');

			

			/*

			 foreach($quotes_val as $key => $val) {

				 $courier_title = $key;

				 $courier_price = $quotes_val[$key]['total'];

				

				// Mage::register('couriertitledisplay', $courier_title);

				// Mage::register('courierpricedisplay', $courier_price);

				 

			

			 			

						$block = $this->getLayout()->createBlock(

                               'Mage_Core_Block_Template',

                               'ship.quotes',

								 array(

									 'template' => 'ship/quotes.phtml'

								 )

						) // NOTE - Custom Variables Below

						->setData('courier_title', $courier_title)

						->setData('courier_price', $courier_price);

					

				 

					 $this->getLayout()->getBlock('content')->append($block);

			 }

			 */		 

			 

		} else {

			echo "Failed";

		}

		

		

		$available_carriers = explode( ',', $display_carriers);



		$handling = Mage::getStoreConfig('carriers/'.$this->_code.'/handling');

		$result = Mage::getModel('shipping/rate_result');

		$show = true;

		if($show){

			

		//die('before');

		//$quotes_val = Mage::getSingleton('core/session')->getSomeSessionVar();

		

		//$session = Mage::getSingleton('core/session', array('name' => 'frontend'));

		//echo $session->getSomeSessionVar();



		//echo '<pre>'; print_r($session->getSomeSessionVar());

		//die('after');

		

				if($quotes_val == ''){ //die('if');

				//echo $quote_form_postcode_val; die;

				   /* if(!is_numeric($quote_form_postcode_val) || $quote_form_postcode_val == "0" || $quote_form_postcode_val == "00" || $quote_form_postcode_val == "000" || $quote_form_postcode_val == "0000" || $quote_form_postcode_val == "00000" || $quote_form_postcode_val == "000000"  || $quote_form_postcode_val == "0000000") {

	       						 Mage::getSingleton('checkout/session')->addError("Please enter valid and correct postcode.");

					} else {*/

						// Mage::getSingleton('core/session')->addError("Please enter correct details, either suburb or postcode not entered properly.");

					//}

			

			

						//Mage::getSingleton('core/session')->addError("Please fill the information properly, there are some fields are missing or not entered properly.");

						

						$method = Mage::getModel('shipping/rate_result_method');

						$method->setCarrier($this->_code);

						$method->setMethod($this->_code);

						$method->setCarrierTitle('Carrier');

						$method->setMethodTitle('Fixed Price');

						

						if($display_fixedprice == '1'){

							$method->setPrice($display_fixedprice1);

							$method->setCost($display_fixedprice1);		

						}

						

						$result->append($method);

					

				} 

				else {

					//die('else');

					

					

					

					 foreach($quotes_val as $key => $val) {

						// echo count($val); die;

						//echo $key.'<br>';

						//print_r($available_carriers);

						

						//usort($quotes_val, 'cmp1');	

						

						$courier_title = $key;

						$courier_price = $quotes_val[$key]['total'];

						

						$method = Mage::getModel('shipping/rate_result_method');

						$method->setCarrier($this->_code);

						$method->setMethod($courier_title);

						$method->setCarrierTitle('Carriers');

						

						if($display_surcharge == '1'){

							

							$tmp_display_surcharge = $courier_price + $display_surcharge1;

							$tmp_method_title = $courier_title.' + Handling Charge';

							

							$method->setMethodTitle($tmp_method_title);

							$method->setPrice($tmp_display_surcharge);

							$method->setCost($tmp_display_surcharge);	

	

						} else {

						

							if(in_array($key,$available_carriers)) {

								//echo '<br>'.$key . ' - matched<br>';

								$method->setMethodTitle($courier_title);

								$method->setPrice($courier_price);

								$method->setCost($courier_price);

							} else {

								//echo '<br>'.$key . ' - not found<br>';

							}

						

							

						

						}

						

						$result->append($method);

			

					}

					//die('arr');

				}	



						

			//echo '<pre>'; print_r($result);  //die('model');



		}else{

			$error = Mage::getModel('shipping/rate_result_error');

			$error->setCarrier($this->_code);

			$error->setCarrierTitle('Carrier Title');

			$error->setErrorMessage('Error');

			$result->append($error);

		}

		

		//return $result;

		

		

		//$this->renderLayout();

		 $this->_getQuote()->save();

		 $this->_goBack();

		 

		$this->_redirectReferer();



		return $this;

    }

	

	function productAction(){

		$country    = (string) $this->getRequest()->getParam('country_id');

        $postcode   = (string) $this->getRequest()->getParam('estimate_postcode');

        $city       = (string) $this->getRequest()->getParam('estimate_city');

        $regionId   = (string) $this->getRequest()->getParam('region_id');

        $region     = (string) $this->getRequest()->getParam('region');



        $this->_getQuote()->getShippingAddress()

            ->setCountryId($country)

            ->setCity($city)

            ->setPostcode($postcode)

            ->setRegionId($regionId)

            ->setRegion($region)

            ->setCollectShippingRates(true);

        $this->_getQuote()->save();

        $this->_goBack();

		//$this->_redirectReferer();

		

	}

	

	

	

}