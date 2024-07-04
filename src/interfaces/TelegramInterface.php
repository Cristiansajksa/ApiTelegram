<?php
interface TelegramInterface
{
    public function Get(string $method, array|string $params);
    public function Post(string $method, array|string $postField);
    public function CheckerResult(object $curlObject, string $method);
    public function executeRetrys(object $curlObject);
}