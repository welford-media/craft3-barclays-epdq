<?php

namespace welfordmedia\barclaysepdqgateway\omni\Message;

use Omnipay\Common\Message\AbstractRequest;

class PurchaseRequest extends AbstractRequest
{
    protected $liveEndpoint = 'https://payments.epdq.co.uk/ncol/prod/orderstandard_utf8.asp';
    protected $testEndpoint = 'https://mdepayments.epdq.co.uk/ncol/test/orderstandard_utf8.asp';

    public function getClientId()
    {
        return $this->getParameter('clientId');
    }

    public function setClientId($value)
    {
        return $this->setParameter('clientId', substr($value, 0, 30));
    }

    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    public function setLanguage($value)
    {
        return $this->setParameter('language', $value);
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
        $value = substr($value, 0, 200);
        $this->setParameter('returnUrl', $value);
        $this->setParameter('declineUrl', $value);
        $this->setParameter('exceptionUrl', $value);

        return $this;
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

    public function getShaVersion()
    {
        return $this->getParameter('shaVersion');
    }

    public function setShaVersion($value)
    {
        return $this->setParameter('shaVersion', $value);
    }

    public function getCallbackMethod()
    {
        return $this->getParameter('callbackMethod');
    }

    public function setCallbackMethod($value)
    {
        return $this->setParameter('callbackMethod', $value);
    }

    public function getPageLayout()
    {
        return $this->getParameter('pageLayout');
    }

    public function setPageLayout($value)
    {
        return $this->setParameter('pageLayout', $value);
    }

    public function getData()
    {
        //$this->validate('amount', 'clientId', 'currency', 'language');

        $data = array();

        $data['PSPID']          = $this->getClientId();

        $data['ORDERID']        = $this->getTransactionId();
        $data['PARAMVAR']       = $this->getTransactionId();
        $data['CURRENCY']       = $this->getCurrency();
        $data['LANGUAGE']       = $this->getLanguage();
        $data['AMOUNT']         = $this->getAmountInteger();

        $data['ACCEPTURL']      = $this->getReturnUrl();
        $data['CANCELURL']      = $this->getCancelUrl();
        $data['DECLINEURL']     = $this->getDeclineUrl();
        $data['EXCEPTIONURL']   = $this->getReturnUrl();

        $card = $this->getCard();
        if ($card) {
            $data['CN']              = $card->getName();
            $data['COM']             = $card->getCompany();
            $data['EMAIL']           = $card->getEmail();
            $data['OWNERZIP']        = $card->getPostcode();
            $data['OWNERTOWN']       = $card->getCity();
            $data['OWNERCTY']        = $card->getCountry();
            $data['OWNERTELNO']      = $card->getPhone();
            $data['OWNERADDRESS']    = $card->getAddress1();
            $data['OWNERADDRESS2']   = $card->getAddress2();
        }

        $pageLayout = $this->getPageLayout();
        if (is_array($pageLayout)) {
            $data['TITLE'] = $pageLayout['pl_title'];
            $data['BGCOLOR'] = $pageLayout['pl_bgcolor'];
            $data['TXTCOLOR'] = $pageLayout['pl_txtcolor'];
            $data['TBLBGCOLOR'] = $pageLayout['pl_tblbgcolor'];
            $data['TBLTXTCOLOR'] = $pageLayout['pl_tbltxtcolor'];
            $data['HDTBLBGCOLOR'] = $pageLayout['pl_hdtblbgcolor'];
            $data['HDTBLTXTCOLOR'] = $pageLayout['pl_hdtbltxtcolor'];
            $data['HDFONTTYPE'] = $pageLayout['pl_hdfonttype'];
            $data['BUTTONBGCOLOR'] = $pageLayout['pl_buttonbgcolor'];
            $data['BUTTONTXTCOLOR'] = $pageLayout['pl_buttontxtcolor'];
            $data['FONTTYPE'] = $pageLayout['pl_fonttype'];
            //$data['LOGO'] = $pageLayout['pl_logo'];
        }

        $data = $this->cleanParameters($data);
        if ($this->getShaIn()) {
            $data['SHASIGN'] = $this->calculateSha($data, $this->getShaIn(), $this->getShaVersion());
        }

        return $data;
    }

    protected function cleanParameters($data): array
    {
        $clean = [];
        foreach ($data as $key => $value) {
            if (!is_null($value) && $value !== false && $value !== '') {
                $clean[strtoupper($key)] = $value;
            }
        }

        return $clean;
    }

    public function calculateSha($data, $shaKey, $shaVersion): string
    {
        ksort($data);
        $shaString = '';
        foreach ($data as $key => $value) {
            $shaString .= sprintf('%s=%s%s', strtoupper($key), $value, $shaKey);
        }

        switch ($shaVersion) {
            case 'sha512':
            case 'sha256':
                $shaString = hash($shaVersion, $shaString);
                break;
            default:
                $shaString = sha1($shaString);
                break;
        }

        return strtoupper($shaString);
    }

    public function sendData($data): PurchaseResponse
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint(): string
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }
}
