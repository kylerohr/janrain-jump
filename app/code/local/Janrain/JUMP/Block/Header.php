<?php
class Janrain_JUMP_Block_Header extends Mage_Core_Block_Template
{
	protected function _toHtml()
	{
		$jump = \janrain\Jump::getInstance();
		return $jump->raw_render();
	}
}
?>
