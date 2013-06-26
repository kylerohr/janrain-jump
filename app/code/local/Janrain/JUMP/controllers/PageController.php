<?php
require_once "Mage/Cms/controllers/PageController.php";

class Janrain_JUMP_PageController extends Mage_Cms_PageController
{
	public function viewAction() {
		exit('blah');
		$this->loadLayout();
		$block = $this->getLayout()->createBlock(
			'core/template',
			'test123',
			array('template' => 'janrain/jump/test.phtml')
		);

		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
}
