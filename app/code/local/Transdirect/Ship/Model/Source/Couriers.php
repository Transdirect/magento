<?php

class Transdirect_Ship_Model_Source_Couriers extends Varien_Object
{
	public function toOptionArray()
	{
	    $hlp = Mage::helper('ship');
		return array(
			array('value' => 'allied', 'label' => $hlp->__('Allied Express')),
			array('value' => 'couriers_please', 'label' => $hlp->__('Couriers Please')),
			array('value' => 'fastway', 'label' => $hlp->__('Fastway')),
			array('value' => 'northline', 'label' => $hlp->__('Northline')),
			array('value' => 'toll', 'label' => $hlp->__('Toll')),
			array('value' => 'toll_priority_sameday', 'label' => $hlp->__('Toll Priority Sameday')),
			array('value' => 'toll_priority_overnight', 'label' => $hlp->__('Toll Priority')),
			/*array('value' => 'auspost_regular_eparcel', 'label' => $hlp->__('Auspost Regular Eparcel')),
			array('value' => 'auspost_express_eparcel', 'label' => $hlp->__('Auspost Express Eparcel')),*/
			array('value' => 'tnt_nine_express', 'label' => $hlp->__('TNT Nine Express')),
			array('value' => 'tnt_overnight_express', 'label' => $hlp->__('TNT Overnight Express')),
			array('value' => 'tnt_road_express', 'label' => $hlp->__('TNT Road Express')),
			array('value' => 'tnt_ten_express', 'label' => $hlp->__('TNT Ten Express')),
			array('value' => 'tnt_twelve_express', 'label' => $hlp->__('TNT Twelve Express')),
                        array('value' => 'tnt_international_express_export', 'label' => $hlp->__('TNT International Express Export')),
                        array('value' => 'tnt_international_express_import', 'label' => $hlp->__('TNT International Express Import')),
                        array('value' => 'tnt_international_express_document_export', 'label' => $hlp->__('TNT International Express Document Export')),
                        array('value' => 'tnt_international_express_document_import', 'label' => $hlp->__('TNT International Express Document Import')),
                        array('value' => 'tnt_international_economy_express_export', 'label' => $hlp->__('TNT International Economy Express Export')),
                        array('value' => 'tnt_international_economy_express_import', 'label' => $hlp->__('TNT International Economy Express Import')),
                        array('value' => 'tnt_international_economy_express_document_export', 'label' => $hlp->__('TNT International Economy Express Document Export')),
                        array('value' => 'tnt_international_economy_express_document_import', 'label' => $hlp->__('TNT International Economy Express Document Import')),                    
			array('value' => 'direct_couriers_regular', 'label' => $hlp->__('Direct Regular Couriers')),
			array('value' => 'direct_couriers_express', 'label' => $hlp->__('Direct Express Couriers')),
			array('value' => 'direct_couriers_elite', 'label' => $hlp->__('Direct Elite Couriers')),
		);
	}
}
