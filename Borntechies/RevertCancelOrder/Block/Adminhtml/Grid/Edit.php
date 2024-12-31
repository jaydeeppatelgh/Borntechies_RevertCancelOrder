<?php
namespace Borntechies\RevertCancelOrder\Block\Adminhtml\Grid;

class Edit extends \Magento\Backend\Block\Widget\Form\Container {

    protected $_backendUrl;

    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Backend\Model\UrlInterface $backendUrl
    ){
        $this->_backendUrl = $backendUrl;
        parent::__construct($context);
    }

    protected function _construct() {

        $url_back = $this->_backendUrl->getUrl("admin/dashboard");

        $this->_objectId = 'id';
        $this->_blockGroup = 'Borntechies_RevertCancelOrder';
        $this->_controller = 'adminhtml_grid';
        parent::_construct();
        $this->buttonList->update('save', 'label', __('Submit'));
        
        $this->addButton(
            'back',
            [
                'label' => __('Back'),
                'onclick' => 'document.location.href="'.$url_back.'"',
                'class' => 'back'
            ],-1
        );
    }
}