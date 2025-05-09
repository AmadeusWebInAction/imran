<?php
DEFINE('WEBROOT', dirname(AMADEUSROOT, 1) . '/');
DEFINE('YMNROOT', WEBROOT . 'networks/yieldmore/');

if (!disk_is_dir(YMNROOT))
	die(YMNROOT . ' doesnt exist');

function collateNetworkSheets() {
	$s1 = getSheet(YMNROOT . 'imran/data/sitemap.tsv', 'Topic');
	$s1->values['site'] = 'imran';

	$more = [];
	
	return $s1;
}

function getItemLink($item, $sheet) {
	$external = isset($_GET['external']);
	$base = $external ? 'https://imran.yieldmore.org/'
		: pageUrl('imran/topic/' . variable('page_parameter2'));
	//parameterError('20', $sheet, true, true);
	return makeLink($sheet->getValue($item, 'SNo') . '. ' . $sheet->getValue($item, 'Name'),
			$base . urlize($sheet->getValue($item, 'Name')) . '/', $external ? EXTERNALLINK : false) .
			NEWLINE . $sheet->getValue($item, 'Description');
}

#Work	Collection	SNo	Name	Date	Dedication	Category	RhymeScheme	Description	Topic
function renderItem($item, $sheet) {
	$meta = [];
	foreach(explode('	', 'Work	Collection	SNo	Name	Date	Dedication	Category	RhymeScheme	Description	Topic') as $col)
		$meta[$col] = $sheet->getValue($item, $col);

	$fwe = concatSlugs(['imran', 'works', $meta['Work'], $meta['Collection'], urlize($name = $meta['Name'])]);
	$piece = $fwe . '.txt';

	$show = [
		'Topic' => 'plain',
		'Date' => 'plain',
		'Work' => '%meta%/',
		'Collection' => 'all/collection/%meta%/',
		'Dedication' => 'all/for/%meta%/',
		'Category' => 'all/category/%meta%/',
		'RhymeScheme' => 'plain',
		'Description' => 'plain',
	];

	$info = ['+What' => 'Info'];
	$base = 'https://imran.yieldmore.org/';
	foreach ($show as $col => $what) {
		$val = $meta[$col];
		if ($what !== 'plain')
			$val = makeLink($val, $base . replaceItems($what, ['meta' => urlize($val)], '%'), EXTERNALLINK);
		$info[$col] = $val;
	}

	$mdInfo = []; $mdContent = false;
	if ($hasMD = disk_file_exists($md = YMNROOT . $fwe . '.md')) {
		$seo = read_seo($md);
		$mdContent = disk_file_get_contents($md);
		$mdInfo = [
			'+With AI' => 'More Info',
			'About' => isset($seo['about']) ? $seo['about'] : '(empty)',
			'SEO Description' => isset($seo['description']) ? $seo['description'] : '(empty)',
			'SEO Keywords' => isset($seo['keywords']) ? $seo['keywords'] : '(empty)',
			'AI Author' => isset($seo['meta']['Author']) ? $seo['meta']['Author'] : '(empty)',
			'AI Date' => isset($seo['meta']['Date']) ? $seo['meta']['Date'] : '(empty)',
		];
	}

	runFeature('tables');

	contentBox('piece', 'container');
	echo '<div class="row">' . NEWLINE;

	$divStart = '	<div class="h-100 col-sm-12 col-md-%colspan%">' . NEWLINE;
	
	echo replaceItems($divStart, ['colspan' => 12], '%');
	h2($meta['SNo'] . ' ' . $name);
	echo '<hr>' . NEWLINE;
	echo '</div>' . NEWLINES2; //.col (title)

	echo replaceItems($divStart, ['colspan' => 7], '%');
	renderAny(YMNROOT . $piece);
	echo '	</div>' . NEWLINES2; //.col

	echo replaceItems($divStart, ['colspan' => 5], '%');
	_tableHeadingsOnLeft(['id' => 'piece', 'class' => 'float-md-end all-links-external'], $info);

	if ($mdContent) {
		_tableHeadingsOnLeft(['id' => 'piece', 'class' => 'float-md-end all-links-external'], $mdInfo);
	}

	contentBox('end');
	echo '	</div>' . NEWLINES2; //.col

	echo '</div>' . NEWLINES2; //.row

	if ($mdContent) {
		runFeature('engage');

		contentBox('piece', 'container after-content');
		h2('Deep Dive Into "' . $meta['Name'] . '"');

		_renderEngage('piece', $md, true);
		echo '	</div>' . NEWLINES2; //.col
		contentBox('end');
	}
}
