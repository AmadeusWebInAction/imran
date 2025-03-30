<?php
if ($param3 = variable('page_parameter3')) {
	$title_r = humanize($param3);
	$sheet = collateNetworkSheets();
	$byName = arrayGroupBy($sheet->rows, $sheet->columns['Name']);
	if (isset($byName[$title_r])) {
		renderItem($byName[$title_r][0], $sheet);
	} else {
		parameterError('TITLE ERROR', [$title_r], false);
	}
}

disk_include(__DIR__ . '/menu.php', ['callingFrom' => 'page', 'limit' => -1]);
