<?php

class sfPHPUndecoratedView extends sfPHPView
{
  protected function decorate($content)
  {
    return $content;
  }
}
