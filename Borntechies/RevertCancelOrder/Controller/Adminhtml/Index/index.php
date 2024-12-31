<?php

namespace Borntechies\RevertCancelOrder\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;

class Index extends \Magento\Backend\App\Action {

     protected $_resultFactory;

     public function __construct(Action\Context $context,
       \Magento\Framework\View\Result\PageFactory $resultFactory) 
     {  
          $this->_resultFactory = $resultFactory;
          parent::__construct($context);
     }

     public function execute() {

          $resultPage = $this->_resultFactory->create();
          $resultPage->getConfig()->getTitle()->prepend((__('Revert Cancel Order')));
          return $resultPage;

     }

}