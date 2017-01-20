<?php


namespace app\modules\click2call\models;


class CallRequest implements RequestInterface
{
    private $phoneNumber;

    private $callerId;

    public function __construct($phoneNumber, $callerId)
    {
        $this->phoneNumber = $phoneNumber;
        $this->callerId = $callerId;
    }

    private function getChannel()
    {
        return 'SIP/' . $this->callerId;
    }

    public function requestParameters()
    {
        return [
            new RequestParameter('action', 'Originate'),
            new RequestParameter('channel', $this->getChannel()),
            new RequestParameter('priority', 1),
            new RequestParameter('exten', $this->phoneNumber),
            new RequestParameter('context', 'from-internal'),
            new RequestParameter('callerid', $this->callerId),
        ];
    }
}