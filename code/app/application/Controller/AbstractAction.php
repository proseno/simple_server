<?php

namespace Controller;

abstract class AbstractAction implements ActionInterface
{
    protected string $template;

    public function execute(): ?string
    {
        if (!isset($this->template)) {
            $templateClass = explode('\\', get_class($this));
            unset($templateClass[0]);
            $templateClass = implode("\\", $templateClass);
        } else {
            $templateClass = $this->template;
        }
        return \Core\Loader::loadClass("\\Model\\$templateClass", "render");
    }
}