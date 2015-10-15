<?php

class Transdirect_Ship_Model_System_Config_Backend_Serialized extends Mage_Adminhtml_Model_System_Config_Backend_Serialized
{

    protected function _afterLoad()
    {
        if (!is_array($this->getValue())) {
            $couriers = new Transdirect_Ship_Model_Source_Couriers();
            $value = $this->getValue();
            $value = unserialize($value);
            if (empty($value)) {
                $value = array();
                foreach ($couriers->toOptionArray() as $courier) {
                    $value[$courier['value']] = array(
                        'enable_courier' => '',
                        'courier' => $courier['label'],
                        'rename_group' => '',
                        'surcharge_courier' => 0,
                        'enable_surcharge_courier' => ''
                    );
                }
            } else {
                foreach ($couriers->toOptionArray() as $courier) {
                    $value[$courier['value']]['courier'] = $courier['label'];
                }
            }
            
            $this->setValue($value);
        }
    }

    protected function _beforeSave()
    {
        $value = $this->getValue();
        if (is_array($value)) {
            unset($value['__empty']);
        }
        $this->setValue($value);
        parent::_beforeSave();
    }
}
