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
class Editionguard_Editionguard_Model_Resource_Downloadable_Link extends Mage_Downloadable_Model_Resource_Link
{
    /**
     * Delete data by item(s)
     *
     * @param Mage_Downloadable_Model_Link|array|int $items
     * @return Mage_Downloadable_Model_Resource_Link
     */
    public function deleteItems($items)
    {
        // The original code tries to short circut the normal delete process, which prevents
        // this module from being able to also remove the file from editionguard. To avoid
        // this, read in and delete each item in a loop. This is slightly slower than a raw
        // query, but it lets us hook the behavior better.
        foreach ($items as $item)
        {
            $link = Mage::getModel('downloadable/link')->load($item);
            $link->delete();
        }

        // It's safe to call the parent, just in case it ever does something more.
        return parent::deleteItems($items);
    }
}
