<?php
DEFINE('NODEPATH', __DIR__);

disk_include_once(NODEPATH . '/data/sheet-loader.php');

variables([
	'standalone-pages' => ['writing'],
	'no-page-menu' => !!variable('page_parameter1'),
	'page-aliases' => ['topic' => 'writing'],
	'nodeSafeName' => 'imran-ali-namazi',
	'node-2ndLevel-title' => 'Pieces',
	'standalone_parameter_name' => 'topic',
]);

//NOTE: for now, manage sub-theme for entire network here
function site_before_render() {
	//addStyle('site', 'site-static');
	$node = variable('node');
	//if ($node == 'index') setSubTheme('');
}

function enrichNodeThemeVars($vars, $what) {
	//if (SITENAME == 'web' && variable('node') == 'index')
		//$vars['optional-slider'] = getSnippet('home-slider');

	return $vars;
}

runExtension('node-xt');
initializeNodeXt('standalone');
