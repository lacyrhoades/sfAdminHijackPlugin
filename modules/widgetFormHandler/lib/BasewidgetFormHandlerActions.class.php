<?php

/**
 * Base actions for the sfAdminHijacksPlugin widgetFormHandler module.
 * 
 * @package     sfAdminHijacksPlugin
 * @subpackage  widgetFormHandler
 * @author      Christian Schaefer <caefer@ical.ly>
 * @version     SVN: $Id: BaseActions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
abstract class BasewidgetFormHandlerActions extends sfActions
{
  public function preExecute()
  {
    sfConfig::set('sf_web_debug', false);
  }

  public function executeAutocomplete(sfWebRequest $request)
  {
    $q      = $request->getGetParameter('q');
    $model  = $request->getParameter('model');
    $fields = explode(',', $request->getParameter('fields'));
    $id     = $request->getParameter('id');

    $query = Doctrine_Core::getTable($model)->createQuery('o');
    if($id)
    {
      $query->andWhere('o.id = ?', $id);
    }
    else
    {
      foreach($fields as $field)
      {
        $query->orWhere($field.' LIKE ?', $q.'%');
      }
    }
    $this->results = $query->execute(array(), Doctrine_Core::HYDRATE_ARRAY);
  }

  public function executeDelete(sfWebRequest $request)
  {
    $objectClass = $request->getParameter('model');
    $objectId    = $request->getParameter('id',      false);

    $object = $this->getObject($objectClass, $objectId, false);
    $object->delete();
    return sfView::NONE;
  }

  public function executeForm(sfWebRequest $request)
  {
    $objectClass = $request->getParameter('model');
    $objectId    = $request->getParameter('id',      false);
    $cloneObject = $request->getParameter('clone',   false);
    $moduleName  = $request->getParameter('targetModule');
    $actionName  = (false === (boolean) $objectId) ? 'new' : 'edit';

    $object = $this->getObject($objectClass, $objectId, $cloneObject);
    $this->injectStubRoute($object);
    $this->viewData = $this->getController()->getPresentationFor($moduleName, $actionName, 'sfPHPUndecorated');
  }

  public function postExecute()
  {
    $replace = strstr($this->viewData, '<ul class="sf_admin_actions">');
    $this->viewData = str_replace($replace, '', $this->viewData);

    if(false !== (boolean) $this->getRequest()->getParameter('clone',   false))
    {
      $this->viewData = str_replace('<h1>Edit', '<h1>New', $this->viewData);
    }
    $this->viewData = str_replace('<form', '<form id="widget-form"', $this->viewData);
  }

  protected function injectStubRoute($object)
  {
    $route = new sfStubObjectRoute('/');
    $route->injectObject($object);
    $this->getRequest()->setAttribute('sf_route', $route);
  }

  protected function getObject($model, $id = false, $copy = false)
  {
    if(false === (boolean) $id)
    {
      return new $model();
    }
    else
    {
      $object = Doctrine_core::getTable($model)->find($id);
    }

    return ((boolean) $copy) ? $object->copy(false) : $object;
  }
}
