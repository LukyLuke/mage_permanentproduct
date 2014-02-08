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
 * Overriden Checkout-Model to add all Products to if they are not already in
 *
 * @category   Custom
 * @package    Delight_Delightexport
 * @author     delight software gmbh <info@delightsoftware.com>
 */
class Delight_PermanentProduct_Block_Cart extends Mage_Checkout_Block_Cart {

	public function getItems() {
		$items = parent::getItems();
		$storeId = Mage::app()->getStore()->getId();

		$products = Mage::getResourceModel('catalog/product_collection')
			->addAttributeToFilter('always_in_shopping_cart', 1)
			->addAttributeToSelect('*')
			->setStoreId($storeId)
			->addStoreFilter($storeId)
			->setOrder('name', 'asc');

		$idList = array();
		foreach ($items as $item) {
			$idList[] = $item->getProductId();
		}

		foreach ($products as $product) {
			if (!in_array($product->getId(), $idList)) {
				$item = $this->getItemByProduct($product);
				if (!$item) {
					$item = Mage::getModel('sales/quote_item')->setStoreId($storeId);
				}

				// We can't modify existing child items
				if ($item->getId() && $product->getParentProductId()) {
					continue;
				}

				$item->setOptions($product->getCustomOptions())->setProduct($product);
				$item->setCalculationPrice($product->getPrice());
				$item->setData('qty', 0);
				$item->setData('is_qty_decimal', 0);
				$this->getQuote()->addItem($item);
				$this->getQuote()->save();

				$idList[] = $product->getId();
			}
		}

		return parent::getItems();
	}
}