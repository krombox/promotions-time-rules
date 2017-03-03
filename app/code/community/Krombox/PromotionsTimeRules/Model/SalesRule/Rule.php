<?php

class Krombox_PromotionsTimeRules_Model_SalesRule_Rule extends Mage_SalesRule_Model_Rule 
{
    protected function _convertFlatToRecursive(array $data)
    {
        $arr = array();
        foreach ($data as $key => $value) {
            if (($key === 'conditions' || $key === 'actions') && is_array($value)) {
                foreach ($value as $id=>$data) {
                    $path = explode('--', $id);
                    $node =& $arr;
                    for ($i=0, $l=sizeof($path); $i<$l; $i++) {
                        if (!isset($node[$key][$path[$i]])) {
                            $node[$key][$path[$i]] = array();
                        }
                        $node =& $node[$key][$path[$i]];
                    }
                    foreach ($data as $k => $v) {
                        $node[$k] = $v;
                    }
                }
            } else {
                /**
                 * Convert dates into Zend_Date
                 */
                if (in_array($key, array('from_date', 'to_date')) && $value) {
                    var_dump($value);
                    $value = Mage::app()->getLocale()->date(
                        $value,
                        Varien_Date::DATETIME_INTERNAL_FORMAT,
                        null,
                        true
                    );
                    $value->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);                    
                }
                $this->setData($key, $value);
            }
        }
 
        return $arr;
    }
    
    public function prepareAdminFormData()
    {
        $data = $this->getData();
        
        foreach ($data as $key => $value) {            
            if (in_array($key, array('from_date', 'to_date')) && $value) {
                    
                    $value = Mage::app()->getLocale()->date(
                        $value,
                        Varien_Date::DATETIME_INTERNAL_FORMAT,
                        null,
                        false
                    );                    
                    
                    $value->setTimezone(Mage::getStoreConfig('general/locale/timezone'));                    
                }
                $data[$key] = $value;
        }
        
        return $data;
    }
    
    public function prepareInputFormData($data)
    {                
        $store_timezone = new DateTimeZone(Mage::getStoreConfig('general/locale/timezone'));
            $gmt_timezone   = new DateTimeZone('UTC');
            foreach ($data as $key => $value) {                    
                if (in_array($key, array('from_date', 'to_date')) && $value) {
                    $dateString = $value;
                    $usedDateFormat = Mage::app()->getLocale()->getDateTimeFormat(
                        Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
                    );
                    // Instantiate date object in current locale
                    $date = Mage::app()->getLocale()->date();
                    $date->set($dateString, $usedDateFormat);
                    $date->setTimezone(Mage_Core_Model_Locale::DEFAULT_TIMEZONE);                      
                    $data[$key] = $date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);
                }                
            }
        
        return $data;
    }
}