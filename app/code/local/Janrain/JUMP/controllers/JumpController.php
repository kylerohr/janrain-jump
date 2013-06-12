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
		$config = Mage::getModel('jump/config');
		$configAO = new \ArrayObject($config->getConfig());
		$api = new janrain\jump\Api($configAO);
		$user = $api->fetchUserByUuid($uuid, $token);

		$this->loginUser($user);
	}

	protected function loginUser($user) {
		$customer = Mage::getModel('customer/customer');
		$customer->setWebsiteId(Mage::app()->getWebsite()->getId());

		// @TODO: Verify Janrain login information first

		$email = $user->email;
		$customer->loadByEmail($email);

		// Create the customer if necessary and add a record to link to Capture
		if (!$customer->getId()) {
			$customer->setEmail($email);
			$customer->setPassword($customer->generatePassword(16));
			// @TODO: Set confirmed? Is this necessary?
			$customer->save();

			// Add UUID to the janrain_jump table
			$jump_user = Mage::getModel('jump/user');

			$jump_user->setCustomerId($customer->getId());
			$jump_user->setUuid($user->getUuid());
			$jump_user->save();
		}
		
		// Log the customer into the site
		Mage::getSingleton('customer/session')->loginById($customer->getId());

		// Redirect to the customer account page
		$this->_redirect('customer/account');
	}
}
