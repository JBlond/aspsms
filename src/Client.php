<?php

namespace jblond\aspsms;


use CurlHandle;

class Client
{
    /**
     * @var false|CurlHandle
     */
    protected false|CurlHandle $curl;

    /**
     * @var string
     */
    protected string $data = '';

    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
        curl_setopt_array(
            $this->curl,
            [
                CURLOPT_HEADER => false,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_CONNECTTIMEOUT => 0,
                CURLOPT_TIMEOUT => 600,
            ]
        );
    }

    /**
     * Evaluate if the response was successful
     * @param string $data
     * @return bool
     */
    protected function analyseResponse(string $data): bool
    {
        $response = json_decode($data, true);
        if ($response['errorCode'] === 1) {
            return true;
        }
        return false;
    }

    /**
     * @param array $input
     */
    protected function setData(array $input): void
    {
        $this->data = json_encode($input, JSON_PRETTY_PRINT);
    }

    /**
     * @param string $url
     * @return bool|string
     */
    public function get(string $url): bool|string
    {
        curl_setopt_array(
            $this->curl,
            [
                CURLOPT_URL => $url,
            ]
        );
        return $this->sendRequest();
    }

    /**
     * @param string $url
     * @param array $data
     * @return bool
     */
    public function post(string $url, array $data): bool
    {
        $this->setData($data);
        curl_setopt_array(
            $this->curl,
            [
                CURLOPT_POST => true,
                CURLOPT_URL => $url,
                CURLOPT_POSTFIELDS => $this->data,
                CURLOPT_HTTPHEADER => ['Content-type: application/json']
            ]
        );
        return $this->analyseResponse($this->sendRequest());
    }

    /**
     * @return bool|string
     */
    protected function sendRequest(): bool|string
    {
        $response = curl_exec($this->curl);
        if ($response === false) {
            $response = curl_error($this->curl);
        }
        curl_close($this->curl);
        return $response;
    }
}
