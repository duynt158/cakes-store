<?php
namespace X247Commerce\Checkout\Plugin\CustomerData;

class DefaultItem
{
    public function afterGetItemData(\Magento\Checkout\CustomerData\DefaultItem $subject, $result, \Magento\Quote\Model\Quote\Item $item)
    {
        if ($item->getProductType() == 'simple') {
            $result['lead_delivery'] = $item->getProduct()->getData('lead_delivery');
        } else {
            $quote = $item->getQuote();
            $childItem = null;
            foreach ($quote->getAllItems() as $quoteItem) {
                if ($quoteItem->getParentItemId() == $item->getId()) {
                    $childItem = $quoteItem;  
                    break;
                }
            }
            $result['lead_delivery'] = $childItem->getProduct()->getData('lead_delivery');
        }
        
        return $result;
    }
}
