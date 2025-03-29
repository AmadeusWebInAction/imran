<?php
//TODO: HIGH make an extension
if (!function_exists('my_menu_items')) { function my_menu_items($callingFrom) {
	$items = textToList(disk_file_get_contents(__DIR__ . '/_items.txt'));
	$r = []; $prefix = $callingFrom != 'section-check' ? 'imran/topic/' : '';
	foreach ($items as $ix => $item)
		$r[$prefix . urlize($item)] = ($ix + 1) . '. ' . $item;

	return $r;
} }

$r = my_menu_items($callingFrom);

if ($callingFrom == 'section-check')
	return $r;

if ($callingFrom == 'header-menu') {
	$r2 = array_slice($r, 0, 5);
	if (count($r) > 5)
		$r2['writing'] = '&raquo; ' . (count($r) - 5) . ' More &hellip;';

	return $r2;
}

if (!function_exists('my_piece_siblings')) { function my_piece_siblings($sheet, $param2) {
	$byName = arrayGroupBy($sheet->rows, 'Name');
	$name = humanize($param2);
	$topic = isset($byName[$name]) ? $byName[$name][0][$sheet->columns['Topic']] : false;
	variable('thisTopic', $topic ? $topic : 'Topic "' . $param2 . '" not found!');
	return $topic ? $sheet->group[$topic] : [];
} }

$param2 = variable('page_parameter2');
if (($param1 = variable('page_parameter1')) && ($param1 == 'writing' || $param1 == 'topic') && $param2) {
	$sheet = getSheet(__DIR__ . '/data/imran.tsv', 'Topic');
	$items = $param1 == 'all-topics' ? $sheet->rows
		: ($param1 == 'topic' ? $sheet->group[humanize($param2)] : my_piece_siblings($sheet, $param2));
	$title = $param2 == 'all' ? 'All' : ($param1 == 'topic' ? humanize($param2) : variable('thisTopic'));

	contentBox('pieces', 'box-like-list container');
	h2('Pieces in "' . $title . ':"');
	echo '<ol>' . NEWLINE;
	foreach ($items as $item)
		echo '<li>' . makeLink($sheet->getValue($item, 'SNo') . '. ' . $sheet->getValue($item, 'Name'),
			'https://imran.yieldmore.org/' . urlize($sheet->getValue($item, 'Name')) . '/', EXTERNALLINK) .
			NEWLINE . $sheet->getValue($item, 'Description') . '</li>' . NEWLINE;

	echo '</ol>' . NEWLINES2;
	contentBox('end');
}

contentBox('topics', 'box-like-list container after-content');
h2('List of All Topics');
echo '<ol class="block-links">' . NEWLINE;
menu('/', ['files' => $r, 'this-is-standalone-section' => true, 'no-ul' => true]);
echo '</ol>' . NEWLINES2;
contentBox('end');
