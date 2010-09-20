<?php

class sfWidgetFormJQueryAdminHijackDoctrineChoice extends sfWidgetFormDoctrineChoice
{
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addRequiredOption('model');
    $this->addRequiredOption('module');
    $this->addRequiredOption('fields');

    $this->addOption('template.html', '
    <input type="hidden" name="##NAME##" value="##VALUE##" id="widget-hidden" rel="old" />
    <ul class="sf_admin_actions">
      <li class="sf_admin_action_new"><a id="widget-button-new" href="javascript:void(0)">New</a></li>
      <li class="sf_admin_action_edit"><a id="widget-button-edit" href="javascript:void(0)">Edit</a></li>
      <li class="sf_admin_action_use"><a id="widget-button-use" href="javascript:void(0)">Search</a></li>
      <li class="sf_admin_action_delete"><a id="widget-button-delete" href="javascript:void(0)">Delete</a></li>
    </ul>
    <input type="text" id="widget-autocomplete" style="display:none;" />
    <div id="widget-canvas"><em>Currently none selected..</em></div>
    <script type="text/javascript">
      ModalChoiceWidgetConfig = {
        formUrl:"##FORMURL##",
        autocompleteUrl:"##AUTOCOMPLETEURL##",
        deleteUrl:"##DELETEURL##",
        model:"##MODEL##",
        fields:##FIELDS##
      }
    </script>
    ');

    $this->addOption('javascript.sources', array(
      '/sfAdminHijackPlugin/js/widget.js'
    ));
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $tokens = array(
      '##FORMURL##'         => sfContext::getInstance()->getRouting()->generate('widgetFormHandler',  array('model' => $this->getOption('model'), 'targetModule' => $this->getOption('module')), true),
      '##AUTOCOMPLETEURL##' => sfContext::getInstance()->getRouting()->generate('widgetAutoComplete', array('model' => $this->getOption('model'), 'fields' => implode(',', $this->getOption('fields'))), true),
      '##DELETEURL##'       => sfContext::getInstance()->getRouting()->generate('widgetDelete',       array('model' => $this->getOption('model')), true),
      '##MODEL##'           => $this->getOption('model'),
      '##FIELDS##'          => json_encode($this->getOption('fields')),
      '##NAME##'            => $name,
      '##VALUE##'           => $value
    );

    return str_replace(array_keys($tokens), array_values($tokens), $this->getOption('template.html'));
  }

  public function getJavaScripts()
  {
    return array_merge(parent::getJavaScripts(), $this->getOption('javascript.sources'));
  }
}
