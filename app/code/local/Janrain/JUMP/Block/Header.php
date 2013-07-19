<?php
class Janrain_JUMP_Block_Header extends Mage_Core_Block_Template
{
	protected function _toHtml()
	{
		$configModel = Mage::getModel('jump/config');
		$config = $configModel->getConfig();
		$config['features'] = array('Capture');

		$jump = \janrain\Jump::getInstance();
		$jump->init($config);

		return $jump->raw_render();
	}
}
?>
