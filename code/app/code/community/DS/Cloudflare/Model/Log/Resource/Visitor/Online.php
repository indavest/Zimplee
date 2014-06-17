<?php
/**
 * Extended Mage Log Resource Visitor Online Model
 * 
 * @author     Design:Slider GbR <magento@design-slider.de>
 * @copyright  (C)Design:Slider GbR <www.design-slider.de>
 * @license    OSL <http://opensource.org/licenses/osl-3.0.php>
 * @link       http://www.design-slider.de/magento-onlineshop/magento-extensions/cloudflare/
 * @package    DS_Cloudflare
 */
class DS_Cloudflare_Model_Log_Resource_Visitor_Online extends Mage_Log_Model_Resource_Visitor_Online
{

    /**
     * Prepare online visitors for collection
     *
     * @param Mage_Log_Model_Visitor_Online $object
     * @return Mage_Log_Model_Resource_Visitor_Online
     */
    public function prepare(Mage_Log_Model_Visitor_Online $object)
    {
        if (($object->getUpdateFrequency() + $object->getPrepareAt()) > time()) {
            return $this;
        }

        $readAdapter    = $this->_getReadAdapter();
        $writeAdapter   = $this->_getWriteAdapter();

        $writeAdapter->beginTransaction();

        try{
            $writeAdapter->delete($this->getMainTable());

            $visitors = array();
            $lastUrls = array();

            // retrieve online visitors general data

            $lastDate = Mage::getModel('core/date')->gmtTimestamp() - $object->getOnlineInterval() * 60;

            $select = $readAdapter->select()
                ->from(
                    $this->getTable('log/visitor'),
                    array('visitor_id', 'first_visit_at', 'last_visit_at', 'last_url_id'))
                ->where('last_visit_at >= ?', $readAdapter->formatDate($lastDate));

            $query = $readAdapter->query($select);
            while ($row = $query->fetch()) {
                $visitors[$row['visitor_id']] = $row;
                $lastUrls[$row['last_url_id']] = $row['visitor_id'];
                $visitors[$row['visitor_id']]['visitor_type'] = Mage_Log_Model_Visitor::VISITOR_TYPE_VISITOR;
                $visitors[$row['visitor_id']]['customer_id']  = null;
            }

            if (!$visitors) {
                $this->commit();
                return $this;
            }

            // retrieve visitor remote addr
            $select = $readAdapter->select()
                ->from(
                    $this->getTable('log/visitor_info'),
                    array('visitor_id', 'remote_addr', 'remote_country')) # added remote_country
                ->where('visitor_id IN(?)', array_keys($visitors));

            $query = $readAdapter->query($select);
            while ($row = $query->fetch()) {
                $visitors[$row['visitor_id']]['remote_addr'] = $row['remote_addr'];
                $visitors[$row['visitor_id']]['remote_country'] = $row['remote_country']; # added
            }

            // retrieve visitor last URLs
            $select = $readAdapter->select()
                ->from(
                    $this->getTable('log/url_info_table'),
                    array('url_id', 'url'))
                ->where('url_id IN(?)', array_keys($lastUrls));

            $query = $readAdapter->query($select);
            while ($row = $query->fetch()) {
                $visitorId = $lastUrls[$row['url_id']];
                $visitors[$visitorId]['last_url'] = $row['url'];
            }

            // retrieve customers
            $select = $readAdapter->select()
                ->from(
                    $this->getTable('log/customer'),
                    array('visitor_id', 'customer_id'))
                ->where('visitor_id IN(?)', array_keys($visitors));

            $query = $readAdapter->query($select);
            while ($row = $query->fetch()) {
                $visitors[$row['visitor_id']]['visitor_type'] = Mage_Log_Model_Visitor::VISITOR_TYPE_CUSTOMER;
                $visitors[$row['visitor_id']]['customer_id']  = $row['customer_id'];
            }

            foreach ($visitors as $visitorData) {
                unset($visitorData['last_url_id']);

                $writeAdapter->insertForce($this->getMainTable(), $visitorData);
            }

            $writeAdapter->commit();
        } catch (Exception $e) {
            $writeAdapter->rollBack();
            throw $e;
        }

        $object->setPrepareAt();

        return $this;
    }
}