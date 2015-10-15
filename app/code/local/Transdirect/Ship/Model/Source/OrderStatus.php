<?php

class Transdirect_Ship_Model_Source_OrderStatus extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('ship');
		$orderStatusCollection = Mage::getModel('sales/order_status')->getResourceCollection()->getData();
		$status = array();

		foreach($orderStatusCollection as $orderStatus) {
		    $status[] = array (
		        'value' => $orderStatus['status'], 'label' => $orderStatus['label']
		    );
		}

		return $status;
	}
	
}