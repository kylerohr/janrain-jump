<?php
namespace janrain\jump;

class Capture implements \janrain\plex\PlexInterface {

	protected $clientId;
	protected $captureName;
	protected $engageName;

	public function __construct(CaptureConfigInterface $config) {
		$this->clientId = $config->get('capture.clientId');
		$this->captureName = $config->get('capture.name');
		$this->engageName = $config->get('engage.name');
		$this->captureId = $config->get('capture.id');
	}

	public function getJsSrcs() {
		return array();//'https://d7v0k4dt27zlp.cloudfront.net/assets/capture_client.js');
	}

	public function getStartHeadJs() {
		$out = "(function(){
			if (typeof window.janrain !== 'object') window.janrain = {};
			if (typeof window.janrain.settings !== 'object') window.janrain.settings = {};
			if (typeof window.janrain.settings.packages !== 'object') window.janrain.settings.packages = [];
			if (typeof window.janrain.settings.capture !== 'object') window.janrain.settings.capture = {};\n";
		return $out;
	}

	public function getSettingsHeadJs() {
		$out = "\n//Start Janrain Settings
			janrain.settings.capture.appId = '{$this->captureId}';
			janrain.settings.capture.clientId = '{$this->clientId}';
			janrain.settings.capture.captureServer = 'https://{$this->captureName}.janraincapture.com';
			janrain.settings.capture.recaptchaPublicKey = '6LeVKb4SAAAAAGv-hg5i6gtiOV4XrLuCDsJOnYoP';
			janrain.settings.packages.push('capture');
			janrain.settings.capture.redirectUri = 'http://raw.lvm/';
			janrain.settings.capture.loadJsUrl = 'd16s8pqtk4uodx.cloudfront.net/{$this->engageName}/load.js';
			janrain.settings.capture.flowName = 'plugins';
			janrain.settings.capture.registerFlow = 'socialRegistration';
			janrain.settings.capture.responseType = 'code';
			janrain.settings.tokenUrl = 'http://raw.lvm/index.php';
			janrain.settings.tokenAction = 'event';
			//End Janrain Settings\n";
		return $out;
	}

	public function getEndHeadJs() {
		$out =
			"function isReady() {janrain.ready=true;}
			if (document.addEventListener) {
				document.addEventListener('DOMContentLoaded', isReady, false);
			} else {
				window.attachEvent('onload', isReady);
			}
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.id = 'janrainAuthWidget';
			e.src = '//d16s8pqtk4uodx.cloudfront.net/{$this->engageName}/load.js';
			var s = document.getElementsByTagName('script')[0];
			s.parentNode.insertBefore(e, s);
			})();
			</script>
			<script type='text/javascript'>
			function janrainCaptureWidgetOnLoad() {
				janrain.events.onCaptureLoginSuccess.addHandler(
					function (result) {
						alert('Success: this is your token ' + result.authorizationCode);
					});
				janrain.capture.ui.start();				
 			}\n";
		return $out;
	}
	public function getCssHrefs() {
		return array(
			'//d3hmp0045zy3cs.cloudfront.net/2.1.10/quilt.css',
			'//d3hmp0045zy3cs.cloudfront.net/2.1.10/widgets.css',
			);
	}
	public function getCss() {
		return '';
	}
	public function getWidgetBody() {
		return
			"<a href='#' class='capture_modal_open'>Sign In</a>
			<div style='display:none;' id='signIn'>
    <div class='capture_header'>
        <h1>Sign Up / Sign In</h1>
    </div>
    <div class='capture_signin'>
        <h2>With your existing account from...</h2>
        {* loginWidget *} <br />
    </div>
    <div class='capture_backgroundColor'>
        <div class='capture_signin'>
            <h2>With a traditional account...</h2>
                {* #userInformationForm *}
                    {* traditionalSignIn_emailAddress *}
                    {* traditionalSignIn_password *}
                    <div class='capture_rightText'>
                        {* traditionalSignIn_signInButton *}{* traditionalSignIn_createButton *}
                    </div>
                {* /userInformationForm *}
        </div>
    </div>
</div>

<div style='display:none;' id='returnSocial'>
    <div class='capture_header'>
        <h1>Sign Up / Sign In</h1>
    </div>
    <div class='capture_signin'>
        <h2>Welcome Back, {* welcomeName *}</h2>
        {* loginWidget *}
        <div class='capture_centerText switchLink'><a href='#' data-cancelcapturereturnexperience='true'>Use another account</a></div>
    </div>
</div>
<div style='display:none;' id='returnTraditional'>
    <div class='capture_header'>
        <h1>Sign Up / Sign In</h1>
    </div>
    <h2 class='capture_centerText'>Welcome back<span id='displayNameData'></span>!</h2>
        <div id='userPhoto' class='inline'>
        </div>
        <div class='inline'>
            <span id='displayNameData'>{* welcomeName *}</span>
        </div>
            <div class='capture_backgroundColor'>
                <div class='capture_signin'>
                {* #userInformationForm *}
                    {* traditionalSignIn_emailAddress *}
                    {* traditionalSignIn_password *}
                    <div class='capture_form_item capture_rightText'>
                        {* traditionalSignIn_signInButton *}
                    </div>
                {* /userInformationForm *}
            </div>
        <div class='capture_centerText switchLink'><a href='#' data-cancelcapturereturnexperience='true'>Use another account</a></div>
    </div>
</div>
<div style='display:none;' id='socialRegistration' class='capture_lrg_footer'>
    <div class='capture_header'>
        <h1>Almost Done!</h1>
    </div>
      <h2>Please confirm the information below before signing in.</h2>
        {* #socialRegistrationForm *}
            {* socialRegistration_emailAddress *}
            {* socialRegistration_displayName *}
            {* socialRegistration_ageVerification *}
            By clicking 'Create Account', you confirm that you accept our
                <a href='#'>terms of service</a> and have read and understand
                <a href='#'>privacy policy</a>.
            <div class='capture_footer'>
                <div class='capture_left'>
                    {* backButton *}
                </div>
                <div class='capture_right'>
                {* socialRegistration_signInButton *}
                </div>
            </div>
        {* /socialRegistrationForm *}
</div>
<div style='display:none;' id='traditionalRegistration'>
    <div class='capture_header'>
        <h1>Almost Done!</h1>
    </div>
        {* #registrationForm *}
            {* traditionalRegistration_emailAddress *}
            {* traditionalRegistration_password *}
            {* traditionalRegistration_passwordConfirm *}
            {* traditionalRegistration_displayName *}
            {* traditionalRegistration_captcha *}
            {* traditionalRegistration_ageVerification *}
            By clicking 'Create Account', you confirm that you accept our
                <a href='#'>terms of service</a> and have read and understand
                <a href='#'>privacy policy</a>.
            <div class='capture_footer'>
                <div class='capture_left'>
                    {* backButton *}
                </div>
                <div class='capture_right'>
                {* createAccountButton *}
                </div>
            </div>
        {* /registrationForm *}
</div>
<div style='display:none;' id='forgotPassword'>
    <div class='capture_header'>
        <h1>Create a new password</h1>
    </div>
    <h2>Don't worry, it happens. We'll send you a link to create a new password.</h2>
    {* #forgotPasswordForm *}
        {* traditionalSignIn_emailAddress *}
        <div class='capture_footer'>
            <div class='capture_left'>
                {* backButton *}
            </div>
            <div class='capture_right'>
                {* forgotPassword_sendButton *}
            </div>
        </div>
    {* /forgotPasswordForm *}
</div>
<div style='display:none;' id='forgotPasswordSuccess'>
    <div class='capture_header'>
        <h1>Create a new password</h1>
    </div>
      <p>We've sent an email with instructions to create a new password. Your existing password has not been changed.</p>
    <div class='capture_footer'>
        <a href='#' onclick='janrain.capture.ui.modal.close()' class='capture_btn capture_primary'>Close</a>
    </div>
</div>
<div style='display:none;' id='mergeAccounts'>
    {* mergeAccounts *}
</div>
<div style='display:none;' id='traditionalAuthenticateMerge'>
    <div class='capture_header'>
        {* backButton *}
        <h1>Sign in to complete account merge</h1>
    </div>
    <div class='capture_signin'>
    {* #tradAuthenticateMergeForm *}
        {* traditionalSignIn_emailAddress *}
        {* mergePassword *}
        <div class='capture_footer'>
            <div class='capture_form_item capture_rightText'>
                {* traditionalSignIn_signInButton *}
            </div>
        </div>
     {* /tradAuthenticateMergeForm *}
    </div>
</div>\n";
	}
}