<?php

class Zim_Mylibrary_Model_Mysql4_Mylibrary_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        //parent::__construct();
        $this->_init('mylibrary/mylibrary');
    }
} 