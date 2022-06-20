# Barclaycard ePDQ eCommerce Payment Gateway Plugin for Craft CMS and Commerce 4

This plugin provides the ability for Craft Commerce 4 to process payments using the Barclaycard ePDQ e-Commerce payment solution.

**This plugin does not support Barclaycard ePDQ DirectLink at this time. Support for this payment method is not planned.** 

## Requirements

This plugin requires Craft CMS 4 or later and Craft Commerce 4 or above.

## Installation

**We recommend that you use the Craft Plugin Store to install this plugin as doing so will ensure correct installation. If you have installed this plugin using the Craft Plugin Store then please skip to step 3.**

To install the plugin using the command line, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require welfordmedia/barclaycard-epdq-gateway

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Barclaycard ePDQ eCommerce Gateway.

## Barclays ePDQ Gateway Overview

This plugin provides the ability for Craft Commerce 4 to process payments using the Barclaycard ePDQ e-Commerce payment solution.

**This plugin does not support Barclaycard ePDQ DirectLink at this time. Support for this payment method is not planned.**

## Configuring Barclays ePDQ Gateway

Before using this gateway, you must set up a new gateway within the Craft Commerce gateway settings. During configuration, you will need to input your merchant details as specified on the settings page. Make sure you have these details to hand.

## Using Barclays ePDQ Gateway

Using the payment gateway is simple. The following checkout code is provided as an **example only** and represents the bare minimum that your form should include when submitting to the payment gateway.

**Important:** This example assumes that you have already captured and stored the users billing information as per the Craft Commerce 2 documentation in a previous checkout step. You MUST capture the customers billing information for transactions to be processed successfully.

        <form method="POST">
            {{ csrfInput() }}
            <input type="hidden" name="action" value="commerce/payments/pay"/>
            {{ redirectInput('/checkout/success') }}
            <input type="hidden" name="cancelUrl" value="{{ '/checkout'|hash }}"/>
            <input class="btn btn-lg" type="submit" value="Continue to Payment" />
        </form>
