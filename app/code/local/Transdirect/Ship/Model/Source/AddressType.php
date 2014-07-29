<?php



class Transdirect_Ship_Model_Source_AddressType extends Varien_Object

{

	public function toOptionArray()

	{

	    $hlp = Mage::helper('ship');

		return array(

			array('value' => 'residential', 'label' => $hlp->__('Residential')),

			array('value' => 'commercial', 'label' => $hlp->__('Commercial')),

		);

	}

	

}