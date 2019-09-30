<?php
/**
 * Barclaycard ePDQ eCommerce Gateway plugin for Craft Commerce 2.x
 *
 *
 * @link      https://welfordmedia.co.uk
 * @copyright Copyright (c) 2019 Welford Media
 */

namespace welfordmedia\barclaysepdqgateway;


use Craft;
use craft\base\Plugin;
use craft\commerce\services\Gateways;
use craft\events\RegisterComponentTypesEvent;


use welfordmedia\barclaysepdqgateway\gateways\Gateway;
use yii\base\Event;

/**
 * Class BarclaysEpdqGateway
 *
 * @author    Welford Media
 * @package   BarclaysEpdqGateway
 * @since     1
 *
 */
class BarclaysEpdqGateway extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var BarclaysEpdqGateway
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(Gateways::class, Gateways::EVENT_REGISTER_GATEWAY_TYPES,  function(RegisterComponentTypesEvent $event) {
            $event->types[] = Gateway::class;
        });
    }

    // Protected Methods
    // =========================================================================

}
