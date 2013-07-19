<?php
class Janrain_JUMP_Block_Widget extends Mage_Core_Block_Template
{
    public function _toHtml()
    {
        $jump = janrain\Jump::getInstance();
        return $jump->getFeature('Capture')->getHtml() . '<br/>';
    }
}
?>
