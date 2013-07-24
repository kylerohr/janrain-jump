<?php
class Janrain_JUMP_AdminController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();

        $url = Mage::helper('adminhtml')->getUrl('/admin/post');
        $form_key = Mage::getSingleton('core/session')->getFormKey();
        $block = $this->getLayout()->createBlock('janrain_jump/admin');
        $this->_addContent($block);
        $this->_setActiveMenu('system');
        $this->renderLayout();
    }

    public function postAction()
    {
        $jump = Janrain\Jump::instance();
        $post = $this->getRequest()->getPost();
        var_dump($post);
        exit();
    }
}
