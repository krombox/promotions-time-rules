<?php

trait Promo 
{
    /**
     * Convert dates with time in array from localized to internal format
     *
     * @param   array $array
     * @param   array $dateFields
     * @return  array
     */
    protected function _filterDateTime($array, $dateFields)
    {
        if (empty($dateFields)) {
            return $array;
        }
        
        $filterInput = new Zend_Filter_LocalizedToNormalized(array(
            'date_format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            'locale' => Mage::app()->getLocale()->getLocaleCode()/*This line fix AM/PM issue when locale time is 24H e.g. ua_Uk*/
        ));
        $filterInternal = new Zend_Filter_NormalizedToLocalized(array(
            'date_format' => Varien_Date::DATETIME_INTERNAL_FORMAT,
            //'locale' => Mage::app()->getLocale()->getLocaleCode()/*This line fix AM/PM issue when locale time is 24H e.g. ua_Uk*/
        ));

        foreach ($dateFields as $dateField) {
            if (array_key_exists($dateField, $array) && !empty($dateField)) {
                $array[$dateField] = $filterInput->filter($array[$dateField]);
                $array[$dateField] = $filterInternal->filter($array[$dateField]);
            }
        }
        return $array;
    }
    
}
