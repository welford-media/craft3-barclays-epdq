<?php

namespace welfordmedia\barclaysepdqgateway\omni\Message;

use JetBrains\PhpStorm\Pure;
use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;

class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{

    public function isSuccessful(): bool
    {
        return false;
    }

    public function isRedirect(): bool
    {
        return true;
    }

    public function getRedirectUrl(): ?string
    {
        return $this->getRequest()->getEndpoint();
    }

    public function getRedirectMethod(): string
    {
        return 'POST';
    }

    #[Pure] public function getRedirectData()
    {
        return $this->getData();
    }
}
