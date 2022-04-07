<?php

namespace Core\Request;

interface RequestInterface
{
    const URL = 'url';
    const GET_PARAMS = 'get_params';
    const POST_PARAMS = 'post_params';

    public function getUrl(): string;

    public function getGetParams(): array;

    public function getPostParams(): array;

    public function setUrl(string $url): static;

    public function setGetParams(array $getParams): static;

    public function setPostParams(array $postParams): static;
}