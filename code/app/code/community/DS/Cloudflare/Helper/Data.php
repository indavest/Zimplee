<?php 
/**
 * Default Helper
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * Retrieve HTTP CF IP COUNTRY
     *
     * @param boolean $clean clean non UTF-8 characters
     * @return string
     */
    public function getHttpCfIpCountry($clean=true) {
        $value = $this->_getRequest()->getServer('HTTP_CF_IPCOUNTRY', '');
        if ($clean) {
            $value = Mage::helper('core/string')->cleanString($value);
        }

        return $value;
    }

    /**
     * Retrieve HTTP CF CONNECTING IP
     *
     * @param boolean $clean clean non UTF-8 characters
     * @return string
     */
    public function getHttpCfConnectingIp($clean=true) {
        $value = $this->_getRequest()->getServer('HTTP_CF_CONNECTING_IP', '');
        if ($clean) {
            $value = Mage::helper('core/string')->cleanString($value);
        }

        return $value;
    }
}