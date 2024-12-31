<?php

namespace Borntechies\RevertCancelOrder\Controller\Adminhtml\Order;

class Uncancel extends \Magento\Framework\App\Action\Action {

	protected $orderRepository;
	protected $stockManagement;
	protected $stockIndexerProcessor;
	protected $orderManagement;
	protected $messageManager;

	public function __construct(
		\Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
		\Magento\CatalogInventory\Api\StockManagementInterface $stockManagement,
		\Magento\CatalogInventory\Model\Indexer\Stock\Processor $stockIndexerProcessor,
		\Magento\Sales\Model\Order $orderManagement,
		\Magento\Framework\Message\ManagerInterface $messageManager,
		\Magento\Framework\App\Action\Context $context
	) {
		$this->orderRepository = $orderRepository;
		$this->stockManagement = $stockManagement;
		$this->stockIndexerProcessor = $stockIndexerProcessor;
		$this->orderManagement = $orderManagement;
		$this->messageManager = $messageManager;
		parent::__construct($context);
	}

	public function execute() {
		$postValue = $this->getRequest()->getPostValue();
		$incrementId = $postValue['Order_increment_id'];

		$orderInfo = $this->orderManagement->loadByIncrementId($incrementId);
		$orderId = $orderInfo->getId();		
		if ($orderId) {
			$order = $this->orderRepository->get($orderId);

			if ($order->isCanceled()) {
				$state = \Magento\Sales\Model\Order::STATE_PROCESSING;
				$productStockQty = [];
				foreach ($order->getAllVisibleItems() as $item) {
					$productStockQty[$item->getProductId()] = $item->getQtyCanceled();
					foreach ($item->getChildrenItems() as $child) {
						$productStockQty[$child->getProductId()] = $item->getQtyCanceled();
						$child->setQtyCanceled(0);
						$child->setTaxCanceled(0);
						$child->setDiscountTaxCompensationCanceled(0);
					}
					$item->setQtyCanceled(0);
					$item->setTaxCanceled(0);
					$item->setDiscountTaxCompensationCanceled(0);
				}

				$order->setSubtotalCanceled(0);
				$order->setBaseSubtotalCanceled(0);
				$order->setTaxCanceled(0);
				$order->setBaseTaxCanceled(0);
				$order->setShippingCanceled(0);
				$order->setBaseShippingCanceled(0);
				$order->setDiscountCanceled(0);
				$order->setBaseDiscountCanceled(0);
				$order->setTotalCanceled(0);
				$order->setBaseTotalCanceled(0);
				$order->setState($state)
					->setStatus($order->getConfig()->getStateDefaultStatus($state));
				if (!empty($comment)) {
					$order->addStatusHistoryComment($comment, false);
				}

				/* Reverting inventory */
				$itemsForReindex = $this->stockManagement->registerProductsSale(
					$productStockQty,
					$order->getStore()->getWebsiteId()
				);
				$productIds = [];
				foreach ($itemsForReindex as $item) {
					$item->save();
					$productIds[] = $item->getProductId();
				}
				if (!empty($productIds)) {
					$this->stockIndexerProcessor->reindexList($productIds);
				}
				$order->setInventoryProcessed(true);

				$order->save();

				$this->messageManager->addSuccessMessage('Order has ben Revert Successfully');
				$resultRedirect = $this->resultRedirectFactory->create();
				$resultRedirect->setPath('revertcancelorder/Index/');
				return $resultRedirect;

			} else {
				$this->messageManager->addErrorMessage('Cannot un-cancel this order.');
				$resultRedirect = $this->resultRedirectFactory->create();
				$resultRedirect->setPath('revertcancelorder/Index/');
				return $resultRedirect;
			}
		} else {
			$this->messageManager->addErrorMessage('Requested Order Increment ID Not Found.');
			$resultRedirect = $this->resultRedirectFactory->create();
			$resultRedirect->setPath('revertcancelorder/Index/');
			return $resultRedirect;
		}
	}
}