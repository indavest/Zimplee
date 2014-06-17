<?php
/**
 * Extended Mage Log Resource Visitor Model
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Model_Log_Resource_Visitor extends Mage_Log_Model_Resource_Visitor
{

    /**
     * Saving visitor information
     *
     * @param   Mage_Log_Model_Visitor $visitor
     * @return  Mage_Log_Model_Resource_Visitor
     */
    protected function _saveVisitorInfo($visitor)
    {
        /* @var $stringHelper Mage_Core_Helper_String */
        $stringHelper = Mage::helper('core/string');

        $referer    = $stringHelper->cleanString($visitor->getHttpReferer());
        $referer    = $stringHelper->substr($referer, 0, 255);
        $userAgent  = $stringHelper->cleanString($visitor->getHttpUserAgent());
        $userAgent  = $stringHelper->substr($userAgent, 0, 255);
        $charset    = $stringHelper->cleanString($visitor->getHttpAcceptCharset());
        $charset    = $stringHelper->substr($charset, 0, 255);
        $language   = $stringHelper->cleanString($visitor->getHttpAcceptLanguage());
        $language   = $stringHelper->substr($language, 0, 255);
        
        $adapter = $this->_getWriteAdapter();
        $data = new Varien_Object(array(
            'visitor_id'            => $visitor->getId(),
            'http_referer'          => $referer,
            'http_user_agent'       => $userAgent,
            'http_accept_charset'   => $charset,
            'http_accept_language'  => $language,
            'server_addr'           => $visitor->getServerAddr(),
            'remote_addr'           => $visitor->getRemoteAddr(),
            'remote_country'		=> $visitor->getRemoteCountry(), # added
        ));
        $bind = $this->_prepareDataForTable($data, $this->getTable('log/visitor_info'));

        $adapter->insert($this->getTable('log/visitor_info'), $bind);
        return $this;
    }
}