<?php
DEFINE('NODEPATH', __DIR__);

$nodeStaticUrl = variable('local')
	? 'http://localhost/awe/world/lightworkers/imran/'
	: 'https://amadeusweb.world/lightworkers/imran/';

variables([
	'node-static-folder' => NODEPATH . '/assets/',
	'node-static' => $nodeStaticUrl . 'assets/',
	'nodeSafeName' => 'imran-ali-namazi',
]);

function before_render_node($node, $section) {
	$standalones = variableOr('standalone-pages', []);
	$page1 = variable('page_parameter1');
	$page2 = variable('page_parameter2');

	$thisPage = $page1 == 'topic' ? 'writing' : $page1;
	if (!in_array($thisPage, $standalones)) return false;

	if (isInPageItem($thisPage, $page2, $node)) {
		variables([
			'section' => $section,
			'file' => concatSlugs([variable('path'), $section, $node, $thisPage, 'home.php']),
			'is-standalone-section' => true,
			'no-page-menu' => true,
		]);

		return true;
	}

	return false;
}

function isInPageItem($page1, $page2, $node) {
	if ($node == 'topic') return true;
	$file = NODEPATH . '/' . $page1 . '/menu.php';
	$items = disk_include($file, ['callingFrom' => 'section-check']);
	return isset($items[$page2]);
}

//NOTE: for now, manage sub-theme for entire network here
function site_before_render() {
	addStyle('site', 'site-static');
	$node = variable('node');
	//if ($node == 'index') setSubTheme('');
}

function enrichNodeThemeVars($vars, $what) {
	//if (SITENAME == 'web' && variable('node') == 'index')
		//$vars['optional-slider'] = getSnippet('home-slider');

	return $vars;
}

variables([
	'standalone-pages' => ['writing', 'topic'],
	'no-page-menu' => !!variable('page_parameter1'),
]);
