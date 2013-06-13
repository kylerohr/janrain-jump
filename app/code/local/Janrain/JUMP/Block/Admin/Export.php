<?php 
class Janrain_JUMP_Block_Admin_Export extends Mage_Adminhtml_Block_System_Config_Form_Field
{

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $url = 'janrain/jump'; // @TODO: Make this point somewhere useful

        $html = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('scalable')
                    ->setLabel('Export')
                    ->setOnClick("setLocation('$url')")
                    ->toHtml();

        return $html;
    }
}
?>
