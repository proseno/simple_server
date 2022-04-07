<?php

namespace Model;

abstract class AbstractBlock implements BlockInterface
{
    public function render()
    {
        $templateClass = explode('\\', get_class($this));
        unset($templateClass[0]);
        array_walk($templateClass, fn (&$item) => $item = strtolower($item));
        $templateClass = implode("/", $templateClass);
        \Core\Loader::loadTemplate($templateClass);
    }
}