<?php
namespace Borntechies\RevertCancelOrder\Block\Adminhtml\Grid\Edit;

class Tabs extends \Magento\Backend\Block\Widget\Tabs {

	protected function _construct() {
		parent::_construct();
		$this->setId('grid_record');
		$this->setDestElementId('edit_form');
		$this->setTitle(__('Revert Canceled Order'));
	}
}