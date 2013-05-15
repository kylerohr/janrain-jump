<?php
ob_start();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>FakeCMS</title>
		{{headerinject}}
	</head>
	<body>
		{{bodyinject}}
	</body>
</html>
<?php
$PAGE = ob_get_clean();
spl_autoload_register(
	function($name){
		require_once str_replace('\\', '/', $name) . '.php';
	});
spl_autoload_extensions('.php');
set_include_path(get_include_path() . PATH_SEPARATOR . '/var/www/raw');

function loadContent($injectableContent) {
	global $PAGE;
	$PAGE = str_replace('{{bodyinject}}', $injectableContent . "{{bodyinject}}", $PAGE);
}

function loadHead($injectableContent) {
	global $PAGE;
	$PAGE = str_replace('{{headerinject}}', $injectableContent . "{{headerinject}}", $PAGE);
}

$configData = array(
	'clientId' => '6ktpqgv775wk7grhs5gc6k26z22khx6t',
	'captureName' => 'byron.dev',
	'engageName' => 'byron-janrain',
	'captureAppId' => '6jreb2yub54ekd3f3a4vymx8wh',
	);

$config = new janrain\jump\CaptureConfig($configData);
$module = new janrain\jump\Capture($config);

foreach ($module->getCssHrefs() as $href) {
	loadHead("<link rel='stylesheet' type='text/css' href='{$href}'/>");
}
loadHead($module->getCss());
foreach ($module->getJsSrcs() as $src) {
	loadHead("<script type='text/javascript' src='{$src}'></script>");
}
loadHead('<script type="text/javascript">');
loadHead($module->getStartHeadJs());
loadHead($module->getSettingsHeadJs());
loadHead($module->getEndHeadJs());
loadHead("</script>");
loadContent("<div>");
loadContent($module->getHtml());
loadContent("</div>");
echo str_replace(array('{{bodyinject}}', '{{headerinject}}'), '', $PAGE);