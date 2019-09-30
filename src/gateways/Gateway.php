<?php
namespace welfordmedia\barclaysepdqgateway\gateways;

use Craft;
use craft\commerce\models\payments\BasePaymentForm;
use craft\commerce\models\Transaction;
use craft\commerce\omnipay\base\OffsiteGateway;
use craft\helpers\UrlHelper;
use Omnipay\Common\AbstractGateway;
use welfordmedia\barclaysepdqgateway\omni\Gateway as BarlcaysGateway;
use craft\commerce\base\RequestResponseInterface;
use yii\base\NotSupportedException;


class Gateway extends OffsiteGateway {

    public $clientId;
    public $language = 'en_US';
    public $returnUrl;
    public $declineUrl;
    public $cancelUrl;
    public $exceptionUrl;
    public $shaIn;
    public $shaOut;
    public $callbackMethod;
    public $pl_title;
    public $pl_fonttype;
    public $pl_bgcolor;
    public $pl_txtcolor;
    public $pl_tblbgcolor;
    public $pl_tbltxtcolor;
    public $pl_hdtblbgcolor;
    public $pl_hdtbltxtcolor;
    public $pl_hdfonttype;
    public $pl_buttonbgcolor;
    public $pl_buttontxtcolor;
    public $pageLayout;

    public static function displayName(): string
    {
        return Craft::t('commerce', 'Barclaycard ePDQ eCommerce');
    }

    public function completePurchase(Transaction $transaction): RequestResponseInterface {
        if (!$this->supportsCompletePurchase()) {
            throw new NotSupportedException(Craft::t('commerce', 'Completing purchase is not supported by this gateway'));
        }
        $request = $this->createRequest($transaction);
        $request['transactionReference'] = $transaction->reference;
        $completeRequest = $this->prepareCompletePurchaseRequest($request);
        return $this->performRequest($completeRequest, $transaction);
    }

    public function supportsWebhooks(): bool
    {
        return false;
    }

    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('barclaycard-epdq-gateway/gatewaySettings', ['gateway' => $this]);
    }

    public function getPaymentTypeOptions(): array
    {
        return [
            'purchase' => Craft::t('commerce', 'Purchase (Authorize and Capture Immediately)')
        ];
    }

    protected function createGateway(): AbstractGateway
    {
        /** @var BarlcaysGateway $gateway */
        $gateway = static::createOmnipayGateway($this->getGatewayClassName());
        $gateway->setClientId($this->clientId);
        $gateway->setLanguage($this->language);
        $gateway->setReturnUrl($this->returnUrl);
        $gateway->setDeclineUrl($this->declineUrl);
        $gateway->setCancelUrl($this->cancelUrl);
        $gateway->setExceptionUrl($this->exceptionUrl);
        $gateway->setPageLayout([
            'pl_title' => $this->pl_title,
            'pl_bgcolor' => $this->pl_bgcolor,
            'pl_txtcolor' => $this->pl_txtcolor,
            'pl_tblbgcolor' => $this->pl_tblbgcolor,
            'pl_tbltxtcolor' => $this->pl_tbltxtcolor,
            'pl_hdtblbgcolor' => $this->pl_hdtblbgcolor,
            'pl_hdtbltxtcolor' => $this->pl_hdtbltxtcolor,
            'pl_hdfonttype' => $this->pl_hdfonttype,
            'pl_buttonbgcolor' => $this->pl_buttonbgcolor,
            'pl_buttontxtcolor' => $this->pl_buttontxtcolor,
            'pl_fonttype' => $this->pl_fonttype,
            //'pl_logo' => $this->pl_logo
        ]);
        $gateway->setShaIn($this->shaIn);
        return $gateway;
    }

    protected function getGatewayClassName()
    {
        return '\\'.BarlcaysGateway::class;
    }
}
