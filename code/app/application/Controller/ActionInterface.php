<?php

namespace Controller;

interface ActionInterface
{
    public function execute(): ?bool;
}