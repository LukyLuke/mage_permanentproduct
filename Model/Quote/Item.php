<?php
/**
 * DelightSerial Customisation by delight software gmbh for Magento
 *
 * DISCLAIMER
 *
 * Do not edit or add code to this file if you wish to upgrade this Module to newer
 * versions in the future.
 *
 * @category   Custom
 * @package    Delight_Delightexport
 * @copyright  Copyright (c) 2001-2011 delight software gmbh (http://www.delightsoftware.com/)
 */

/**
 * Override Quote-Item because we always need a qty, even it is zero
 *
 * @category   Custom
 * @package    Delight_Delightexport
 * @author     delight software gmbh <info@delightsoftware.com>
 */
class Delight_PermanentProduct_Model_Quote_Item extends Mage_Sales_Model_Quote_Item {

	protected function _prepareQty($qty) {
		$qty = Mage::app()->getLocale()->getNumber($qty);
        $qty = ($qty >= 0) ? $qty : 0;
        return $qty;
	}
}