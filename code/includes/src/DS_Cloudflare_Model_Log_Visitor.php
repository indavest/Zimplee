<?php
/**
 * Extended Mage Log Visitor Model
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Model_Log_Visitor extends Mage_Log_Model_Visitor
{
    /**
     * Initialize visitor information from server data
     *
     * @return Mage_Log_Model_Visitor
     */
    public function initServerData()
    {
        /* @var $helper Mage_Core_Helper_Http */
        $helper = Mage::helper('core/http');

        $this->addData(array(
            'server_addr'           => $helper->getServerAddr(true),
            'remote_addr'           => $helper->getRemoteAddr(true),
            'remote_country'        => Mage::helper('ds_cloudflare')->getHttpCfIpCountry(), # added
            'http_secure'           => Mage::app()->getStore()->isCurrentlySecure(),
            'http_host'             => $helper->getHttpHost(true),
            'http_user_agent'       => $helper->getHttpUserAgent(true),
            'http_accept_language'  => $helper->getHttpAcceptLanguage(true),
            'http_accept_charset'   => $helper->getHttpAcceptCharset(true),
            'request_uri'           => $helper->getRequestUri(true),
            'session_id'            => $this->_getSession()->getSessionId(),
            'http_referer'          => $helper->getHttpReferer(true),
        ));

        return $this;
    }
}