<?php

class Zim_Mylibrary_Model_Mylibrary extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mylibrary/mylibrary');
    }
}