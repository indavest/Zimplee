<?php
class ZIM_Qpaper_Block_Qpaper extends Mage_Core_Block_Template
{
 	public function _prepareLayout()
    {
       $this->getLayout()->getBlock('breadcrumbs')
            ->addCrumb('home',
                array('label'=>Mage::helper('catalogsearch')->__('Home'),
                    'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
                    'link'=>Mage::getBaseUrl())
                )
            ->addCrumb('customer',
                array('label'=>Mage::helper('customer')->__('Topper answer papers'))
                );
      return parent::_prepareLayout();
    } 
}

?>