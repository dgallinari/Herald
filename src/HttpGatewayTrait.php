<?php
namespace HeraldOfArms\Herald;

trait HttpGatewayTrait
{
    /**
     * The http client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;
    /**
     * The configuration options.
     *
     * @var string[]
     */
    protected $config;

    /**
     * Get error response from server or fallback to general error.
     *
     * @param \GuzzleHttp\Message\ResponseInterface $rawResponse
     *
     * @return array
     */
    protected function responseError($rawResponse)
    {
        return $rawResponse->json() ?: $this->jsonError($rawResponse);
    }

    /**
     * Get the default json response.
     *
     * @param \GuzzleHttp\Message\ResponseInterface $rawResponse
     *
     * @return array
     */
    abstract protected function jsonError($rawResponse);

    /**
     * Build request url from string.
     *
     * @param string|null $endpoint
     *
     * @return string
     */
    protected function buildUrlFromString($endpoint = null)
    {
        if ($endpoint) {
            return $this->getRequestUrl() . '/' . $endpoint;
        }

        return $this->getRequestUrl();
    }

    /**
     * Get the gateway request url.
     *
     * @return string
     */
    abstract protected function getRequestUrl();
}