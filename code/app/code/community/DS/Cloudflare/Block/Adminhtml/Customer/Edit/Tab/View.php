<?php
/**
 * Extended Adminhtml Customer Tab View Block
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Block_Adminhtml_Customer_Edit_Tab_View extends Mage_Adminhtml_Block_Customer_Edit_Tab_View
{

    /**
     * Get last known ip address
     * 
     * @return string
     */
    public function getLastKnownIP() {
        if (!is_null($ip = $this->getCustomerLog()->getRemoteAddr())) {
            return long2ip($ip);
        }
        
        return Mage::helper('ds_cloudflare')->__('Unknown');
    }
    
    /**
     * Get last known ip country
     * 
     * @return string
     */
    public function getLastKnownCountry() {
        if (strlen($country = $this->getCustomerLog()->getRemoteCountry())) {

            # Country code is enough, so suppress exception when not supported
            try {
                $countryModel = Mage::getModel('directory/country')->loadByCode($country);
                /* @var $countryModel Mage_Directory_Model_Country */
    
                if (!$countryModel->isEmpty()) {
                    $country = $countryModel->getName();
                }
            }
            catch (Mage_Core_Exception $e) {}
        }
        
        return $country;
    }
}