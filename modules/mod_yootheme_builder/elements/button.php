<?php

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Language\Text;
use Joomla\Registry\Registry;

class JFormFieldButton extends FormField
{
    protected $type = 'Button';
    protected $href = '';

    public function renderField($options = [])
    {
        if (!($templ = $this->getTemplate())) {
            return '<p id="alert-customizer" class="alert alert-error">Please create a YOOtheme <a href="index.php?option=com_templates">template style</a>.</p>';
        }

        $this->href = "index.php?option=com_ajax&p=customizer&templateStyle={$templ->id}&format=html&section=joomla-modules";

        return parent::renderField($options);
    }

    public function getInput()
    {
        return '<script>

            document.addEventListener("DOMContentLoaded", function () {

                var label = document.getElementById("jform_params_button-lbl");
                var group = label.closest(".control-group");
                group.hidden = true;

                if (!parent.document || !parent.document.querySelector(".uk-modal-page")) {

                    var button = document.createElement("a");
                    button.textContent = "' .
            Text::_('Open Builder') .
            '";
                    button.classList.add("tm-button");
                    button.href = "' .
            $this->href .
            '&return=" + encodeURIComponent(location.href);

                    group.parentNode.insertBefore(button, group);

                    ' .
            (!$this->form->getData()->get('id')
                ? '
                    button.addEventListener("click", function (e) {
                        e.preventDefault();
                        window.alert("' .
                    Text::_('Please save the module first.') .
                    '");
                    })'
                : '') .
            '
                }

            });

        </script>
        <style>
            .tm-button {
                display: block;
                box-sizing: border-box;
                width: 280px;
                max-width: 100%;
                padding: 20px 30px;
                border-radius: 2px;
                background: linear-gradient(140deg, #FE67D4, #4956E3);
                box-shadow: inset 0 0 1px 0 rgba(0,0,0,0.5);
                line-height: 10px;
                vertical-align: middle;
                color: #fff !important;
                font-size: 11px;
                font-weight: bold;
                font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
                text-align: center;
                text-decoration: none !important;
                text-transform: uppercase;
                letter-spacing: 2px;
                -webkit-font-smoothing: antialiased;
            }\
        </style>';
    }

    protected function getTemplate()
    {
        $db = Factory::getDbo();
        $db->setQuery(
            'SELECT id, params from #__template_styles WHERE client_id = 0 ORDER BY home DESC'
        );

        foreach ($db->loadObjectList() as $templ) {
            $params = new Registry($templ->params);

            if ($params->get('yootheme')) {
                return $templ;
            }
        }
    }
}
