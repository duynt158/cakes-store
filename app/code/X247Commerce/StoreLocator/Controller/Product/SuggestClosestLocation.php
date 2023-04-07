<?php

namespace X247Commerce\StoreLocator\Controller\Product;

use Magento\Framework\App\Action\Context;
use X247Commerce\StoreLocatorSource\Model\ResourceModel\LocatorSourceResolver;

class SuggestClosestLocation extends \Magento\Framework\App\Action\Action
{

    protected $storeLocationContext;

    protected $quoteRepository;

    protected $checkoutSession;

    protected $locatorSourceResolver;

    public function __construct(
        \X247Commerce\Checkout\Api\StoreLocationContextInterface $storeLocationContext,
        \Magento\Quote\Api\CartRepositoryInterface $quoteRepository,
        \Magento\Checkout\Model\Session $checkoutSession,
        LocatorSourceResolver $locatorSourceResolver,
        Context $context
    ) {
        parent::__construct($context);
        $this->locatorSourceResolver = $locatorSourceResolver;
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->storeLocationContext = $storeLocationContext;
    }

    public function execute()
    {
        try {
            $quote = $this->checkoutSession->getQuote();
            $productSkus = [];
            if (!empty($quote->getAllVisibleItems())) {
                foreach ($quote->getAllVisibleItems() as $quoteItem) {
                    $productSkus[] = $quoteItem->getSku();
                }
            }
            $closestLocation = $this->locatorSourceResolver->getClosestLocationHasProducts($this->storeLocationContext->getStoreLocationId(), $productSkus);
            if (!empty($closestLocation)) {
                $result = [
                    'status' => 200,
                    'message' => __('Okay!'),
                    'closest_location' => $closestLocation
                ];
            } else {
                $result = [
                    'status' => 404,
                    'message' => __('There are no sources in the cart that match the items in the cart!')
                ];
            }
        } catch (\Exception $e) {
            $result = [
                'status' => 500,
                'message' => __('There are no sources in the cart that match the items in the cart!'),
                'debug_note' => $e->getMessage()
            ];
        }


        return $this->getResponse()->setBody(json_encode($result));
    }
}