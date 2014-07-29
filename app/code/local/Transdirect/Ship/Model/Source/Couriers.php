<?php

class Transdirect_Ship_Model_Source_Couriers extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('ship');
		return array(
			array('value' => 'toll', 'label' => $hlp->__('Toll')),
			array('value' => 'toll_priority_overnight', 'label' => $hlp->__('Toll Priority')),
			array('value' => 'allied', 'label' => $hlp->__('Allied Express')),
			array('value' => 'couriers_please', 'label' => $hlp->__('Couriers Please')),
			array('value' => 'fastway', 'label' => $hlp->__('Fastway')),
			array('value' => 'mainfreight', 'label' => $hlp->__('Mainfreight')),
			array('value' => 'northline', 'label' => $hlp->__('Northline')),
		);
	}
	
}