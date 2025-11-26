<? if($SHOW): ?>

<?
    if(file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $MODULE . "/include/_license.txt")) {
        $time = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $MODULE . "/include/_license.txt", date("d-m-Y H:i:s"));
        $time = strtotime($time ."+30 day");
        if($time > time())
            return;
    }

?>

<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client.php"); ?>
<?
if (CUpdateClient::Lock()) {
    $arLicense = [];
    $str_error = "error message";
    if ($arUpdateList = CUpdateClient::GetUpdatesList($str_error, "ru")) {
        $arLicense["SITE"] = $_SERVER["SERVER_NAME"];
        $arLicense["KEY"] = CUpdateClient::GetLicenseKey();
        $arLicense["LICENSE"] = current($arUpdateList["CLIENT"])["@"]["LICENSE"];
        $arLicense["LAST_UPDATE"] = COption::GetOptionString("main", "update_system_update", "-");
        $arLicense["DATE_FROM"] = current($arUpdateList["CLIENT"])["@"]["DATE_FROM"];
        $arLicense["COMPANY"] = current($arUpdateList["CLIENT"])["@"]["NAME"];
    }

    // подгужаем с нашего сайта контент, для того чтобы можно было если что поменять просто
    $arrContextOptions = [
        "ssl" => [
            "verify_peer"      => false,
            "verify_peer_name" => false,
        ],
    ];

    $content = file_get_contents("https://web-komp.ru/mc/license/index.php?".http_build_query($arLicense), false,
        stream_context_create($arrContextOptions));

    if($content) {
        file_put_contents($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $MODULE . "/include/_license.txt", date("d-m-Y H:i:s"));
    }
}
?>
<? endif ?>
