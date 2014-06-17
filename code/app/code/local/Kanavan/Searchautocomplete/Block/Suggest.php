<?php
class Kanavan_Searchautocomplete_Block_Suggest extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getSearchautocomplete()     
     { 
        if (!$this->hasData('searchautocomplete')) {
            $this->setData('searchautocomplete', Mage::registry('searchautocomplete'));
        }
        return $this->getData('searchautocomplete');
     }

     public function getSuggestProducts()     
     {


        $query = Mage::helper('catalogsearch')->getQuery();
        $query->setStoreId(Mage::app()->getStore()->getId());

                if ($query->getRedirect()){
                    $query->save();
                }
                else {
                    $query->prepare();
                }
            Mage::helper('catalogsearch')->checkNotes();


            $results=$query->getResultCollection();//->setPageSize(5);



        $results=Mage::getResourceModel('catalogsearch/search_collection')->addSearchFilter(Mage::app()->getRequest()->getParam('q'));

        $results->addAttributeToFilter('visibility', array('neq' => 1));


        if(Mage::getStoreConfig('searchautocomplete/preview/number_product'))
        {
            $results->setPageSize(Mage::getStoreConfig('searchautocomplete/preview/number_product'));
        }
        else
        {
            $results->setPageSize(5);
        }
        $results->addAttributeToSelect('description');
        $results->addAttributeToSelect('name');
        $results->addAttributeToSelect('thumbnail');
        $results->addAttributeToSelect('small_image');
        $results->addAttributeToSelect('url_key');


        return $results;
    }
    
    public function getSuggestProuctsModified()
    {
        $category = Mage::app()->getRequest()->getParam('category');
        if($category != ''){
            $results = Mage::getModel('catalog/category')->load($category)
                    ->getProductCollection();   
        }else{
            $results = Mage::getModel('catalog/product')->getCollection();    
        }
                    
        $words = Mage::helper('core/string')->splitWords(Mage::app()->getRequest()->getParam('q'), true, 0, ' ');
        
        $filterArray = array();
        foreach($words as $word)
        {
            array_push($filterArray, array('like' => "%$word%"));
        }
        if($category == 6){
        	$results->addAttributeToFilter(
        			array(
        					array('attribute'=> 'description','like' => $filterArray),
        					array('attribute'=> 'isbn_one','like' => $filterArray),
        					array('attribute'=> 'author_book','like' => $filterArray),
        			)
        	)
        	->addAttributeToFilter('visibility' , array('neq' => 1))
        	->addAttributeToSelect('name')
        	->addAttributeToSelect('description')
        	->addAttributeToSelect('thumbnail')
        	->addAttributeToSelect('url_key');
        }else{
        	$results->addAttributeToFilter('description', $filterArray)
        			->addAttributeToFilter('visibility' , array('neq' => 1))
        			->addAttributeToSelect('name')
        			->addAttributeToSelect('description')
        			->addAttributeToSelect('thumbnail')
        			->addAttributeToSelect('url_key');
        }
        
        
        if(Mage::getStoreConfig('searchautocomplete/preview/number_product'))
        {
            $results->setPageSize(Mage::getStoreConfig('searchautocomplete/preview/number_product'));
        }
        else
        {
            $results->setPageSize(5);
        }
        
        return $results;
    }
    
    public function getSuggestCategories()
    {
        $searchTerm = Mage::app()->getRequest()->getParam('q');
        $categories = Mage::getModel('catalog/category')->getCollection()
                            ->addAttributeToSelect('id')
                            ->addAttributeToSelect('name')
                            ->addAttributeToFilter('name',$searchTerm)
                            ->addAttributeToSelect('url_key');
        return $categories;
    }
    
     public function enabledSuggest()     
     {
        return Mage::getStoreConfig('searchautocomplete/suggest/enable');
     }

     public function enabledPreview()     
     {
        return Mage::getStoreConfig('searchautocomplete/preview/enable');
     }

     public function getImageWidth()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/image_width');
     }

     public function getImageHeight()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/image_height');
     }
     public function getEffect()
     {
        return Mage::getStoreConfig('searchautocomplete/settings/effect');
     }

     public function getPreviewBackground()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/background');
     }

     public function getSuggestBackground()
     {
        return Mage::getStoreConfig('searchautocomplete/suggest/background');
     }

     public function getSuggestColor()
     {
        return Mage::getStoreConfig('searchautocomplete/suggest/suggest_color');
     }

     public function getSuggestCountColor()
     {
        return Mage::getStoreConfig('searchautocomplete/suggest/count_color');
     }

     public function getBorderColor()
     {
        return Mage::getStoreConfig('searchautocomplete/settings/border_color');
     }

     public function getBorderWidth()
     {
        return Mage::getStoreConfig('searchautocomplete/settings/border_width');
     }

     public function isShowImage()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/show_image');
     }

     public function isShowName()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/show_name');
     }
     public function getProductNameColor()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/pro_title_color');
     }

     public function getProductDescriptionColor()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/pro_description_color');
     }


     public function isShowDescription()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/show_description');
     }

     public function getNumDescriptionChar()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/num_char_description');
     }


     public function getImageBorderWidth()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/image_border_width');
     }
     public function getImageBorderColor()
     {
        return Mage::getStoreConfig('searchautocomplete/preview/image_border_color');
     }

     public function getHoverBackground()
     {
        return Mage::getStoreConfig('searchautocomplete/settings/hover_background');
     }

}
