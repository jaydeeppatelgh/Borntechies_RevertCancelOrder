<?php
namespace Borntechies\RevertCancelOrder\Block\Adminhtml\Grid\Edit\Tab;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;

class Main extends Generic implements TabInterface {

	protected $_coreRegistry = null;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\Magento\Framework\Registry $registry,
		\Magento\Framework\Data\FormFactory $formFactory,
		array $data = []
	) {
		parent::__construct($context, $registry, $formFactory, $data);
	  }

	protected function _prepareForm() {

		$model = $this->_coreRegistry->registry('Borntechies_RevertCancelOrder_form_data');
		$isElementDisabled = false;
		$form = $this->_formFactory->create();
		$form->setHtmlIdPrefix('page_');
		$fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Revert Canceled Order')]);

		$fieldset->addField(
			'Order Increment ID',
			'text',
			[
				'name' => 'Order_increment_id',
				'label' => __('Order Increment ID'),
				'title' => __('Order Increment ID'),
				'required' => true,
				'disabled' => $isElementDisabled,
			]
		);

		$this->setForm($form);
		return parent::_prepareForm();
	}

	public function getTabLabel() {
		return __('Revert Canceled Order');
	}
	public function getTabTitle() {
		return __('Revert Canceled Order');
	}
	public function canShowTab() {
		return true;
	}
	public function isHidden() {
		return false;
	}
}