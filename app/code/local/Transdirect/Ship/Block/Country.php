<?php
class Transdirect_Ship_Block_Country extends Mage_Core_Block_Text
{
	 public function _toHtml()
    {
    	// is_render_to_js_template,type,name,column_name,column,module_name

    	// groups[displayoptions][fields][shipping_costs][value][0][enable_courier]
    	// groups[displayoptions][fields][shipping_costs][value][0][country]
        return '<span>#{' . $this->getData('column_name') . '}</span>';
    }
 
    public function setInputName($value)
    {
        return $this->setName($value);
    }
}