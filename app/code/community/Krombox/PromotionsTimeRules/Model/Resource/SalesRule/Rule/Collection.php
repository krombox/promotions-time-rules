<?php

class Krombox_PromotionsTimeRules_Model_Resource_SalesRule_Rule_Collection extends Mage_SalesRule_Model_Resource_Rule_Collection 
{
    public function addWebsiteGroupDateFilter($websiteId, $customerGroupId, $now = null)
    {
        if (!$this->getFlag('website_group_date_filter')) {
            if (is_null($now)) {
                $now = Mage::getModel('core/date')->gmtDate('Y-m-d H:i:s');
            }
            
            $this->addWebsiteFilter($websiteId);
 
            $entityInfo = $this->_getAssociatedEntityInfo('customer_group');
            $connection = $this->getConnection();
            $sql = $this->getSelect()
                ->joinInner(
                    array('customer_group_ids' => $this->getTable($entityInfo['associations_table'])),
                    $connection->quoteInto(
                        'main_table.' . $entityInfo['rule_id_field']
                        . ' = customer_group_ids.' . $entityInfo['rule_id_field']
                        . ' AND customer_group_ids.' . $entityInfo['entity_id_field'] . ' = ?',
                        (int)$customerGroupId
                    ),
                    array()
                )
                ->where('from_date is null or from_date <= ?', $now)
                ->where('to_date is null or to_date >= ?', $now);
 
            $this->addIsActiveFilter();            
            $this->setFlag('website_group_date_filter', true);
        }
 
        return $this;
    }
}