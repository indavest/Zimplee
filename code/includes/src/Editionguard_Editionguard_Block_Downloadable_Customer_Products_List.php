<?php
/**
 * EditionGuard
 *
 * This source file is proprietary property of EditionGuard. Any reuse or
 * distribution of any part of this file without prior consent is prohibited.
 *
 * @category    EditionGuard
 * @package     Editionguard_Editionguard
 * @copyright   Copyright (c) 2012 EditionGuard. All rights Reserved.
 */
class Editionguard_Editionguard_Block_Downloadable_Customer_Products_List extends Mage_Downloadable_Block_Customer_Products_List
{
    /**
     * Return number of left downloads or unlimited
     *
     * @return string
     */
    public function getRemainingDownloads($item)
    {
        if (Mage::helper('editionguard')->getUseEditionguard($item))
        {
            // EditionGuard downloads are unlimited
            return Mage::helper('downloadable')->__('Unlimited');
        }
        else
        {
            return parent::getRemainingDownloads($item);
        }
    }

    /**
     * Return url to download link
     *
     * @param Mage_Downloadable_Model_Link_Purchased_Item $item
     * @return string
     */
    public function getDownloadUrl($item)
    {
        if (Mage::helper('editionguard')->getUseEditionguard($item))
        {
            Mage::log(print_r($item, true));
            return Mage::helper('editionguard')->getDownloadUrl($item->getOrderItemId(), $item->getEditionguardResource());
        }
        else
        {
            return parent::getDownloadUrl($item);
        }
    }
}
