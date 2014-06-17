<?php

class Zim_Home_Model_Mysql4_Attributefilterflat_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('home/attributefilterflat');
    }
}
