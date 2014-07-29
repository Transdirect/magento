<?php

class Transdirect_Ship_Model_Source_Quotes extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('ship');
		return array(
			array('value' => 'display_all_quotes', 'label' => $hlp->__('Display all Quotes')),
			array('value' => 'display_cheapest', 'label' => $hlp->__('Display Cheapest Quotes')),
			array('value' => 'display_cheapest_fastest', 'label' => $hlp->__('Display Fastest Quotes')),
		);
	}
	
}