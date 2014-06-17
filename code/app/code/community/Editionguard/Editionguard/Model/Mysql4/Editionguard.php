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

class Editionguard_Editionguard_Model_Mysql4_Editionguard extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('editionguard/editionguard', 'id');
    }
}