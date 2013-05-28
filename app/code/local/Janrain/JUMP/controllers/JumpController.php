<?php
class Janrain_JUMP_JumpController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		if (isset($_GET['token'])) {
			$this->_processToken($_GET['token']);
		}
	}

	private function _processToken($token)
	{
		echo $token;
	}
}
