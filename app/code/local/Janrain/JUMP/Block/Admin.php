<?php
class Janrain_JUMP_Block_Admin extends Mage_Core_Block_Template
{
    protected function _toHtml()
    {
        return
        "<form method='post' action='<?php echo \$url?>'>
            <fieldset>
                <legend>hi</legend>
                <input name='posted' value=''/>
                <button type='submit'>Save Changes</button>
                <input type='hidden' name='form_key' value='{\$form_key}'/>
            </fieldset>
        </form>";
    }
}
?>
