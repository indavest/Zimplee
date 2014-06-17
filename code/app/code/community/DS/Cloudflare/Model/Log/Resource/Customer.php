<?php
/**
 * Extended Mage Log Resource Customer Model
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Model_Log_Resource_Customer extends Mage_Log_Model_Resource_Customer
{

    /**
     * Retrieve select object for load object data
     * 
     * @param string $field
     * @param mixed $value
     * @param Mage_Log_Model_Customer $object
     * @return Varien_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($field == 'customer_id') {
            // load remote_country
            $select
                ->joinInner(
                    array('lvit2' => $this->_visitorInfoTable),
                    'lvt.visitor_id = lvit2.visitor_id',
                    array('remote_country'));
        }
        return $select;
    }
}