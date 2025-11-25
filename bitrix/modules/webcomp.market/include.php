<?php
# if DEMO mode
// const webcomp_market_DEMO = "Y";

if(!defined('WEBCOMP_MARKET_MODULE_ID')){
    define('WEBCOMP_MARKET_MODULE_ID', 'webcomp.market');
}

Bitrix\Main\Loader::registerAutoloadClasses(
    'webcomp.market',
    [
        'CMarket'                                   => 'classes/general/CMarket.php',
        'CMarketTools'                              => 'classes/general/CMarketTools.php',
        'CMarketView'                               => 'classes/general/CMarketView.php',
        'CMarketForm'                               => 'classes/general/CMarketForm.php',
        'CMarketFormOrder'                          => 'classes/general/CMarketForm.php',
        'CMarketLog'                                => 'classes/general/CMarketLog.php',
        'CMarketCatalog'                            => 'classes/general/CMarketCatalog.php',
        'CMarketEvent'                              => 'classes/general/CMarketEvent.php',
        'Webcomp\\Market\\Main'                     => 'lib/Main.php',
        'Webcomp\\Market\\Tools'                    => 'lib/Tools.php',
        'Webcomp\\Market\\Settings'                 => 'lib/Settings.php',
        'Webcomp\\Market\\Property\\FormString'     => 'lib/Property/FormString.php',
        'Webcomp\\Market\\Property\\FormPhone'      => 'lib/Property/FormPhone.php',
        'Webcomp\\Market\\Property\\FormEmail'      => 'lib/Property/FormEmail.php',
        'Webcomp\\Market\\Property\\FormText'       => 'lib/Property/FormText.php',
        'Webcomp\\Market\\Property\\FormBind'       => 'lib/Property/FormBind.php',
        'Webcomp\\Market\\Property\\FormFile'       => 'lib/Property/FormFile.php',
        'Webcomp\\Market\\Property\\FormRating'     => 'lib/Property/FormRating.php',
        'Webcomp\\Market\\Property\\FormAddress'     => 'lib/Property/FormAddress.php',
        'Webcomp\\Market\\Constants'                => 'lib/Constants.php',

    ]
);