<?php
$r = standalone_menu_items($callingFrom);

if ($callingFrom == 'header-menu') {
	$r2 = array_slice($r, 0, 5);
	if (count($r) > 5)
		$r2['writing'] = '&raquo; ' . (count($r) - 5) . ' More &hellip;';

	return $r2;
}

standalone_2ndlevel_menu();

contentBox('topics', 'box-like-list container after-content');
h2('List of All Topics');
echo '<ol class="block-links">' . NEWLINE;
menu('/', ['files' => $r, 'this-is-standalone-section' => true, 'no-ul' => true]);
echo '</ol>' . NEWLINES2;
contentBox('end');
