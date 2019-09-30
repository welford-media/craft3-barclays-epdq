<?php

namespace welfordmedia\barclaysepdqgateway\omni\Message;

use Symfony\Component\HttpFoundation\ParameterBag;

class CompletePurchaseRequest extends PurchaseRequest
{

    public function getData()
    {
        $requestData = $this->getRequestData();

        if ($this->getShaOut() && array_key_exists('SHASIGN', $requestData)) {
            $barclaysSha = (string)$requestData['SHASIGN'];
            unset($requestData['SHASIGN']);

            $ourSha = $this->calculateSha($this->cleanParameters($requestData), $this->getShaOut());

            if ($ourSha !== $barclaysSha) {
                throw new InvalidResponseException("Hashes do not match, request is faulty or has been tampered with.");
            }
        }

        return $requestData;
    }

    public function getRequestData()
    {
        $data = ($this->getCallbackMethod() == 'POST') ?
            $this->httpRequest->request->all() :
            $this->httpRequest->query->all();
        if (empty($data)) {
            throw new InvalidResponseException(sprintf(
                "No callback data was passed in the %s request",
                $this->getCallbackMethod()
            ));
        }
        return $data;
    }


    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }
}