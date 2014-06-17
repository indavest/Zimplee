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
class Editionguard_Editionguard_Helper_Data extends Mage_Core_Helper_Data
{
    const API_PACKAGE_URL                       = 'http://www.editionguard.com/api/package';
    const API_SET_STATUS_URL                    = 'http://www.editionguard.com/api/set_status';
    const API_DELETE_URL                        = 'http://www.editionguard.com/api/delete';
    const API_LINK_URL                          = 'http://acs4.editionguard.com/fulfillment/URLLink.acsm';
    const API_EBOOK_LISTING                     = 'http://www.editionguard.com/api/ebook_list'; //API for getting uploaded files
    
    const LINK_EDITIONGUARD_YES                 = 1;
    const LINK_EDITIONGUARD_NO                  = 0;
    const LINK_EDITIONGUARD_CONFIG              = 2;
    const XML_PATH_CONFIG_USE_EDITIONGUARD      = 'catalog/downloadable/editionguard';
    const XML_PATH_CONFIG_EDITIONGUARD_EMAIL    = 'editionguard/settings/email';
    const XML_PATH_CONFIG_EDITIONGUARD_DISTRIBUTERID = 'editionguard/settings/distributerid';
    const XML_PATH_CONFIG_EDITIONGUARD_SECRET   = 'editionguard/settings/sharedsecret';

    /**
     * Given a URL and parameters, makes a request to the editionguard API using the
     * currently configured credentials.
     * 
     * @param string $request_url
     * @param array $params
     * 
     * @return Varien_Simplexml_Element object containing the result
     * @throws Exception on failure
     */
    protected function _sendEditionguardApiRequest($request_url, array $params, $file = null)
    {
        $secret = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_SECRET);
        $email = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_EMAIL);
        $nonce = rand(1000000, 999999999);
        $hash = hash_hmac("sha1", $nonce.$email, base64_decode($secret));
    
        $httpClient = new Varien_Http_Client();
        $params['email'] = $email;
        $params['nonce'] = $nonce;
        $params['hash'] = $hash;
    
        $httpClient->setUri($request_url)
            ->setParameterPost($params)
            ->setConfig(array('timeout' => 3600));
        
        if ($file && is_array($file))
        {
            $httpClient->setFileUpload(
                $file['filename'],
                $file['formname'],
                $file['data'],
                $file['type']
            );
        }

        $response = $httpClient->request('POST');
        
        // TODO: Handle non 200 responses
        // - Follow 3**
        // - Give errors on 4** and 5**
    
        try {
            $body = $response->getBody();
        } catch (Zend_Http_Exception $e) {
            // HACK: EditionGuard currently sends the response raw, even though
            // the header indicates that it is chunked. Just grab the raw body
            // and use it.
            $body = $response->getRawBody();
        }
    
    //echo 'xml->';echo '<pre>'; print_r($body); die();
        try {
            $xml = new Varien_Simplexml_Element("<root>".$body."</root>");
        } catch (Exception $e) {
            // Not valid XML. Treat like a raw error
            throw new Editionguard_Editionguard_Model_Exception("Error: \"$body\" while uploading file to EditionGuard");
        }
    
        if (isset($xml->error))
        {
            $error_data = $xml->error->getAttribute('data');
            if (preg_match('~([a-zA-Z0-9_]*) (.*)~', $error_data, $matches))
            {
                $error_type = $matches[1];
                $error_url = $matches[2];
                // TODO: Map any documented error codes to more meaningful messages
                // TODO: Use a custom exception type
                throw new Editionguard_Editionguard_Model_Exception("Error: $error_type while uploading file to EditionGuard", $error_type, $error_url);
                Mage::log("EditionGuard Error with returned XML: ".$xml);
            }
            else
            {
                // TODO: Use a custom exception type
                throw new Editionguard_Editionguard_Model_Exception("Unknown error while uploading file to EditionGuard");
                Mage::log("EditionGuard Unknown error with XML: ".$xml);
            }
        }

        return $xml;
    }
    
    /**
     * Given a URL and parameters, makes a request to the editionguard API using the
     * currently configured credentials.
     * 
     * @param string $request_url
     * @param array $params
     * 
     * @return Varien_Simplexml_Element jSon object containing the result
     * @throws Exception on failure
     */
    protected function _sendEditionguardApiRequestJson($request_url, array $params, $file = null)
    {
        $secret = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_SECRET);
        $email = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_EMAIL);
        $nonce = rand(1000000, 999999999);
        $hash = hash_hmac("sha1", $nonce.$email, base64_decode($secret));
    
        $httpClient = new Varien_Http_Client();
        $params['email'] = $email;
        $params['nonce'] = $nonce;
        $params['hash'] = $hash;
    
        $httpClient->setUri($request_url)
            ->setParameterPost($params)
            ->setConfig(array('timeout' => 3600));
        
        if ($file && is_array($file))
        {
            $httpClient->setFileUpload(
                $file['filename'],
                $file['formname'],
                $file['data'],
                $file['type']
            );
        }

        $response = $httpClient->request('POST');
        
        // TODO: Handle non 200 responses
        // - Follow 3**
        // - Give errors on 4** and 5**
    
        try {
            $body = $response->getBody();
        } catch (Zend_Http_Exception $e) {
            // HACK: EditionGuard currently sends the response raw, even though
            // the header indicates that it is chunked. Just grab the raw body
            // and use it.
            $body = $response->getRawBody();
        }
    

        $bodyDecoded = json_decode($body);
        return $bodyDecoded;
    }
    
    
    /**
     * Collects an Editionguard resources already uploaded files
     */
    public function listResource($resourceid)
    {
        $decoded_reponse = $this->_sendEditionguardApiRequestJson(Editionguard_Editionguard_Helper_Data::API_EBOOK_LISTING, array(
            'resource_id'=>$resourceid,
        ));
        
        return $decoded_reponse;
    }
    

    /**
     * Sends a file to Editionguard for DRM handling
     * 
     * Use a null resourceid to upload a new file
     * 
     * @return string - Unique ID for the file in Editionguard 
     */
    public function sendToEditionguard($resourceid, $title, $filename, $filedata, $filetype = null)
    {
        $params = array(
            'title'=>$title,
            'author'=>'',
            'publisher'=>'',
        );
        if ($resourceid)
        {
            $params['resource_id'] = $resourceid;
        }
        $xml = $this->_sendEditionguardApiRequest(Editionguard_Editionguard_Helper_Data::API_PACKAGE_URL,
            $params,
            array(
                'filename'=>$filename,
                'formname'=>'file',
                'data'=>$filedata,
                'type'=>$filetype,
            )
        );

        if (!isset($xml->resourceItemInfo) && !isset($xml->response))
        {
            // Unknown response type. Assume it's a raw error.
            Mage::log("EditionGuard Error with returned XML: ".print_r($xml, true));
            throw new Exception("Error: \"".$xml->error->getAttribute('data')."\" while uploading file to EditionGuard");
        }

        return array(
            'resource'=>$xml->resourceItemInfo->resource,
            'src'=>$xml->resourceItemInfo->src,
        );
    }
    
    /**
     * Changes the status of an Editionguard resource
     */
    public function setResourceActive($resourceid, $active = true)
    {
        $xml = $this->_sendEditionguardApiRequest(Editionguard_Editionguard_Helper_Data::API_SET_STATUS_URL, array(
            'resource_id'=>$resourceid,
            'status'=>$active ? 'active' : 'inactive',
        ));

        // Setting to inactive is currently returning deleted. Setting to active should
        // return a distributionrights object
        if (!isset($xml->response) || (!isset($xml->response->distributionRights) && !isset($xml->response->deleted)))
        {
            // Unknown response type. Assume it's a raw error.
            throw new Exception("Error: \"{$xml->innerXml()}\" while uploading file to EditionGuard");
        }

        return true;
    }
    
    /**
     * Deletes an Editionguard resource
     */
    public function deleteResource($resourceid)
    {
        $xml = $this->_sendEditionguardApiRequest(Editionguard_Editionguard_Helper_Data::API_DELETE_URL, array(
            'resource_id'=>$resourceid,
        ));

        // NOTE: We get a blank response if the file had already been deleted. There's no sense
        // dying over this, so we're going to skip this verification process, even though it would
        // normally be a good thing.
//        if (!isset($xml->response) || !isset($xml->response->deleted) || !$xml->response->deleted)
//        {
//            // Unknown response type. Assume it's a raw error.
//            throw new Exception("Error: \"{$xml->innerXml()}\" while uploading file to EditionGuard");
//        }

        return true;
    }
    
    /**
     * Builds a link to a file managed by Editionguard
     * 
     * @return string - Unique download URL for this purchase
     */
    public function getDownloadUrl($order_item_id, $resource)
    {
        $dateval=time(); 
        $sharedSecret = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_SECRET);
        $order_item = Mage::getModel('sales/order_item')->load($order_item_id);
        $order = Mage::getModel("sales/order")->load($order_item->order_id);
        $transactionId = $order->getIncrementId()."-".$order_item_id;
        $resourceId = $resource;
        $linkURL = Editionguard_Editionguard_Helper_Data::API_LINK_URL;
        $orderSource = Mage::getStoreConfig(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_EDITIONGUARD_EMAIL);
        
        // Create download URL
        $URL = "action=enterorder&ordersource=".urlencode($orderSource)."&orderid=".urlencode($transactionId)."&resid=".urlencode("$resourceId")."&dateval=".urlencode($dateval)."&gblver=4";

        // Digitaly sign the request
        $URL = $linkURL."?".$URL."&auth=".hash_hmac("sha1", $URL, base64_decode($sharedSecret));

        return $URL;
    }
    

    /**
     * Check is link DRM protected by EditionGuard or not
     *
     * @param Mage_Downloadable_Model_Link | Mage_Downloadable_Model_Link_Purchased_Item $link
     * @return bool
     */
    public function getUseEditionguard($link)
    {
        $editionguard = false;
        switch ($link->getUseEditionguard()) {
            case Editionguard_Editionguard_Helper_Data::LINK_EDITIONGUARD_YES:
            case Editionguard_Editionguard_Helper_Data::LINK_EDITIONGUARD_NO:
                $editionguard = (bool) $link->getUseEditionguard();
                break;
            case Editionguard_Editionguard_Helper_Data::LINK_EDITIONGUARD_CONFIG:
                $editionguard = (bool) Mage::getStoreConfigFlag(Editionguard_Editionguard_Helper_Data::XML_PATH_CONFIG_USE_EDITIONGUARD);
        }
        return $editionguard;
    }
}
