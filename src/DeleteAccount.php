<?php
/**
 * @author     ByMayo
 * @package    DeleteAccount
 * @since      1.0.0
 * @copyright  Copyright (c) 2018 ByMayo
 */

namespace bymayo\deleteaccount;

use bymayo\deleteaccount\services\DeleteAccountService as DeleteAccountServiceService;
use bymayo\deleteaccount\variables\DeleteAccountVariable;
use bymayo\deleteaccount\models\Settings;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\events\PluginEvent;
use craft\web\UrlManager;
use craft\web\twig\variables\CraftVariable;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

class DeleteAccount extends Plugin
{

    public static $plugin;

    public $schemaVersion = '1.0.0';

    public function init()
    {

        parent::init();
        self::$plugin = $this;

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_SITE_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
                $event->rules['test'] = 'delete-account/default/delete';
            }
        );

        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) {
            }
        );

        Event::on(
            CraftVariable::class,
            CraftVariable::EVENT_INIT,
            function (Event $event) {
                $variable = $event->sender;
                $variable->set('deleteAccount', DeleteAccountVariable::class);
            }
        );

        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_INSTALL_PLUGIN,
            function (PluginEvent $event) {
                if ($event->plugin === $this) {
                }
            }
        );

        Craft::info(
            Craft::t(
                'delete-account',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );

    }

    protected function createSettingsModel()
    {
        return new Settings();
    }

    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'delete-account/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
    
}
