<?php

namespace Controller;

interface ActionInterface
{
    public function execute(): ?string;
}