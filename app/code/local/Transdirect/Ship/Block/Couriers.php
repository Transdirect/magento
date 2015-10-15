<?php
class Transdirect_Ship_Block_Couriers
    extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_itemEnabledRenderer;
    protected $_itemRenderer;
    protected $_itemSurchargeRenderer;

    public function _prepareToRender()
    {	
        $this->addColumn('enable_courier', array(
            'label' => Mage::helper('ship')->__('Enable'),
            'class' => 'surchargecourier',
            'renderer' => $this->_getEnabledRenderer(),
        ));
        $this->addColumn('courier', array(
            'label' => Mage::helper('ship')->__('Courier'),   
            'renderer' => $this->_getRenderer()
        ));

        $this->addColumn('rename_group', array(
            'label' => Mage::helper('ship')->__('Rename/Group'),
            'style' => 'width:100px',
        ));

        $this->addColumn('surcharge_courier', array(
            'label' => Mage::helper('ship')->__('Surcharge'),
            'class' => 'surchargecourier',
            'style' => 'width:100px',
        ));

       $this->addColumn('surcharge_courier_unit', array(
            'label' => Mage::helper('ship')->__('Unit'),
            'class' => 'surchargecourier',
            'renderer' => $this->_getUnitRenderer(),
        ));

        $this->addColumn('enable_surcharge_courier', array(
            'label' => Mage::helper('ship')->__('Enable Surcharge'),
            'renderer' => $this->_getEnabledSurchageRenderer(),
        ));

        $this->_addAfter = false;
        // $this->_addButtonLabel = false;
    }

    /**
     * Check if columns are defined, set template
     *
     */
    public function __construct()
    {
        if (!$this->_addButtonLabel) {
            $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add');
        }
        // parent::__construct();
        if (!$this->getTemplate()) {
            $this->setTemplate('system/config/form/field/couriersarray.phtml');
        }
    }

    protected function _getUnitRenderer() 
    {
        if (!$this->_itemUnitRenderer) {
            $this->_itemUnitRenderer = $this->getLayout()->createBlock(
                'ship/unitcourier', '',
                array('is_render_to_js_template' => true, 'class' => 'enable')
            );
        }
        return $this->_itemUnitRenderer;
    }

    protected function _getEnabledRenderer() 
    {
        if (!$this->_itemEnabledRenderer) {
            $this->_itemEnabledRenderer = $this->getLayout()->createBlock(
                'ship/enablecourier', '',
                array('is_render_to_js_template' => true, 'class' => 'enable')
            );
        }
        return $this->_itemEnabledRenderer;
    }

    protected function _getEnabledSurchageRenderer() 
    {
        if (!$this->_itemSurchargeRenderer) {
            $this->_itemSurchargeRenderer = $this->getLayout()->createBlock(
                'ship/enablecourier', '',
                array('is_render_to_js_template' => true, 'class' => 'enable')
            );
        }
        return $this->_itemSurchargeRenderer;
    }

    protected function _getRenderer() 
    {
        if (!$this->_itemRenderer) {
            $this->_itemRenderer = $this->getLayout()->createBlock(
                'ship/country', '',
                array('is_render_to_js_template' => true)
            );
        }
        return $this->_itemRenderer;
    }

     protected function _prepareArrayRow(Varien_Object $row)
    {
        $row->setData(
            'option_extra_attr_' . $this->_getEnabledRenderer()
                ->calcOptionHash($row->getData('enable_courier')),
            'selected="selected"'
        );
        $row->setData(
            'option_extra_attr_' . $this->_getEnabledSurchageRenderer()
                ->calcOptionHash($row->getData('enable_surcharge_courier')),
            'selected="selected"'
        );

        $row->setData(
            'option_extra_attr_' . $this->_getUnitRenderer()
                ->calcOptionHash($row->getData('surcharge_courier_unit')),
            'selected="selected"'
        );

    }
}