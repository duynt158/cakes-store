<?php
/**
 * Copyright © HTCSoft, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace X247Commerce\Theme\Block\Html;

use Magento\Framework\View\Element\Template;

class BestSellerHome extends Template
{
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Pricing\Helper\Data $priceHelp,
        array $data = []
    )
    {    
        $this->imageHelper = $imageHelper;
        $this->_productCollectionFactory = $productCollectionFactory;  
        $this->priceHelp = $priceHelp;  
        parent::__construct($context, $data);
    }

    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->setPageSize(8);

        return $collection;
    }

    public function getImageUrl($product)
    {
        return $this->imageHelper->init($product, 'product_page_image_large')->getUrl();
    }

    public function priceFormat($price)
    {
        return $this->priceHelp->currency($price, true, false);
    }
}