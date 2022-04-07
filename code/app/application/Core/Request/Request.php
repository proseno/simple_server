<?php

namespace Core\Request;

use Core\DataObject;

class Request extends DataObject implements RequestInterface
{
    public function getUrl(): string
    {
        return (string)$this->getData(static::URL);
    }

    public function getGetParams(): array
    {
        return (array)$this->getData(static::GET_PARAMS);
    }

    public function getPostParams(): array
    {
        return (array)$this->getData(static::POST_PARAMS);
    }

    public function setUrl(string $url): static
    {
        $this->setData(static::URL, $url);
        return $this;
    }

    public function setGetParams(array $getParams): static
    {
        $this->setData(static::GET_PARAMS, $getParams);
        return $this;
    }

    public function setPostParams(array $postParams): static
    {
        $this->setData(static::POST_PARAMS, $postParams);
        return $this;
    }
}