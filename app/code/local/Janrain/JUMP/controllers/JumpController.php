<?php
class Janrain_JUMP_JumpController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		if (isset($_GET['token'])) {
			$this->processToken($_GET['token']);
		}
	}

	protected function processToken($token)
	{
		$uuid = Mage::app()->getRequest()->getParam('uuid');
		$config = Mage::getModel('janrain_jump/config');
		$configAO = new \ArrayObject($config->getConfig());
		$api = new janrain\jump\Api($configAO);
		$user = $api->fetchUserByUuid($uuid, $token);
		//var_dump($user);
		//echo $token;

		$this->loginUser($user);
	}

	protected function loginUser($user) {
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());

		// @TODO: Verify Janrain login information first

		$email = $user->email;
		$customer->loadByEmail($email);

		if (!$customer->getId()) {
			$customer->setEmail($email);
			$customer->setPassword($customer->generatePassword(16));
			// @TODO: Set confirmed? Is this necessary?
			$customer->save();
		}

		// @TODO: Add entry in jump table with UUID
		// @TODO: Call loginUser()
	}
}
