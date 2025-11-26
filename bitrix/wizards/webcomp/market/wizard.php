<? if ( ! defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
} ?>
<?
require_once("scripts/wizard.php");

class SelectSiteStep extends CSelectSiteWizardStep
{
    function InitStep()
    {
        parent::InitStep();

        $wizard =& $this->GetWizard();
        $wizard->solutionName = "market";
    }
}


class SelectTemplateStep extends CSelectTemplateWizardStep
{
}

class SelectThemeStep extends CSelectThemeWizardStep
{

}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $wizard->solutionName = "market";
        parent::InitStep();

        $templateID = $wizard->GetVar("templateID");
        $themeID = $wizard->GetVar($templateID."_themeID");


        $wizard->SetDefaultVars(
            [
                "companyName"         => GetMessage("WIZ_COMPANY_NAME_DEF"),
                "companyPhone"        => GetMessage("WIZ_COMPANY_PHONE_DEF"),
                "companyEmail"        => GetMessage("WIZ_COMPANY_EMAIL_DEF"),
                "companyAddress"      => GetMessage("WIZ_COMPANY_ADDRESS_DEF"),
                "companyTimeWork"     => GetMessage("WIZ_COMPANY_TIME_WORK_DEF"),
                "companySlogan"       => GetMessage("WIZ_COMPANY_SLOGAN_DEF"),
                "companyCopy"         => GetMessage("WIZ_COMPANY_COPY_DEF"),
                "siteMetaDescription" => GetMessage("wiz_site_desc"),
                "siteMetaKeywords"    => GetMessage("wiz_keywords"),
            ]
        );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_NAME").'</div>';
        $this->content .= $this->ShowInputField("text", "companyName", [
                "id"          => "company-name",
                "class"       => "wizard-field",
                "placeholder" => GetMessage("WIZ_COMPANY_NAME"),
            ]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_PHONE").'</div>';
        $this->content .= $this->ShowInputField("text", "companyPhone",
                ["id" => "company-phone", "class" => "wizard-field",]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_EMAIL").'</div>';
        $this->content .= $this->ShowInputField("text", "companyEmail",
                ["id" => "company-email", "class" => "wizard-field"]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_ADDRESS").'</div>';
        $this->content .= $this->ShowInputField("text", "companyAddress",
                ["id" => "company-email", "class" => "wizard-field"]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_TIME_WORK").'</div>';
        $this->content .= $this->ShowInputField("textarea", "companyTimeWork", [
                "id"    => "company-email",
                "class" => "wizard-field",
                "rows"  => "3",
            ]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_SLOGAN").'</div>';
        $this->content .= $this->ShowInputField("textarea", "companySlogan", [
                "id"    => "company-slogan",
                "class" => "wizard-field",
                "rows"  => "3",
            ]).'</div>';

        $this->content .= '<div class="wizard-upload-img-block"><div class="wizard-catalog-title">'
            .GetMessage("WIZ_COMPANY_COPY").'</div>';
        $this->content .= $this->ShowInputField("text", "companyCopy",
                ["id" => "company-copy", "class" => "wizard-field",]).'</div>';

        $firstStep = COption::GetOptionString("main",
            "wizard_first".substr($wizard->GetID(), 7)."_"
            .$wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));



        $this->content .= '
		<div  id="bx_metadata">
			<div class="wizard-input-form-block">
				<div class="wizard-metadata-title">'.GetMessage("wiz_meta_data")
            .'</div>
				<div class="wizard-upload-img-block">
					<label for="siteMetaDescription" class="wizard-input-title">'
            .GetMessage("wiz_meta_description").'</label>
					'.$this->ShowInputField("textarea", "siteMetaDescription", [
                "id"    => "siteMetaDescription",
                "class" => "wizard-field",
                "rows"  => "3",
            ]).'
				</div>';
        $this->content .= '
				<div class="wizard-upload-img-block">
					<label for="siteMetaKeywords" class="wizard-input-title">'
            .GetMessage("wiz_meta_keywords").'</label><br>
					'.$this->ShowInputField('text', 'siteMetaKeywords',
                ["id" => "siteMetaKeywords", "class" => "wizard-field"]).'
				</div>
			</div>
		</div>';

        $formName = $wizard->GetFormName();
        $installCaption = $this->GetNextCaption();
        $nextCaption = GetMessage("NEXT_BUTTON");
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
//		COption::SetOptionString("main", "wizard_site_logo", $res, "", $wizard->GetVar("siteID")); 
    }
}

class DataInstallStep extends CDataInstallWizardStep
{
    function CorrectServices(&$arServices)
    {
        $wizard =& $this->GetWizard();

    }
}

class FinishStep extends CFinishWizardStep
{
}

?>