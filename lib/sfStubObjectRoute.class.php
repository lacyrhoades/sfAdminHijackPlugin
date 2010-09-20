<?php

class sfStubObjectRoute extends sfObjectRoute
{
  public function __construct($pattern, array $defaults = array(), array $requirements = array(), array $options = array())
  {
    $this->isBound = true;
    $this->options['type'] = 'object';
  }

  public function injectObject($object)
  {
    $this-> object = $object;
  }
}
