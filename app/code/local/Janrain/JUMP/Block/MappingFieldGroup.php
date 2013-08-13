<?php
use janrain\jump\data\Transform;

class Janrain_JUMP_Block_MappingFieldGroup extends Mage_Adminhtml_Block_System_Config_Form_Fieldset
{
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html = $this->_getHeaderHtml($element);

        $mappingjson = Mage::getStoreConfig('jump/mapping/json');
        $defaultMap = str_replace('"','\"', json_encode(json_decode($mappingjson)));
        //$defaultMap = str_replace(array("\n", '"'), array('','\\"'), $mappingjson);
        $html .= <<<SCRIPT
            <script type='text/javascript'>
            //<![CDATA[
            document.jump = {
                default: "{$defaultMap}",
                append: function ()
                {
                    var ta = $('jump_maps');
                    var op = $('jump_op').value;
                    var j = $('jump_j').value;
                    var p = $('jump_p').value;
                    var newOp = ',\\n{\"op\":' + op + ',\"j\":' + j + ',\"p\":' + p + '}';
                    ta.value = [ta.value.slice(0,-1), newOp, ta.value.slice(-1)].join('');
                },
                reset: function ()
                {
                    $('jump_maps').value = this.default.replace( /\},/g , '},\\n' );
                }
            };
            //]]>
            </script>
SCRIPT;
        ##### JANRAIN CODE HERE #####
        //$html .= '<pre>'. print_r(Mage::getStoreConfig('jump'), true) .'</pre>';
        $html .= sprintf('<textarea id="jump_maps" name="%s" style="width:100%%;height:10em">%s</textarea>', 'groups[mapping][fields][json][value]', $mappingjson);
        $jump = \janrain\Jump::getInstance();
        $fields = $jump->getFeature('CaptureApi')->getUserFields();

        $html .= '<label>Mapping Op</label><select id="jump_op">';
        foreach (Transform::getAvailableOps() as $opName) {
            $html .= "<option>$opName</option>";
        }

        $html .= '</select>';


        $html .= '<label>Capture Fields</label><select id="jump_j">';
        foreach ($fields as &$f) {
            $plural = substr($f, -1) == '*'
                ? ' disabled="disabled"'
                : '';
            $title = $plural ? 'Plural mapping not supported.' : $f;
            $html .= sprintf("<option value='%s'%s title='%s'>%s</option>",$f, $plural, $title, $f);
        }
        $html .= '</select>';

        $html .= '<label>Magento Field</label><select id="jump_p">';
        foreach (Mage::getModel('janrain_jump/user')->getMappableFields() as $f) {
            $html .= sprintf('<option>%s</option>', $f);
        }
        $html .= '</select>';

        $html .= '<button type="button" onclick="jump.append();return false">Add Mapping</button>';
        $html .= '<button type="button" onclick="jump.reset();return false">Clear Changes</button>';

        ##### END JANRAIN CODE #####

        $html .= $this->_getFooterHtml($element);
        return $html;
    }
}
