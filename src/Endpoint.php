<?php

namespace jblond\aspsms;


/**
 * Class Endpoint
 * Endpoints can be found at https://webapi.aspsms.com/index.html
 * There are more endpoint to the API, but I did not require them.
 * @package jblond\aspsms
 */
class Endpoint
{
    /**
     * @var Client
     */
    protected Client $client;

    /**
     * @var string
     */
    protected string $baseurl = 'https://webapi.aspsms.com';

    /**
     * Endpoint constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @return bool|string
     */
    public function getCredits(): bool|string
    {
        return $this->client->get(
            $this->baseurl . '/ASPSMSCredits?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD']
        );
    }

    /**
     * @param string $message
     * @param array $recipients
     * @param string $when Y-m-dTH:i:s or date("c", timestamt)
     * @param string $notifyUrl URL that will be called when a message is delivered instantly. The submitted TransactionReferenceNumber will added to the URL.
     * @param string $notDeliveredUrl URL that will be connected when a message is not delivered. The submitted TransactionReferenceNumber will added to the URL.
     * @return bool|string
     */
    public function sendSMS(
        string $message,
        array $recipients,
        string $when,
        string $notifyUrl = '',
        string $notDeliveredUrl = ''
    ): bool|string
    {

        $who = [];
        foreach ($recipients as $recipient) {
            $who[]['PhoneNumber'] =  $recipient;
        }
        $data = [
            'Operation' => 'SendTextSMS',
            'Recipients' => $who,
            'DeferredDeliveryTime' => $when, //"2021-06-30T10:18:07.609Z",
            'MessageData' => $message,
            'Originator' => $_ENV['SENDER'],
        ];
        if ($notifyUrl !== '') {
            $data['URLDeliveryNotification'] = $notifyUrl;
        }
        if ($notDeliveredUrl !== '') {
            $data['URLNonDeliveryNotification'] = $notDeliveredUrl;
        }
        return $this->client->post(
            $this->baseurl . '/ASPSMSSendSMS?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD'],
            $data
        );
    }

    /**
     * @return mixed
     */
    public function getTrafficStat(): mixed
    {
        return json_decode($this->client->get(
            $this->baseurl .
            '/TrafficStat01/Get?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD'] . '&CalculateCredits=true'
        ), true);
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return mixed
     */
    public function getSendingStat(int $year = 0, int $month = 0, int $day = 0): mixed
    {
        $url = '';
        if ($year !== 0) {
            $url .= '&year=' . $year;
        }
        if ($month !== 0) {
            $url .= '&month=' . $month;
        }
        if ($day !== 0) {
            $url .= '&day=' . $day;
        }
        return json_decode($this->client->get(
            $this->baseurl .
            '/SendingStat01/Get?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD'] . $url
        ), true);
    }

    /**
     * @return mixed
     */
    public function getStats(): mixed
    {
        return json_decode($this->client->get(
            $this->baseurl .
            '/MSISDNDLRStat/Get?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD']
        ), true);
    }

    /**
     * @return mixed
     */
    public function getPhoneNumbers(): mixed
    {
        return json_decode($this->client->get(
            $this->baseurl . '/PhoneNumbers/Read?UserKey=' . $_ENV['KEY'] . '&Password=' . $_ENV['PASSWORD']
        ), true);
    }
}
