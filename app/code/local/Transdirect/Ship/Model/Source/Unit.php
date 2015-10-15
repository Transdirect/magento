<?php

class Transdirect_Ship_Model_Source_Unit extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('ship');
		return array(
			array('value' => '$', 'label' => $hlp->__('$')),
			array('value' => '%', 'label' => $hlp->__('%')),
		);
	}
	
}