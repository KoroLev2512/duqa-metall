<?php
require_once($_SERVER['DOCUMENT_ROOT']. "/bitrix/modules/main/include/prolog_before.php");
use Webcomp\Market\Settings;

if(!isset($GLOBALS["WEBCOMP"]["SETTINGS"])) {
    $GLOBALS["WEBCOMP"]["SETTINGS"] = Settings::GetGlobalSettings();
}

header('Content-Type: application/json');


$secretKey = $GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_RECAPTCHA_SECRET_CODE"];
// post request to server
$url = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['TOKEN'];
$response = file_get_contents($url);
$responseKeys = json_decode($response, true);

if ($responseKeys["success"] && $responseKeys["score"] > floatval($GLOBALS["WEBCOMP"]["SETTINGS"]["WEBCOMP_RECAPTCHA_SCORE"])) {
    echo json_encode(
        array(
            'success' => true,
            'score' => $responseKeys["score"],
            'token' => $_POST['TOKEN']
        ));
} else {
    echo json_encode(
        array('success' => false,
            'score' => $responseKeys["score"],
            'token' => $_POST['TOKEN']
        ));
}