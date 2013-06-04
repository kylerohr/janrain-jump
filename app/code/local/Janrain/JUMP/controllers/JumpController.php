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
		$uuid = Mage::app()->getRequest()->getParam('uuid');
		$config = Mage::getModel('janrain_jump/config');
		$configAO = new \ArrayObject($config->getConfig());
		$api = new janrain\jump\Api($configAO);
		$user = $api->fetchUserByUuid($uuid, $token);
		var_dump($user);
		//echo $token;
	}

	private function __registerUser($token, $uuid) {
		
	}

	private function __loginUser($token, $uuid) {

	}
}
