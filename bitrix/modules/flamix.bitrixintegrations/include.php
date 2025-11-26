<?php
require(__DIR__ . '/libs/vendor/autoload.php');

\Bitrix\Main\Loader::registerAutoLoadClasses(
    'flamix.bitrixintegrations',
    [
        '\Flamix\BitrixIntegrations\Option' => 'lib/Option.php',
        '\Flamix\BitrixIntegrations\Lead' => 'lib/Lead.php',
        '\Flamix\BitrixIntegrations\Email' => 'lib/Email.php',

        '\Flamix\BitrixIntegrations\Application\Event' => 'lib/Application/Event.php',

        '\Flamix\BitrixIntegrations\Form' => 'lib/Form.php',
        '\Flamix\BitrixIntegrations\Form\Event' => 'lib/Form/Event.php',

        '\Flamix\BitrixIntegrations\Order' => 'lib/Order.php',

        '\Flamix\BitrixIntegrations\Application\Request' => 'lib/Application/Request.php',

        '\Flamix\BitrixIntegrations\Order\Event' => 'lib/Order/Event.php',
        '\Flamix\BitrixIntegrations\Order\Status' => 'lib/Order/Status.php',
    ]
);
