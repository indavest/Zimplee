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
class Editionguard_Editionguard_Model_Downloadable_Link extends Mage_Downloadable_Model_Link
{
    protected $_addingEditionguard = false;
    protected $_removingEditionguard = false;
    protected $_changingLinkFile = false;
    protected $_loading = false;

    /**
     * Called prior to saving. Used to upload the file to EditionGuard for DRM
     *
     * @return Mage_Downloadable_Model_Link
     */
    protected function _beforeSave()
    {               
        $new = !$this->getLinkId();
        $add_editionguard = $this->_addingEditionguard;
        $remove_editionguard = $this->_removingEditionguard;
        $new_file = $this->_changingLinkFile;
        $type = $this->getLinkType();
        $this->_ensureOrigData();
        
        if (($new || $add_editionguard || $new_file) && $type != 'ebook')
        {
      
            // The file is new to editionguard.
            if (Mage::helper('editionguard')->getUseEditionguard($this))
            {
                // If enabling editionguard, we need to do this before updating the
                // file. Otherwise the system will give errors.
//                if ($this->getEditionguardResource() && $add_editionguard)
//                {
//                    // had a resource, now re-enabling editionguard handling.
//                    try {
//                        $editionguard = Mage::helper('editionguard')->setResourceActive(
//                            $this->getEditionguardResource(),
//                            true
//                        );
//                    } catch (Editionguard_Editionguard_Model_Exception $e) {
//                        // Skip an error code that's getting returned right now in a non-error case
//                        if ($e->getCode() != 'E_ADEPT_INTERNAL')
//                        {
//                            // Got an error from EditionGuard. Can't save. Pass it on.
//                            throw $e;
//                        }
//                    }
//                }

                // Add or update the file
                if ($new || $new_file || !$this->getEditionguardResource())
                {
                    // We don't have it already
                    try {
                        // Upload the file to EditionGuard.
                        $filepath = Mage_Downloadable_Model_Link::getBasePath() . $this->getLinkFile();
                        $filedata = file_get_contents($filepath);
                        // Get the product object for its name
                        $product = Mage::getModel('catalog/product')->load($this->_data['product_id']);
                        
                        // If we're uploading a file after link was set as remote eBook, don't send resource id
                        if($this->getOrigData("link_type") != "ebook") {
                            $editionguard = Mage::helper('editionguard')->sendToEditionguard($this->getEditionguardResource(),
                            $product->_data['name'],
                            basename($this->getLinkFile()),
                            $filedata);
                        }
                        else {
                            $editionguard = Mage::helper('editionguard')->sendToEditionguard(false,
                            $product->_data['name'],
                            basename($this->getLinkFile()),
                            $filedata);
                        }
                        
                        // Remember the response information
                        if($new || $this->getOrigData("link_type") == "ebook") {
                            $this->setEditionguardResource($editionguard['resource']);
                            $this->setEditionguardSrc($editionguard['src']);
                        }
                        
                        // Always set link_type to ebook when using EditionGuard
                        $this->setLinkType("ebook");

                    } catch (Editionguard_Editionguard_Model_Exception $e) {
                        // Got an error from EditionGuard. Can't save. Pass it on.
                        throw $e;
                    }
                }
            }
        }

//        if ($this->getEditionguardResource() && $remove_editionguard)
//        {   
//            // had a resource, now removing editionguard handling. Disable editionguard on the file.
//            try {
//                $editionguard = Mage::helper('editionguard')->setResourceActive(
//                    $this->getEditionguardResource(),
//                    false
//                );
//            } catch (Editionguard_Editionguard_Model_Exception $e) {
//                // Skip an error code that's getting returned right now in a non-error case
//                if ($e->getCode() != 'E_ADEPT_INTERNAL')
//                {
//                    // Got an error from EditionGuard. Can't save. Pass it on.
//                    throw $e;
//                }
//            }
//        }
        return parent::_beforeSave();
    }
    
    /**
     * Called prior to saving. Used to upload the file to EditionGuard for DRM
     *
     * @return Mage_Downloadable_Model_Link
     */
//    protected function _beforeDelete()
//    {
//        $this->_ensureOrigData();
//
//        if ($this->getEditionguardResource())
//        {
//            // Had a resource. Delete it.
//            try {
//                $editionguard = Mage::helper('editionguard')->deleteResource(
//                    $this->getEditionguardResource()
//                );
//            } catch (Editionguard_Editionguard_Model_Exception $e) {
//                // Got an error from EditionGuard. Can't save. Pass it on.
//                throw $e;
//            }
//        }
//        return parent::_beforeDelete();
//    }
    
    protected function _beforeLoad($id, $field = null)
    {
        $this->_loading = true;  
        return parent::_beforeLoad($id, $field);
    }
    
    protected function _afterLoad()
    {
        $this->_loading = false;
        return parent::_afterLoad();
    }
    
    public function setData($key, $value=null)
    {    
       if (!$this->_loading && is_array($key))
        {          
            if (isset($key['use_editionguard']) && $key['type']!='ebook')
            {
                $use_editionguard = $key['use_editionguard'];
                unset($key['use_editionguard']);
            }
            if (isset($key['link_file']))
            {
                $link_file = $key['link_file'];
                unset($key['link_file']);
            }
            parent::setData($key, $value);
            if (isset($use_editionguard))
            {
                $this->setUseEditionguard($use_editionguard);
                //  
               // $this->listResource();
                
            }
            if (isset($link_file))
            {
                $this->setLinkFile($link_file);
            } 
            return $this;
        }

        return parent::setData($key, $value);
        
    }
    //
    public function setListingEditionguard($v)
    {   
        $this->_ensureOrigData();

        if ($v && $v != $this->getOrigData('use_editionguard'))
        {
            $this->_addingEditionguard = true;
            $this->_removingEditionguard = false;
        }
        else if (!$v && $v != $this->getOrigData('use_editionguard'))
        {
            $this->_addingEditionguard = false;
            $this->_removingEditionguard = true;
        }
        return $this->setData('use_editionguard', $v);
    }
    
    public function setUseEditionguard($v)
    {          
        $this->_ensureOrigData();
        
        if ($v && $v != $this->getOrigData('use_editionguard'))
        {
            $this->_addingEditionguard = true;
            $this->_removingEditionguard = false;
        }
        else if (!$v && $v != $this->getOrigData('use_editionguard'))
        {
            $this->_addingEditionguard = false;
            $this->_removingEditionguard = true;
        }  
       
        return $this->setData('use_editionguard', $v);
        
    }

    public function setLinkFile($v)
    {        
        $this->_ensureOrigData();

        if ($v && $v != $this->getOrigData('link_file'))
        {
            // Magento may re-upload, resulting in a file name change in some cases even though
            // the file contents are not actually changing. Skip over any changes to only
            // the path.
            $this->_changingLinkFile = true;
        }
        return $this->setData('link_file', $v);
    }

    protected function _ensureOrigData()
    {    
        // Magento doesn't always load the object. We need to delay load it to get the old values.
        if ($this->getLinkId() && !$this->getOrigData())
        {
            // we could have original data, but don't. Load it now.
            $old_link = Mage::getModel('downloadable/link')->load($this->getLinkId());
            $this->_origData = $old_link->getOrigData();
            $this->set = $old_link->getOrigData();

            // Make sure that we have all the data we'll need to do our job
            if (!$this->hasEditionguardResource())
            {
                $this->setEditionguardResource($this->getOrigData('editionguard_resource'));
            }
            if (!$this->hasEditionguardSrc())
            {
                $this->setEditionguardSrc($this->getOrigData('editionguard_src'));
            }
            
        }
    }
}
