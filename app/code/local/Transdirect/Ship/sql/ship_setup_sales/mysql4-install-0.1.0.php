<?php
$this->startSetup();
$this->addAttribute('order', 'booking_id', array(
    'type'          => 'varchar',
    'label'         => 'Booking Id',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'user_defined'  =>  true
));

$this->endSetup();