<?php
defined('_SECURE_') or die('Forbidden');

function themes_get_menu_tree($menus='') {
	global $core_config;
	$arr_menu = $core_config['menu'];
	if ($menus) {
		$arr_menu = $menus;
	}
	$menu_tree = themes_buildmenu($arr_menu);
	return $menu_tree;
}

function themes_buildmenu($arr_menu) {
	global $core_config;
	$menu = '';
	if ($core_config['module']['themes']) {
		$menu = x_hook($core_config['module']['themes'],'themes_buildmenu',array($arr_menu));
	}
	return $menu;
}

function themes_navbar($num, $nav, $max_nav, $url, $page) {
	global $core_config;
	$url = $url.'&'.$core_config['tmp']['themes_search']['name'].'='.$core_config['tmp']['themes_search']['keyword'];
	$nav_pages = '';
	if ($core_config['module']['themes']) {
		$nav_pages = x_hook($core_config['module']['themes'],'themes_navbar',array($num, $nav, $max_nav, $url, $page));
	}
	return $nav_pages;
}

function themes_nav($count, $url) {
	$ret = false;
	$lines_per_page = 30;
	$max_nav = 10;
	$num = ceil($count / $lines_per_page);
	$nav = ( $_REQUEST['nav'] ? $_REQUEST['nav'] : 1 );
	$page = ( $_REQUEST['page'] ? $_REQUEST['page'] : 1 );
	if ($ret['form'] = themes_navbar($num, $nav, $max_nav, $url, $page)) {
		$ret['limit'] = $lines_per_page;
		$ret['offset'] = ($page - 1) * $lines_per_page;
		$ret['top'] = ($count - ($lines_per_page * ($page - 1))) + 1;
	}
	return $ret;
}

function themes_search($var) {
	global $core_config;
	$ret = false;
	$value = $_REQUEST[$var['name'].'_keyword'];
	$core_config['tmp']['themes_search']['name'] = $var['name'].'_keyword';
	$core_config['tmp']['themes_search']['keyword'] = $value;
	$content = "
		<form action='".$var['url']."' method='post'>
		<table cellpadding='0' cellspacing='0' border='0'><tr>
			<td>"._('Search')."</td>
			<td>:</td>
			<td><input type='text' name='".$var['name']."_keyword' value='".$value."' size='40 maxlength='40'><td></td>
			<td><input type='submit' value='"._('Go')."' class='button'></td>
		</tr></table>
		</form>
	";
	$ret['form'] = $content;
	$ret['keyword'] = $value;
	return $ret;
}

?>