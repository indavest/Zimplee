<?php
class Meteorify_Observerexample_Model_Observer {
 
    public function send_email($observer) {
        //$observer contains data passed from when the event was triggered.
        //You can use this data to manipulate the order data before it's saved.
        //Uncomment the line below to log what is contained here:
        //Mage::log($observer);
        $customer = $observer->getCustomer();
        $fullname = $customer->getName();
        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();
        $customer = $observer->getEvent()->getCustomer();
        /*$visitorData = Mage::getSingleton('core/session')->getVisitorData();
        $log = Mage::getModel('log/customer')->loadByCustomer($customer);
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');
        $value = $read->query("SELECT * FROM log_customer WHERE customer_id=".$customer->getId()." ORDER BY visitor_id DESC LIMIT 1");
        $row = $value->fetch();
        
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $readresult = $write->query("DELETE FROM core_session WHERE session_id IN (SELECT session_id FROM log_visitor WHERE visitor_id IN (SELECT DISTINCT(visitor_id) FROM log_customer WHERE customer_Id=".$customer->getId()." AND visitor_id != ".$row['visitor_id']."))");
        
        //$write->query("DELETE FROM core_session WHERE session_id='sc00fst0j0t0otvio9i1562ij6'");
        Mage::log("New Visitor Id=".$row['visitor_id']."----old VisitorId=".$log->getVisitorId()."-----Session Id=".$visitorData['session_id']);*/
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $readresult = $write->query("DELETE FROM core_session WHERE session_id IN (SELECT session_id FROM log_visitor WHERE visitor_id IN (SELECT DISTINCT(visitor_id) FROM log_customer WHERE customer_id=".$customer->getId().") ORDER BY visitor_id DESC)");
        
        
    }
    
    public function customerLogout($observer)
    {
        /*$customer = $observer->getCustomer();
        $fullname = $customer->getName();
        $firstname = $customer->getFirstname();
        $lastname = $customer->getLastname();
        $email = $customer->getEmail();
        $log = Mage::getModel('log/visitor_online')->getCollection()->addFieldToFilter('customer_id', $customer->getId())->getFirstItem();
        $model = Mage::getModel('log/visitor_online');
        $model->setVisitorId($log->getVisitorId())->delete();
        Mage::log('VisitorId='.$log->getVisitorId());*/
        
    }
    
    public function paymentObserver($observer)
    {
    	Mage::log("Fired observer");
    	$order_id = $observer->getData('order_ids');
    	//$order = $observer->getInvoice()->getOrder();
    	$order = Mage::getModel('sales/order')->load($order_id);
    	Mage::log('OrderId='.$order->getId());
    	
    	//$cart = $this->getOnepage()->getQuote();
    	//$customerId = $cart->getCustomerId();
    	$customerId = $order->getCustomerId();
    	Mage::log("CustomerId=".$order->getCustomerId());
    	$newsInsert = Mage::getModel('mylibrary/mylibrary');
    	foreach ($order->getAllItems() as $item) {
	    	$productId = $item->getProduct()->getId();
	    	Mage::log("ProductId=".$productId);
	    	$newsInsert = Mage::getModel('mylibrary/mylibrary')->getCollection()
	    	->addFieldToFilter('product_id', $productId)
	    	->addFieldToFilter('customer_id', $customerId);
	    	if($newsInsert->getSize() > 0){
		    	foreach($newsInsert as $news){
			    	$model = Mage::getModel('mylibrary/mylibrary')->load($news->getMylibraryId());
			    	$puchasedMonths = $item->getQty();
			    	if(strtotime($news->getExpiryTime()) <= strtotime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())))){
			    	
				    	$purchasedDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
				    	$purchase_date_timestamp = strtotime($purchasedDate);
				    	$expiryDate = strtotime("+$puchasedMonths months", $purchase_date_timestamp);
				    	$expiryDate = date("Y-m-d h:i:s", $expiryDate);
				    	echo $expiryDate;
				    	$model->setPurchasedTime($purchasedDate);
				    	$model->setExpiryTime($expiryDate);
				    	$model->setUpdateTime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
			    	}else{
				    	$purchasedDate = $news->getExpiryTime();
				    	$purchase_date_timestamp = strtotime($purchasedDate);
				    	$expiryDate = strtotime("+$puchasedMonths months", $purchase_date_timestamp);
				    	$expiryDate = date("Y-m-d h:i:s", $expiryDate);
				    	$model->setExpiryTime($expiryDate);
				    	$model->setUpdateTime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
			    	}
			    	$model->save();
		    	}
	    	}else{
		    	$puchasedMonths = $item->getQty();
		    	$purchasedDate = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time()));
		    	$purchase_date_timestamp = strtotime($purchasedDate);
		    	$expiryDate = strtotime("+$puchasedMonths months", $purchase_date_timestamp);
		    	$expiryDate = date("Y-m-d h:i:s", $expiryDate);
		    	$model = Mage::getModel('mylibrary/mylibrary');
		    	$model->setCustomerId($customerId);
		    	$model->setProductId($productId);
		    	$model->setCreatedTime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
		    	$model->setUpdateTime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
		    	$model->setPurchasedTime($purchasedDate);
		    	$model->setExpiryTime($expiryDate);
		    	$model->save();
	    	}
    	}
    }
    
 
}