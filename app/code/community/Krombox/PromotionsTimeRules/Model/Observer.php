<?php

class Krombox_PromotionsTimeRules_Model_Observer 
{
    public function adminhtmlPromoQuoteEditTabMainPrepareForm($observer)
    {
        $form = $observer->getData("form");
        $fs = $form->getElement("base_fieldset");
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        foreach($fs->getElements() as $element){
            if($element->getName() == "to_date" || $element->getName() == "from_date"){
                $element->setData("input_format", Varien_Date::DATETIME_INTERNAL_FORMAT);
                $element->setData("format", $dateFormatIso);
                $element->setData("time", true);
            }
        }
        $model = Mage::registry('current_promo_quote_rule');
        $form->setValues($model->prepareAdminFormData());
    }
    
    public function adminhtmlPromoCatalogEditTabMainPrepareForm($observer)
    {
        $form = $observer->getData("form");
        $fs = $form->getElement("base_fieldset");
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        foreach($fs->getElements() as $element){
            if($element->getName() == "to_date" || $element->getName() == "from_date"){
                $element->setData("input_format", Varien_Date::DATETIME_INTERNAL_FORMAT);
                $element->setData("format", $dateFormatIso);
                $element->setData("time", true);
            }
        }
        $model = Mage::registry('current_promo_catalog_rule');
        $form->setValues($model->prepareAdminFormData());
    }        
}