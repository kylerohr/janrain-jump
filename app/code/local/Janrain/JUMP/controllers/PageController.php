<?php
require_once "Mage/Cms/controllers/PageController.php";

class Janrain_JUMP_PageController extends Mage_Cms_PageController
{
	public function viewAction() {
		$this->loadLayout();
		$block = $this->getLayout()->createBlock(
			'Mage_Core_Block_Template',
			'test123',
			array('template' => 'jump/widget.phtml')
		);

		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
}
