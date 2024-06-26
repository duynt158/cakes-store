<?php

namespace X247Commerce\Sales\Plugin\Block\Dashboard;

use Magento\Backend\Model\Dashboard\Period;
use Magento\Reports\Model\ResourceModel\Order\Collection;
use Magento\Store\Model\Store;

class Totals extends \Magento\Backend\Block\Dashboard\Totals
{
    /**
     * @inheritDoc
     * @return $this|void
     */
    protected function _prepareLayout()
    {
        if (!$this->_moduleManager->isEnabled('Magento_Reports')) {
            return $this;
        }
        $isFilter = $this->getRequest()->getParam(
                'store'
            ) || $this->getRequest()->getParam(
                'website'
            ) || $this->getRequest()->getParam(
                'group'
            );
        $period = $this->getRequest()->getParam('period', Period::PERIOD_24_HOURS);

        /* @var $collection Collection */
        $collection = $this->_collectionFactory->create()->addFilterToMap('created_at', 'main_table.created_at')->addCreateAtPeriodFilter(
            $period
        )->calculateTotals(
            $isFilter
        );

        if ($this->getRequest()->getParam('store')) {
            $collection->addFieldToFilter('store_id', $this->getRequest()->getParam('store'));
        } else {
            if ($this->getRequest()->getParam('website')) {
                $storeIds = $this->_storeManager->getWebsite($this->getRequest()->getParam('website'))->getStoreIds();
                $collection->addFieldToFilter('store_id', ['in' => $storeIds]);
            } else {
                if ($this->getRequest()->getParam('group')) {
                    $storeIds = $this->_storeManager->getGroup($this->getRequest()->getParam('group'))->getStoreIds();
                    $collection->addFieldToFilter('store_id', ['in' => $storeIds]);
                } elseif (!$collection->isLive()) {
                    $collection->addFieldToFilter(
                        'store_id',
                        ['eq' => $this->_storeManager->getStore(Store::ADMIN_CODE)->getId()]
                    );
                }
            }
        }

        $collection->load();

        $totals = $collection->getFirstItem();

        $this->addTotal(__('Revenue'), $totals->getRevenue());
        $this->addTotal(__('Tax'), $totals->getTax());
        $this->addTotal(__('Shipping'), $totals->getShipping());
        $this->addTotal(__('Quantity'), $totals->getQuantity() * 1, true);

        return $this;
    }
}
