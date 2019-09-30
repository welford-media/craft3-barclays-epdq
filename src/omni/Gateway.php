<?php

namespace welfordmedia\barclaysepdqgateway\omni;

use Omnipay\Common\Http\ClientInterface;
use Omnipay\Common\AbstractGateway;
use welfordmedia\barclaysepdqgateway\omni\Message\CompletePurchaseRequest;
use welfordmedia\barclaysepdqgateway\omni\Message\PurchaseRequest;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class Gateway extends AbstractGateway
{
    public function getDefaultParameters()
    {
        return array(
            'clientId' => '',
            'testMode' => false,
            'language' => 'en_US',
            'callbackMethod' => 'POST'
        );
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(
            '\welfordmedia\barclaysepdqgateway\omni\Message\PurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest(
            '\welfordmedia\barclaysepdqgateway\omni\Message\CompletePurchaseRequest',
            array_merge($this->parameters->all(), $parameters)
        );
    }

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }

    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
    }

    public function getShaIn()
    {
        return $this->getParameter('shaIn');
    }

    public function setShaIn($value)
    {
        return $this->setParameter('shaIn', $value);
    }

    public function getShaOut()
    {
        return $this->getParameter('shaOut');
    }

    public function setShaOut($value)
    {
        return $this->setParameter('shaOut', $value);
    }

    public function getReturnUrl()
    {
        return $this->getParameter('returnUrl');
    }

    public function getDeclineUrl()
    {
        return $this->getParameter('declineUrl');
    }

    public function setDeclineUrl($value)
    {
        return $this->setParameter('declineUrl', substr($value, 0, 200));
    }

    public function getCancelUrl()
    {
        return $this->getParameter('cancelUrl');
    }

    public function setCancelUrl($value)
    {
        return $this->setParameter('cancelUrl', substr($value, 0, 200));
    }

    public function getExceptionUrl()
    {
        return $this->getParameter('exceptionUrl');
    }

    public function setExceptionUrl($value)
    {
        return $this->setParameter('exceptionUrl', substr($value, 0, 200));
    }

    public function setReturnUrl($value)
    {
        $this->setParameter('returnUrl', $value);
        $this->setParameter('declineUrl', $value);
        $this->setParameter('exceptionUrl', $value);

        return $this;
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
    }

    public function setPageLayout($value)
    {
        return $this->setParameter('pageLayout', $value);
    }

    public function getName()
    {
        return "Barclays ePDQ";
    }
}
