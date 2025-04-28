<?php
DEFINE('NODEPATH', __DIR__);

disk_include_once(NODEPATH . '/data/sheet-loader.php');

$param1 = variable('page_parameter1');

variables([
	'standalone-pages' => ['writing'],
	'no-page-menu' => !!$param1,
	'page-aliases' => ['topic' => 'writing'],
	'nodeSiteName' => 'Imran\'s World',
	'nodeSafeName' => 'imran-ali-namazi',
	'node-2ndLevel-title' => 'Pieces',
	'standalone_parameter_name' => 'topic',
]);

//NOTE: for now, manage sub-theme for entire network here
function node_before_render() {
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
if (!in_array($param1, ['topic', 'writing']))
	variable('regular-node-item', true);

initializeNodeXt('standalone');
