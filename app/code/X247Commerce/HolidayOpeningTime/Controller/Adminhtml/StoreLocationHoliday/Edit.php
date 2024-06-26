<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace X247Commerce\HolidayOpeningTime\Controller\Adminhtml\StoreLocationHoliday;

class Edit extends \X247Commerce\HolidayOpeningTime\Controller\Adminhtml\StoreLocationHoliday
{

    protected $resultPageFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context, $coreRegistry);
    }

    /**
     * Edit action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('id');
        $model = $this->_objectManager->create(\X247Commerce\HolidayOpeningTime\Model\StoreLocationHoliday::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addErrorMessage(__('This Store location holiday no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        $this->_coreRegistry->register('store_location_holiday', $model);

        // 3. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $this->initPage($resultPage)->addBreadcrumb(
            $id ? __('Edit Store location holiday') : __('New Store location holiday'),
            $id ? __('Edit Store location holiday') : __('New Store location holiday')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Store location holiday'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? __('Edit Store location holiday %1 of Store location %2', $model->getTitle(), $model->getStoreLocationId()) : __('New Store location holiday'));

        return $resultPage;
    }
}

