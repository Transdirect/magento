<?php
class Transdirect_Ship_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
     //where is the controller
     $this->_controller = 'adminhtml_test';
     $this->_blockGroup = 'test';
     //text in the admin header
     $this->_headerText = 'Adressbook management';
     //value of the add button
     $this->_addButtonLabel = 'Add a contact';
     parent::__construct();
     }
}