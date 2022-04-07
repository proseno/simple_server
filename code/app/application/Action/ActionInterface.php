<?php

namespace Action;

interface ActionInterface
{
    public function execute(): ?string;
}