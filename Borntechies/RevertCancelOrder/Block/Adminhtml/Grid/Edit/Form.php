<?php
namespace Borntechies\RevertCancelOrder\Block\Adminhtml\Grid\Edit;

class Form extends \Magento\Backend\Block\Widget\Form\Generic {

	protected $_backendUrl;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\Data\FormFactory $formFactory,
		\Magento\Backend\Model\UrlInterface $backendUrl,
		array $data = []
	) {
		$this->_backendUrl = $backendUrl;
		parent::__construct($context, $registry, $formFactory, $data);
	  }

	protected function _prepareForm() {
		$url_form_action = $this->_backendUrl->getUrl("revertcancelorder/Order/Uncancel");

		$form = $this->_formFactory->create(
			[
				'data' => [
					'id' => 'edit_form',
					'action' => $url_form_action,
					'method' => 'post',
					'enctype' => 'multipart/form-data',
				],
			]
		);
		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}
}