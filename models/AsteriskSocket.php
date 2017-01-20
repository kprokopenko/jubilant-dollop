<?php


namespace app\modules\click2call\models;


use yii\base\ErrorException;

/**
 * Открывает сокет для работы с Asterisk
 */
class AsteriskSocket
{
    /**
     * @var resource
     */
    private $socket;

    /**
     * @var bool Выводить отправляемые запросы в консоль, вместо отправки на сервер
     */
    private $emulateRequests = false;

    private $printDebugInfo = false;

    private function createSocket($ip, $port)
    {
        $this->socket = fsockopen($ip, $port);

        if ($this->socket === false) {
            throw new ErrorException('Не удалось установить соединение с Asterisk');
        }

        stream_set_timeout($this->socket, 0, 400000);

        $this->readRawData();
    }

    private function readRawData()
    {
        $rawData = '';
        while ($byte = fread($this->socket, 1)) {
            $rawData .= $byte;
        }

        if ($this->printDebugInfo) {
            echo $rawData;
        }
    }

    private function sendRawData($data)
    {
        if ($this->emulateRequests) {
            echo $data;
            return;
        }

        if ($this->printDebugInfo) {
            echo $data;
        }

        fwrite($this->socket, $data);

        $this->readRawData();
    }

    private function sendRequestParameter(RequestParameter $requestParameter)
    {
        $this->sendRawData($requestParameter->getKey() . ': ' . $requestParameter->getValue() . PHP_EOL);
    }

    public function __construct($ip, $port)
    {
        $this->createSocket($ip, $port);
    }

    public function __destruct()
    {
        fclose($this->socket);
    }

    public function sendRequestParameters(array $requestParameters)
    {
        foreach ($requestParameters as $parameter) {
            $this->sendRequestParameter($parameter);
        }

        $this->sendRawData(PHP_EOL);
        usleep(1000);
    }

    public function enableEmulateRequests()
    {
        $this->emulateRequests = true;
    }

    public function disableEmulateRequests()
    {
        $this->emulateRequests = false;
    }

    public function enablePrintDebugInfo()
    {
        $this->printDebugInfo = true;
    }

    public function disablePrintDebugInfo()
    {
        $this->printDebugInfo = false;
    }
}