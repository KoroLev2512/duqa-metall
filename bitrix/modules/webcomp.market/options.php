<?

/*

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\HttpApplication;


$module_id = "webcomp.market";

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
Loc::loadMessages(__FILE__);

Loader::IncludeModule($module_id);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$superAdmin = (isset($request["superAdmin"]) && $request["superAdmin"] === "Y") ? true : false;

//Формируем массив вкладок, каждый элемент массива это новая вкладка
$arTabs = [
	// Первый таб
	[
		"DIV" => "webcomp_tab1",
		"TAB" => Loc::getMessage("WEBCOMP_MARKET_TAB_MAIN_TITLE"),
		"TITLE" => "Основные настройки модуля",
		"OPTIONS" => [
			// Поля (текстарея)
			[
				"field_text",
				"Text block",
				"Значение по умолчанию",
				[
					"textarea", 5, 50
				]
			],
			// Поля (текст)
			[
				"field_line",
				"Line block",
				"",
				[
					"text", 10
				]
			],
			// Разделитель
			"Разделитель опций",
			[
				"note" => "Какое-то стилизованное предупреждение предупреждение",
			],
			// Поля (мультиселект)
			[
				"field_list",
				"List block",
				"",
				[
					"multiselectbox", ["Fvar1" => "Vvar1", "Fvar2" => "Vvar2", "Fvar3" => "Vvar3", "Fvar4" => "Vvar4"]
				]
			],
		]
	],
	// Второй таб
	[
		"DIV" => "webcomp_tab2",
		"TAB" => Loc::getMessage("WEBCOMP_MARKET_TAB_RIGHTS"),
		"TITLE" => Loc::getMessage("WEBCOMP_MARKET_TAB_RIGHTS_TITLE"),
	]
];

// superAdmin
if ($superAdmin) {
	$arSTabs = [
		[
			"DIV" => "webcomp_superAdmin",
			"TAB" => Loc::getMessage("WEBCOMP_MARKET_TAB_SUPER_TITLE"),
			"TITLE" => Loc::getMessage("WEBCOMP_MARKET_TAB_SUPER_SUBTITLE"),
			"OPTIONS" => [
				// Поля (для админа)
				[
					"super_field_text",
					"Скрытые настройки",
					"",
					[
						"text", 10
					],
					"N", // disable field
					"чтото", // Красный текст около поля
					"N" // Требуется ли выбор сайта
				],
				// Еще поля
				['isYouYes','checkbox','Y',["checkbox",0,'title="ага" data="somedata"'],'N','Красный текст','N'],
				['isYouText','text','Текст',["text",20],'N','','N'],
				['isYouPass','password','Пароль',["password",10,'noautocomplete'=>'Y'],'N','пароль','N'],
				['isYouTextarea','textarea','Текстареа',["textarea",5,10],'N','чтокак','N'],
				['isYouSelectbox','selectbox','ko',["selectbox",['lo'=>'po','zo'=>'do','ko'=>'ho','vo'=>'no']]],
				['isYouMultiselectbox','multiselectbox','ko,lo',["multiselectbox",['lo'=>'po','zo'=>'do','ko'=>'ho','vo'=>'no']]],
				['isYouStatictext','statictext','Статичный текст',["statictext"]],
				['isYouStatichtml','statichtml','Статичный <i><b>HTML<b><i>',["statichtml"]],
				['isYouFile','file','Статичный <i><b>HTML<b><i>',["file"]],
			]
		]
	];

	$arTabs = array_merge($arSTabs, $arTabs);
}

// Сохранение в базу
if ($request->isPost() && $request["update"] && check_bitrix_sessid()) {
	foreach ($arTabs as $arTab) {

		foreach ($arTab["OPTIONS"] as $arOption) {

			// Строка с подсказкой, используется для разделения на блоки
			if(!is_array($arOption))
				continue;

			// Уведомление с подсветкой
			if($arOption["note"])
				continue;

			$optionName = current($arOption);
			$optionValue = $request->getPost($optionName);

			Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
		}

	}
}

// Визуальный вывод табов
$tabControl = new CAdminTabControl("tabControl", $arTabs);
?>

<? $tabControl->Begin() ?>

<form method="post" action="<?=$APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($request["mid"])?>&amp;lang=<?=$request["lang"]?>" name="webcomp_market_settings">

	<? foreach ($arTabs as $arTab): ?>
		<? if ($arTab["OPTIONS"]): ?>
			<? $tabControl->BeginNextTab() ?>
			<!-- START -->
			<? __AdmSettingsDrawList($module_id, $arTab["OPTIONS"]) ?>
			<!-- END -->
		<? endif ?>
	<? endforeach ?>

	<? $tabControl->BeginNextTab() ?>

	<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php") ?>

	<? $tabControl->Buttons() ?>

	<input type="submit" name="update" value="Сохранить">
	<input type="reset" name="reset" value="Сбросить">
	<?=bitrix_sessid_post()?>

</form>

<? $tabControl->End() ?>