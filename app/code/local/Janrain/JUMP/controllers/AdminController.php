<?php
class Janrain_JUMP_AdminController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $layout = $this->getLayout();
        $url = Mage::helper('adminhtml')->getUrl('/admin/post');
        $form_key = Mage::getSingleton('core/session')->getFormKey();
        $block = $this->getLayout()->createBlock('janrain_jump/adminhtml_form_edit');
        $leftBlock = $this->getLayout()->createBlock('janrain_jump/adminhtml_form_edit_tabs');
        $this->_addContent($block);

        $this->_setActiveMenu('system');
        $this->renderLayout();
    }

    public function postAction()
    {
        $jump = \janrain\Jump::getInstance();
        $post = $this->getRequest()->getPost();
        echo '<pre>', print_r($post, true), '</pre>';
        foreach ($post as $k => $v) {

        }
        exit();
        //$this->_redirect('*/*/');
    }
}
