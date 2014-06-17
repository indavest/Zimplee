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
class Editionguard_Editionguard_Model_Exception extends Mage_Core_Exception
{
    public function __construct($exception, $code = null, $url = null)
    {
        parent::__construct($exception);
        $this->_code = $code;
    }
}