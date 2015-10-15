<?php

class Transdirect_Ship_Model_Observer{

    public function Savefield($observer){
        // session id
        $booking_id = Mage::getSingleton('core/session')->getSomeSessionVar2();
        //get event data
        $event = $observer->getEvent();
        //get order
        $order = $event->getOrder();
        //set the booking id here
        $order->setBookingId($booking_id);
    }  


	// public function adminSystemConfigChangedSection() {
		// add cron job here

		// $config_order_sync_enable = Mage::getStoreConfig('transdirect_section/ordersync/enableordersync');
		// $config_order_stat = Mage::getStoreConfig('transdirect_section/ordersync/orderstatus');
  //       $config_from_date = Mage::getStoreConfig('transdirect_section/ordersync/fromdate');
		// $collection = Mage::getResourceModel('sales/order_collection')->addAttributeToSelect('*');
  //       $account_email           = Mage::getStoreConfig('transdirect_section/authentication/email');
  //       $account_password        = Mage::getStoreConfig('transdirect_section/authentication/password');
  //       $api_array = '';


  //       $ch1 = curl_init();
  //       curl_setopt($ch1, CURLOPT_URL, "https://www.transdirect.com.au/api/orders/");
  //       curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
  //       curl_setopt($ch1, CURLOPT_HEADER, FALSE);
  //       curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
  //         "Authorization: Basic  " . base64_encode($account_email . ":" . $account_password),
  //         "Content-Type: application/json"
  //       ));
  //       curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
  //       $response = curl_exec($ch1);
  //       curl_close($ch1);
  //       $response_order = json_decode($response);

  //       var_dump($response_order);

  //       $dateString = strtotime($config_from_date);
  //       $fromDateFormat = date('Y-m-d',$dateString);
  //       $dt = new DateTime();
  //       $imported_time = $dt->format('H:i:s');

  //       if($config_order_sync_enable == 1) {

  //           foreach ($collection as $col) {
  //               $items = $col->getAllVisibleItems();
  //               $sku = '';
  //               $delimiter = 'transdirect_';
  //               $selected_courier = $col->getShippingMethod(); 
  //               if(strpos($selected_courier, $delimiter) !== FALSE) {
  //                   $selected_courier = substr($selected_courier, strpos($selected_courier, $delimiter) + strlen($delimiter));
  //               } 

  //               foreach ($items as $item) {
  //                   $sku = $item->getSku();
  //               }
  //               $address = Mage::getModel('sales/order_address')->load($col->getShippingAddressId());
  //               $fromdate = substr($col->getUpdatedAt(), 0, strpos($col->getUpdatedAt(), ' '));
  //               if($col->getStatus() == $config_order_stat && strtotime($fromdate) >= strtotime($fromDateFormat)) {

  //                   $api_array['transdirect_order_id']  = (int) $col->getBookingId();
  //                   $api_array['transdirect_order_status']  = $col->getStatus();
  //                   $api_array['order_id'] = $col->getIncrementId();
  //                   $api_array['goods_summary'] = $sku;
  //                   $api_array['goods_dump'] = 'test';
  //                   $api_array['imported_from'] = 'Magento';
  //                   $api_array['purchased_time'] = $col->getCreatedAt();
  //                   $api_array['sale_price'] = number_format($col->getBaseSubtotal(), 2);
  //                   $api_array['selected_courier'] = strtolower($selected_courier);
  //                   $api_array['courier_price'] = number_format($col->getShippingAmount(), 2);
  //                   $api_array['paid_time'] = '2015-06-01T16:06:52+1000';
  //                   $api_array['buyer_name'] = $col->getCustomerFirstname() .' '. $col->getCustomerLastname();
  //                   $api_array['buyer_email'] = $col->getCustomerEmail();
  //                   $api_array['delivery']['name'] = $address->getFirstname() .' '. $address->getLastname();
  //                   $api_array['delivery']['email'] = $address->getEmail();
  //                   $api_array['delivery']['phone'] = $address->getTelephone();
  //                   $api_array['delivery']['address'] = $address->getStreetFull() .' '. $address->getCity() .', '. $address->getPostcode();
  //                   $api_array['last_updated'] = $col->getUpdatedAt();

  //                   $found = false;
  //                   $foundOrder;
  //                   foreach ($response_order as $key => $value) {
  //                       if($value->order_id == $col->getIncrementId()) {
  //                           $foundOrder = $value;
  //                           $found = true;
  //                           break;
  //                       }
  //                   }

  //                   if ($found) {
  //                       if ($foundOrder->last_updated <= $col->getUpdatedAt()) {
  //                           $id  = (int) $foundOrder->id;
  //                           $ch2 = curl_init();
  //                           curl_setopt($ch2, CURLOPT_URL, "https://www.transdirect.com.au/api/orders/". $id);
  //                           curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
  //                           curl_setopt($ch2, CURLOPT_HEADER, FALSE);
  //                           curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "PUT");
  //                           curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($api_array));
  //                           curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
  //                             "Authorization: Basic  " . base64_encode($account_email . ":" . $account_password),
  //                             "Content-Type: application/json"
  //                           ));
  //                           curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
  //                           curl_exec($ch2);
  //                           curl_close($ch2);
  //                       }
  //                   } else {
  //                       $json_data = json_encode($api_array);
  //                       $ch = curl_init();
  //                       curl_setopt($ch, CURLOPT_URL, "https://www.transdirect.com.au/api/orders");
  //                       curl_setopt($ch, CURLOPT_USERPWD, "$account_email:$account_password");
  //                       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
  //                       curl_setopt($ch, CURLOPT_HEADER, FALSE);
  //                       curl_setopt($ch, CURLOPT_POST, TRUE);
  //                       curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_array));
  //                       curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  //                       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  //                       curl_exec($ch);
  //                       curl_getinfo($ch);
  //                       curl_close($ch);
  //                   }
  //               }
  //           }   
  //       }
		
		// exit();	
	// }

    public function method1() {  
    }

	public function cronOderSync() {
        // add cron job here
        Mage::log("WORKS!");
        $config_order_sync_enable = Mage::getStoreConfig('transdirect_section/ordersync/enableordersync');
        $config_order_stat = Mage::getStoreConfig('transdirect_section/ordersync/orderstatus');
        $config_from_date = Mage::getStoreConfig('transdirect_section/ordersync/fromdate');
        $collection = Mage::getResourceModel('sales/order_collection')->addAttributeToSelect('*');
        $account_email           = Mage::getStoreConfig('transdirect_section/authentication/email');
        $account_password        = Mage::getStoreConfig('transdirect_section/authentication/password');
        $api_array = '';

        $ch1 = curl_init();
        curl_setopt($ch1, CURLOPT_URL, "https://www.transdirect.com.au/api/orders/");
        curl_setopt($ch1, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch1, CURLOPT_HEADER, FALSE);
        curl_setopt($ch1, CURLOPT_HTTPHEADER, array(
          "Authorization: Basic  " . base64_encode($account_email . ":" . $account_password),
          "Content-Type: application/json"
        ));
        curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch1);
        curl_close($ch1);
        $response_order = json_decode($response);

        $dateString = strtotime($config_from_date);
        $fromDateFormat = date('Y-m-d',$dateString);
        $dt = new DateTime();
        $imported_time = $dt->format('H:i:s');

        if($config_order_sync_enable == 1) {

            foreach ($collection as $col) {
                $items = $col->getAllVisibleItems();
                $sku = '';
                $delimiter = 'transdirect_';
                $selected_courier = $col->getShippingMethod(); 
                if(strpos($selected_courier, $delimiter) !== FALSE) {
                    $selected_courier = substr($selected_courier, strpos($selected_courier, $delimiter) + strlen($delimiter));
                } 

                foreach ($items as $item) {
                    $sku = $item->getSku();
                }
                $address = Mage::getModel('sales/order_address')->load($col->getShippingAddressId());
                $fromdate = substr($col->getUpdatedAt(), 0, strpos($col->getUpdatedAt(), ' '));
                if($col->getStatus() == $config_order_stat && strtotime($fromdate) >= strtotime($fromDateFormat)) {

                    $api_array['transdirect_order_id']  = (int) $col->getBookingId();
                    $api_array['transdirect_order_status']  = $col->getStatus();
                    $api_array['order_id'] = $col->getIncrementId();
                    $api_array['goods_summary'] = $sku;
                    $api_array['goods_dump'] = 'test';
                    $api_array['imported_from'] = 'Magento';
                    $api_array['purchased_time'] = $col->getCreatedAt();
                    $api_array['sale_price'] = number_format($col->getBaseSubtotal(), 2);
                    $api_array['selected_courier'] = strtolower($selected_courier);
                    $api_array['courier_price'] = number_format($col->getShippingAmount(), 2);
                    $api_array['paid_time'] = '2015-06-01T16:06:52+1000';
                    $api_array['buyer_name'] = $col->getCustomerFirstname() .' '. $col->getCustomerLastname();
                    $api_array['buyer_email'] = $col->getCustomerEmail();
                    $api_array['delivery']['name'] = $address->getFirstname() .' '. $address->getLastname();
                    $api_array['delivery']['email'] = $address->getEmail();
                    $api_array['delivery']['phone'] = $address->getTelephone();
                    $api_array['delivery']['address'] = $address->getStreetFull() .' '. $address->getCity() .', '. $address->getPostcode();
                    $api_array['last_updated'] = $col->getUpdatedAt();

                    $found = false;
                    $foundOrder;
                    foreach ($response_order as $key => $value) {
                        if($value->order_id == $col->getIncrementId()) {
                            $foundOrder = $value;
                            $found = true;
                            break;
                        }
                    }

                    if ($found) {
                        if ($foundOrder->last_updated <= $col->getUpdatedAt()) {
                            $id  = (int) $foundOrder->id;
                            $ch2 = curl_init();
                            curl_setopt($ch2, CURLOPT_URL, "https://www.transdirect.com.au/api/orders/". $id);
                            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, TRUE);
                            curl_setopt($ch2, CURLOPT_HEADER, FALSE);
                            curl_setopt($ch2, CURLOPT_CUSTOMREQUEST, "PUT");
                            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($api_array));
                            curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
                              "Authorization: Basic  " . base64_encode($account_email . ":" . $account_password),
                              "Content-Type: application/json"
                            ));
                            curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
                            curl_exec($ch2);
                            curl_close($ch2);
                        }
                    } else {
                        $json_data = json_encode($api_array);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://www.transdirect.com.au/api/orders");
                        curl_setopt($ch, CURLOPT_USERPWD, "$account_email:$account_password");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_HEADER, FALSE);
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_array));
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_exec($ch);
                        curl_getinfo($ch);
                        curl_close($ch);
                    }
                }
            }   
        }
    }

}


