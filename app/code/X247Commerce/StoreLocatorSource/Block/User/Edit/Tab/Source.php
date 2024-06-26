<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace X247Commerce\StoreLocatorSource\Block\User\Edit\Tab;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Locale\OptionInterface;

/**
 * Cms page edit form main tab
 *
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Source extends \Magento\Backend\Block\Widget\Form\Generic
{
    const CURRENT_USER_PASSWORD_FIELD = 'current_password';

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_authSession;

    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    protected $_LocaleLists;

    /**
     * Operates with deployed locales.
     *
     * @var OptionInterface
     */
    private $deployedLocales;

    private $sourceRepository;

    protected $adminSourceFactory;

    protected $adminSourceCollection;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Framework\Locale\ListsInterface $localeLists
     * @param array $data
     * @param OptionInterface $deployedLocales Operates with deployed locales.
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\InventoryApi\Api\SourceRepositoryInterface $sourceRepository,
        \Magento\Framework\Locale\ListsInterface $localeLists,
        \X247Commerce\StoreLocatorSource\Model\AdminSourceFactory $adminSourceFactory,
        \X247Commerce\StoreLocatorSource\Model\ResourceModel\AdminSource\CollectionFactory $adminSourceCollection,
        array $data = [],
        OptionInterface $deployedLocales = null
    ) {
        $this->_authSession = $authSession;
        $this->_LocaleLists = $localeLists;
        $this->sourceRepository = $sourceRepository;
        $this->deployedLocales = $deployedLocales
            ?: ObjectManager::getInstance()->get(OptionInterface::class);
        $this->adminSourceFactory = $adminSourceFactory;
        $this->adminSourceCollection = $adminSourceCollection;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Prepare form fields
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
        /** @var $model \Magento\User\Model\User */
        $model = $this->_coreRegistry->registry('permissions_user');

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('user_');

        $baseFieldset = $form->addFieldset('base_fieldset', ['legend' => __('Source Assign')]);

        if ($model->getUserId()) {
            $baseFieldset->addField('user_id', 'hidden', ['name' => 'user_id']);
        } else {
            if (!$model->hasData('is_active')) {
                $model->setIsActive(1);
            }
        }

        $sourceList = $this->sourceRepository->getList()->getItems();

        $sourceCode = [];

        foreach ($sourceList as $source) {
            $sourceCode[] = ['label' => $source['name'], 'value' => $source['source_code']];
        }

        $baseFieldset->addField(
            'source',
            'multiselect',
            [
                'label' => __('Sources'),
                'title' => __('Sources'),
                'name' => 'source',
                'required' => false,
                'values' => $sourceCode,
                'disabled' => false
            ]
        );

        $userId = $model->getUserId();
        $source = $this->getSourceCodeCollection($userId);

        $model->setData('source', $source);
        $data = $model->getData();
        

        $form->setValues($data);

        $this->setForm($form);

        return parent::_prepareForm();
    }

    public function getSourceCodeCollection($userId)
    {

        $collection = $this->adminSourceFactory->create()->getCollection()->addFieldToFilter('user_id', ['eq' => $userId]);
        $data = [];
        if ($collection) {
            foreach ($collection as $item) {
                $data[] = $item->getSourceCode();
            }
        }
     
        return $data;     
    }
}
