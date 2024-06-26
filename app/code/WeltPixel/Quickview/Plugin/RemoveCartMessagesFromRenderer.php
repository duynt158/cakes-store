<?php

namespace WeltPixel\Quickview\Plugin;

use \Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Message\MessageInterface;

class RemoveCartMessagesFromRenderer
{
    /**
     * @var \WeltPixel\Quickview\Helper\Data $helper
     */
    protected $helper;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * RemoveCartMessagesFromRenderer constructor.
     * @param \WeltPixel\Quickview\Helper\Data $helper
     * @param \Magento\Framework\App\Request\Http $request
     * @param SessionManagerInterface $sessionManager
     */
    public function __construct(
        \WeltPixel\Quickview\Helper\Data $helper,
        \Magento\Framework\App\Request\Http $request,
        SessionManagerInterface $sessionManager
    ) {
        $this->helper = $helper;
        $this->request = $request;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param \Magento\Framework\View\Element\Message\Renderer\BlockRenderer $subject
     * @param $result
     * @param MessageInterface $message
     * @param array $initializationData
     * @return mixed
     */
    public function afterRender(
        \Magento\Framework\View\Element\Message\Renderer\BlockRenderer $subject,
        $result,
        MessageInterface $message,
        array $initializationData
    ) {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/confirmation_popup.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);
        $logger->info('afterRender start');
        $logger->info('afterRender isAjax not');
        if (!$this->request->isAjax() || !$this->helper->isAjaxCartEnabled()) {
            if (!$this->request->isAjax()) {
                $logger->info('afterRender isAjax not');
                $this->sessionManager->setData('wp_messages', true);
            }
            return $result;
        }

        $messageIdentifier = $message->getIdentifier();
        $logger->info('afterRender start $messageIdentifier addCartSuccessMessage');
        if ($messageIdentifier == 'addCartSuccessMessage') {
            $logger->info('afterRender $messageIdentifier addCartSuccessMessage');
            return '';
        }
        $logger->info('afterRender end');
        return $result;
    }
}
