<?php


namespace MuCTS\Helpers\Exceptions;


use GuzzleHttp\Client;
use Throwable;

class ResponseException extends \Exception
{
    private null|Client $request;
    private string      $method;
    private string      $uri;
    private array       $parameters;

    public function __construct(Client $request, string $method, string $uri, array $parameters, $message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->request    = $request;
        $this->method     = $method;
        $this->uri        = $uri;
        $this->parameters = $parameters;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    private function getMethod(): string
    {
        return $this->method;
    }

    private function getParameter(): array
    {
        return $this->parameters;
    }

    public function getRequest(): Client
    {
        return $this->request;
    }
}