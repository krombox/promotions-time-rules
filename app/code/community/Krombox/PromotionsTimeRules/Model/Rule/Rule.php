<?php

class Krombox_PromotionsTimeRules_Model_Rule_Rule extends Mage_Rule_Model_Rule 
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
                    var_dump($value);die();
                    $value = Mage::app()->getLocale()->date(
                        $value,
                        Varien_Date::DATETIME_INTERNAL_FORMAT,
                        null,
                        false
                    );
                }
                $this->setData($key, $value);
            }
        }
 
        return $arr;
    }
}