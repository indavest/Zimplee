<?php

class Zim_Mylibrary_Model_Mysql4_Mylibrary extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('mylibrary/mylibrary', 'mylibrary_id');
    }
} 