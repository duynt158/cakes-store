<?php 

namespace X247Commerce\Sales\Plugin;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class BeforeSendEmailOrder
{

    protected $scopeConfig;

    protected $_coreSession;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Session\SessionManagerInterface $coreSession
    ){
        $this->scopeConfig = $scopeConfig;
        $this->_coreSession = $coreSession;
    }
    public function beforeSend(\Magento\Sales\Model\Order\Email\Sender\OrderSender $subject, $order)
    {
        $this->_coreSession->start();
        $this->_coreSession->setX247Order($order);
        return [$order];
    }

    public function afterSend(\Magento\Sales\Model\Order\Email\Sender\OrderSender $subject, $result)
    {
        
        $this->_coreSession->start();
        $this->_coreSession->setX247Order();
        return $result;
    }
}
