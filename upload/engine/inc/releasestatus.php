<?php
/*
=====================================================
 Файл: inc/releasestatus.php
-----------------------------------------------------
 Назначение: Создаём конфиг для модуля статуса релизов и добавляем и редактируем их
=====================================================
 Автор: Maxim Harder
-----------------------------------------------------
 Сайт: http://maxim-harder.de
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) ) die( "You are a fucking faggot!" );

# Функции для работы с панелью == START.
function showRow($title = "", $description = "", $field = "") {
	echo "<tr><td class=\"col-xs-10 col-sm-6 col-md-7\"><h6>{$title}</h6><span class=\"note large\">{$description}</span></td><td class=\"col-xs-2 col-md-5 settingstd\">{$field}</td></tr>";
}
function showInput($name, $value) {
	return "<input type=text style=\"width: 400px;text-align: center;\" name=\"save_con[{$name}]\" value=\"{$value}\" size=20>";
}
function makeCheckBox($name, $selected, $flag = true) {
	$selected = $selected ? "checked" : "";
	if($flag)
		echo "<input class=\"iButton-icons-tab\" type=\"checkbox\" name=\"$name\" value=\"1\" {$selected}>";
	else
		return "<input class=\"iButton-icons-tab\" type=\"checkbox\" name=\"$name\" value=\"1\" {$selected}>";
}
function makeDropDown($value, $name, $selected) {
	$output = "<select class=\"uniform\" name=\"save_con[$name]\">\r\n";
	foreach ( $value as $values => $description ) {
		$output .= "<option value=\"{$values}\"";
		if( $selected == $values ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}
function makeDropDownF($value, $name, $selected) {
	$output = "<select class=\"uniform\" name=\"$name\">\r\n";
	foreach ( $value as $values => $description ) {
		$output .= "<option value=\"{$values}\"";
		if( $selected == $values ) {
			$output .= " selected ";
		}
		$output .= ">$description</option>\n";
	}
	$output .= "</select>";
	return $output;
}
function showForm($title = "", $field = "") {
	echo "<div class=\"form-group\"><label class=\"control-label col-xs-2\">{$title}</label><div class=\"col-xs-10\">{$field}</div></div>";
}
# Функции для работы с панелью == END.
include ENGINE_DIR . "/data/releasestatus.php";
switch($action):
	case "config":
		echoheader( "<i class=\"icon-wrench\"></i> Статус релиза", "Настройки модуля" );
echo <<<HTML
		<form action="$PHP_SELF?mod=releasestatus&action=save&for=config" method="post">
			<div id="setting" class="box">
				<div class="box-header"><div class="title">Настройки модуля</div></div>
				<div class="box-content">
					<table class="table table-normal">
HTML;
						showRow("Вывод значения на главной в случае серии", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Серия.", showInput("series_name", $release_cfg['series_name']));
						showRow("Вывод значения на главной в случае фильма", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Полнометражка.", showInput("movie_name", $release_cfg['movie_name']));
						showRow("Вывод значения на главной для статуса перевода", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Перевод.", showInput("translate_name", $release_cfg['translate_name']));
						showRow("Вывод значения на главной для статуса озвучки", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Озвучка.", showInput("dub_name", $release_cfg['dub_name']));
						showRow("Вывод значения на главной для статуса монтажа", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Монтаж.", showInput("montage_name", $release_cfg['montage_name']));
						showRow("Вывод значения на главной для статуса проверки(постобработки)", "Это замещение будет выводиться в блоке статуса релиза. К примеру: Проверка.", showInput("post_name", $release_cfg['post_name']));
						showRow("Количество релизов на главной", "С этим параметром будет выводиться заданное количество релизов на главной. К Примеру: 5.", showInput("limit", $release_cfg['limit']));
						showRow("Вывод постера", "Откуда выводим постер из базы данных. В случае короткой и полной новости - берётся первое изображение!", makeDropDown( array ("1" => "Из полной новости", "2" => " Из короткой новости",  "3" => "Из доп. поля" ), "poster_get", $release_cfg['poster_get']));
						showRow("Поля предназначенное для постера", "Если постер берётся из дополнительного поля, то вводим сюда название этого поля. В противном случае оставляем пустым. К примеру: poster", showInput("poster_field", $release_cfg['poster_field']));
						showRow("Постер расположен на сервере?", "Относится к настройке выше. Если изображения для постера находятся где-то извне (Image-Hosting), то выключаем эту настройку.", makeCheckBox( "save_con[poster_field_ext]", ($release_cfg['poster_field_ext'] == 1) ? true : false, false ));
						showRow("Вывод названия", "Откуда выводим название из базы данных.", makeDropDown( array ("1" => "Из заголовка", "2" => "Из доп. поля" ), "name_get", $release_cfg['name_get']));
						showRow("Поля предназначенное для названия", "Если названиe берётся из дополнительного поля, то вводим сюда название этого поля. В противном случае оставляем пустым. К примеру: runame", showInput("name_field", $release_cfg['name_field']));
						showRow("Выводить в процентах?", "Статус релизы будет выводиться либо в процентах, либо в словах, типа озвучено или не озвучено.", makeCheckBox( "save_con[show_percent]", ($release_cfg['show_percent'] == 1) ? true : false, false ));
						showRow("Суффикс после числа процентов", "Будет выводить после числа символ или т.п. К примеру: % или /100.", showInput("percent_sign", $release_cfg['percent_sign']));
						showRow("Выводить пустые или нуллевые значения?", "Будет либо выводить, либо скрывать все, кроме статуса \"перевод\" значения.", makeCheckBox( "save_con[show_null]", ($release_cfg['show_null'] == 1) ? true : false, false ));
echo <<<HTML
					</table>
				</div>
				<div class="box-footer padded">
					<input type="submit" class="btn btn-lg btn-green" value="{$lang['user_save']}">
					<a href="$PHP_SELF?mod=releasestatus" class="btn btn-lg btn-red" style="color:white">Назад</a>
				</div>
			</div>
			<in
		</form>
		<div class="text-center">Copyright 2016 &copy; <a href="http://maxim-harder.de/" target="_blank"><b>Maxim Harder</b></a>. All rights reserved.</div>
HTML;
		echofooter();
	break;
	case "add":
		$id = (int)$_REQUEST['rid'];
		if($id)
		{
			include_once ENGINE_DIR . '/classes/parse.class.php';
			$parse = new ParseFilter( Array (), Array (), 1, 1 );
			
			$row = $db->super_query("SELECT * FROM ". PREFIX . "_releasestatus as r JOIN ". PREFIX . "_post as p ON r.news_id=p.id WHERE rid = '$id'");
				
			if($release_cfg['name_get'] == "1") {
				$imja = $row['title'];
			} else {
				$xfields = xfieldsload();
				$xfieldsdata = xfieldsdataload ($row['xfields']);
				$field = $release_cfg["name_field"];
				$name = $xfieldsdata[$field];
			}
			
			$_hidden_input = "<input type=\"hidden\" name=\"rid\" value=".$id." />\n<input type=\"hidden\" name=\"for\" value=\"update\" />";
			$_delete = "<a href=\"$PHP_SELF?mod=releasestatus&amp;action=delete&amp;id={$id}\" class=\"btn btn-red\">Удалить</a>";
			echoheader( "<i class=\"icon-edit\"></i> Статус релиза", "Редактирование релиза : {$name}");
		}
		else
		{
			echoheader( "<i class=\"icon-file-alt\"></i> Статус релиза", "Добавления нового релиза");
			$_hidden_input = "<input type=\"hidden\" name=\"for\" value=\"insert\" />";
		}
echo <<<HTML
			<div class="box">
				<div class="box-content">
					<form method="post" class="form-horizontal" enctype="multipart/form-data" name="doadd" id="feed" action="">
						<div class="tab-content">
							<div class="tab-pane active" id="tabhome">
								<div class="row box-section">
									<div class="form-group">
										<label class="control-label col-xs-2">ID Релиза</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="news_id" id="news_id" value="{$row[news_id]}"><br><br>
											<input type="text" style="width:99%;max-width:437px;" id="search_news_input" name="news" value="" placeholder="Поиск новостей"><br>
											<span id="related_news"></span>
										</div>
									</div>
HTML;
									showForm("Тип релиза", makeDropDownF( array ("1" => "Сериал", "2" => "Фильм" ), "type", $row[type]) );
echo <<<HTML
									<div class="form-group">
										<label class="control-label col-xs-2">Номер серии:</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="number" id="number" value="{$row['number']}">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-2">Стадия перевода:</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="translate" id="translate" value="{$row['translate']}">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-2">Стадия озвучки:</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="dub" id="dub" value="{$row['dub']}">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-2">Стадия монтажа:</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="montage" id="montage" value="{$row['montage']}">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-2">Стадия проверки:</label>
										<div class="col-xs-10">
											<input type="text" style="width:99%;max-width:437px;" name="post" id="post" value="{$row['post']}">
										</div>
									</div>
									<div class="form-group">
										<label class="control-label col-xs-2">Показывать на главной:</label>
										<div class="col-xs-10">
HTML;
										makeCheckBox( "view", ($row['view'] == 1) ? true : false );
echo <<< HTML
										</div>
									</div>
									<button class="btn btn-green">Сохранить</button>
									{$_delete}
								</div>
							</div>
						</div>
						<input type="hidden" name="action" value="save" />
						{$_hidden_input}
						<input type="hidden" name="mod" value="releasestatus" />
					</form>
				</div>
			</div>
			<script>
				$(function(){
					$('#search_news_input').attr('autocomplete', 'off');
					var search_timer = false;
					var search_text = '';
					function EndSearch()
					{
						$('#search_news_input').keyup(function() {
							$('#related_news').text('');
							var text = $(this).val();
							if (search_text != text)
							{
								clearInterval(search_timer);
								search_timer = setInterval(function() { StartSearch(text); }, 600);
							}
						});
					}

					function StartSearch(text)
					{
						clearInterval(search_timer);
						$.post("engine/ajax/search_news.php", {news : text}, function(data){
							if(data){
								$('#related_news').text('');
								$('#related_news').append(data);
							}
						});
						search_text = text;
					}
					EndSearch();

					$('body').on('click', '[data-click*=news_]', function() {
						var id = $(this).attr('data-id');
						var arrs = $('[name=news_id]').val().split(',');
						if (arrs.join(',').indexOf(id)>=0)
						{
							alert('Вы уже выбрали эту новость!');
						}
						else
						{
							var news_id = $('[name=news_id]').val();
							if(news_id == "" )
							{
								$('[name=news_id]').val(news_id + id);
								$("#findrelated_" + id).remove();
							}							
							else
							{
								$('[name=news_id]').val(news_id + ',' + id);
								$("#findrelated_" + id).remove();
							}
						}
					});
				});
			</script>
HTML;
		echofooter();
	break;
	case "save":
		$_for = $_REQUEST['for'];
		if( in_array($_for, array("insert", "update")) )
		{
			$id = intval($_REQUEST['rid']);
			if($id)
			{
				$row = $db->super_query( "SELECT * FROM " . PREFIX . "_releasestatus WHERE rid='$id'" );
			}
			
			include_once ENGINE_DIR . '/classes/parse.class.php';
			$parse = new ParseFilter( Array (), Array (), 1, 1 );
			$view 		= !empty($_REQUEST['view']) ? intval($_REQUEST['view']) : false;
			$news_id 	= !empty($_REQUEST['news_id']) ? trim(strip_tags(stripslashes($_REQUEST['news_id']))):false;
			$type 		= !empty($_REQUEST['type']) ? trim(strip_tags(stripslashes($_REQUEST['type']))):false;
			$number 		= !empty($_REQUEST['number']) ? trim(strip_tags(stripslashes($_REQUEST['number']))):false;
			$translate	= !empty($_REQUEST['translate']) ? trim(strip_tags(stripslashes($_REQUEST['translate']))):false;
			$dub 		= !empty($_REQUEST['dub']) ? trim(strip_tags(stripslashes($_REQUEST['dub']))):false;
			$montage 	= !empty($_REQUEST['montage']) ? trim(strip_tags(stripslashes($_REQUEST['montage']))):false;
			$post 		= !empty($_REQUEST['post']) ? trim(strip_tags(stripslashes($_REQUEST['post']))):false;
			if(!$type || !$news_id)
			{
				msg("info", "Все плохо!", "<font color=\"red\"><b>Вы не выбрали новость, либо забыли указать тип релиза!</b></font>", "$PHP_SELF?mod=releasestatus");
				die();
			}
			$news_id = implode(array_reverse(preg_split('//u', $news_id)));
			$news_id = preg_replace('/(\b[\pL0-9]++\b)(?=.*?\1)/siu', '', $news_id);
			$news_id = implode(array_reverse(preg_split('//u', $news_id)));
			$news_id = explode(',', $news_id);
			$new_arr = array();
			for ($i = 0; $i < count($news_id); $i++)
				if(!empty($news_id[$i]))
					$new_arr[] = $news_id[$i];
			$news_id = implode(',', $new_arr);
			
			if($id)
			{
				if($db->query( "UPDATE " . PREFIX . "_releasestatus SET view='$view', news_id='$news_id', type='$type', number='$number', translate='$translate', dub='$dub', montage='$montage', post='$post' WHERE rid='$id'"))
				{
					clear_cache();
					msg("info", "Все отлично!", "<font color=\"green\"><b>Информация обновлена!</b></font>", "$PHP_SELF?mod=releasestatus");
				}
				else
				{
					msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
					die();
				}
			}
			else
			{
				if($db->query( "INSERT INTO " . PREFIX . "_releasestatus ( news_id, type, number, translate, dub, montage, post, view) VALUES('$news_id', '$type', '$number', '$translate', '$dub', '$montage', '$post', '$view')" ))
				{
					clear_cache();
				msg("info", "Все отлично!", "<font color=\"green\"><b>Релиз добавлен!</b></font>", "$PHP_SELF?mod=releasestatus");
				}	
				else
				{
					msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
					die();
				}
			}
		}
		else if( $_for == "config" )
		{
			$save_con = $_REQUEST['save_con'];
			$handler = fopen(ENGINE_DIR . '/data/releasestatus.php', "w");
			
			fwrite($handler, "<?PHP \n\n//ReleaseStatus 1.0 by Maxim Harder\n\n\$release_cfg = array (\n\n'version' => \"1.0\",\n\n");
			foreach ($save_con as $name => $value) {
				fwrite($handler, "'{$name}' => \"{$value}\",\n\n");
			}
			fwrite($handler, ");\n\n?>");
			fclose($handler);
			
			clear_cache();
			msg("info", $lang['opt_sysok'], "<b>{$lang['opt_sysok_1']}</b>", "$PHP_SELF?mod=releasestatus");
		}
		else if( $_for == "delete" )
		{
			$id = intval($_REQUEST['rid']);
			if(!$id)
			{
				msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
				return;
			}
			if($db->query("DELETE FROM " . PREFIX . "_releasestatus WHERE rid='$id'"))
			{
				clear_cache();
				msg("info", "Все отлично!", "<font color=\"green\"><b>Релиз удален!</b></font>", "$PHP_SELF?mod=releasestatus");
			}
			else
			{
				msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
				die();
			}
		}
		else if( $_for == "view_del" ) {
			$id = intval($_REQUEST['rid']);
			$set = intval($_REQUEST['view']);
			if(!$id){
				msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
				return;
			}
			if($id){
				if($db->query("UPDATE ". PREFIX . "_releasestatus SET view='0' WHERE rid='$id'")){ 
					clear_cache();
					msg("info", "Все отлично!", "<font color=\"green\"><b>Информация обновлена! Релиз снят с главной!</b></font>", "$PHP_SELF?mod=releasestatus");
				} else {
					msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
					die();
				}
			}
		}
		else if( $_for == "view_add" ) {
			$id = intval($_REQUEST['rid']);
			$set = intval($_REQUEST['view']);
			if(!$id){
				msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
				return;
			}
			if($id){
				if($db->query("UPDATE ". PREFIX . "_releasestatus SET view='1' WHERE rid='$id'")){ 
					clear_cache();
					msg("info", "Все отлично!", "<font color=\"green\"><b>Информация обновлена! Релиз виден на главной!</b></font>", "$PHP_SELF?mod=releasestatus");
				} else {
					msg("info", "Все плохо", "Упс... Что-то не так", "$PHP_SELF?mod=releasestatus");
					die();
				}
			}
		}
	break;
	default :
		echoheader( "<i class=\"icon-list-alt\"></i> Статус релизов", "Панель модуля RealeaseStatus");
		
		$rowed = $db->super_query("SELECT COUNT(*) as count FROM " . PREFIX . "_releasestatus");
		$all_count_news = $rowed['count'];
		
		if($_REQUEST['start_from']) {
				$start_from = intval( $_REQUEST['start_from'] );
			} else {
				if (!isset($cstart) or ($cstart<1)) {
					$cstart = 1;
					$start_from = 0;
				} else {
					$start_from = ($cstart-1)*$news_per_page;
				}
			}
			if ( intval($_REQUEST['news_per_page']) > 0 ) $news_per_page = intval( $_REQUEST['news_per_page'] ); else $news_per_page = 25;

			$i = $start_from;
			$sql = $db->query("SELECT * FROM " . PREFIX . "_releasestatus as r JOIN " . PREFIX . "_post as p ON r.news_id=p.id LIMIT $start_from,$news_per_page ");
			while( $row = $db->get_row($sql) ) {
				$i++;
				$id = $row['rid'];
				$news_id = $row['news_id'];
				
				if($release_cfg['name_get'] == "1") {
					$imja = $row['title'];
				} else {
					$xfields = xfieldsload();
					$xfieldsdata = xfieldsdataload ($row['xfields']);
					$field = $release_cfg["name_field"];
					$imja = $xfieldsdata[$field];
				}
				$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['news_id'];
				if($row['view'] == 1) {
					$view = "<font color=\"cc0000\" style=\"font-weight:bold;\"><i class=\"icon-eye-close\"></i> Снять с главной</font>";
					$vsuf = "_del";
				} else {
					$view = "<font color=\"green\" style=\"font-weight:bold;\"><i class=\"icon-eye-open\"></i> Показать на главной</font>";
					$vsuf = "_add";
				}
				
				$list .= "<tr><td align=\"center\">{$id}</td><td align=\"center\"><a href=\"{$full_link}\" target=\"_blank\">{$imja} (ID: {$news_id})</a></td><td align=\"center\"><a href=\"$PHP_SELF?mod=releasestatus&amp;action=add&amp;rid={$id}\"><i class=\"icon-pencil\"></i> Редактировать</a>&nbsp;|&nbsp;<a href=\"$PHP_SELF?mod=releasestatus&amp;action=save&amp;for=delete&amp;rid={$id}\" style=\"color:red;\"><i class=\"icon-trash\"></i> Удалить</a>&nbsp;|&nbsp;<a href=\"$PHP_SELF?mod=releasestatus&amp;action=save&amp;for=view{$vsuf}&amp;rid={$id}\">{$view}</a></div></td></tr>";
				$id = '';
			}
			echo <<< HTML
<div class="box">
	<div class="box-header"><div class="title">Список релизов</div></div>
	<div class="box-content">

		<table class="table table-normal table-hover">
			<thead>
				<tr>
					<td>ID</td>
					<td>Название</td>
					<td>Настройки</td>
				</tr>
			</thead>
			<tbody>
				{$list}
			</tbody>
		</table>

   </div>
   
	<div class="box-footer padded">
		<div class="pull-left"><a href="$PHP_SELF?mod=releasestatus&amp;action=config" class="btn btn-green">Настройки</a></div>
		<div class="pull-right"><a href="$PHP_SELF?mod=releasestatus&amp;action=add" class="btn btn-gold">Добавить</a></div>
	</div>
	
</div>

HTML;
$npp_nav = "";


if( $all_count_news > $news_per_page ) {

	if( $start_from > 0 ) {
		$previous = $start_from - $news_per_page;
		$npp_nav .= "<li><a href=\"$PHP_SELF?mod=releasestatus&amp;start_from=$previous&amp;news_per_page=$news_per_page\"> &lt;&lt; </a></li>";
	}
	
	$enpages_count = @ceil( $all_count_news / $news_per_page );
	$enpages_start_from = 0;
	$enpages = "";

	if( $enpages_count <= 10 ) {
		for($j = 1; $j <= $enpages_count; $j ++) {
			if( $enpages_start_from != $start_from ) {
				$enpages .= "<li><a href=\"$PHP_SELF?mod=releasestatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$j</a></li>";
			} else {
				$enpages .= "<li class=\"active\"><span>$j</span></li>";
			}
			$enpages_start_from += $news_per_page;
		}
		$npp_nav .= $enpages;

	} else {
		$start = 1;
		$end = 10;
		if( $start_from > 0 ) {
			if( ($start_from / $news_per_page) > 4 ) {
				$start = @ceil( $start_from / $news_per_page ) - 3;
				$end = $start + 9;
				if( $end > $enpages_count ) {
					$start = $enpages_count - 10;
					$end = $enpages_count - 1;
				}
				$enpages_start_from = ($start - 1) * $news_per_page;
			}
		}

		if( $start > 2 ) {
			$enpages .= "<li><a href=\"#\">1</a></li> <li><span>...</span></li>";
		}

		for($j = $start; $j <= $end; $j ++) {
			if( $enpages_start_from != $start_from ) {
				$enpages .= "<li><a href=\"$PHP_SELF?mod=releasestatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$j</a></li>";
			} else {
				$enpages .= "<li class=\"active\"><span>$j</span></li>";
			}
			$enpages_start_from += $news_per_page;
		}
		$enpages_start_from = ($enpages_count - 1) * $news_per_page;
		$enpages .= "<li><span>...</span></li><li><a href=\"$PHP_SELF?mod=releasestatus&amp;start_from=$enpages_start_from&amp;news_per_page=$news_per_page\">$enpages_count</a></li>";
		$npp_nav .= $enpages;
	}

	if( $all_count_news > $i ) {
		$how_next = $all_count_news - $i;
		if( $how_next > $news_per_page ) {
			$how_next = $news_per_page;
		}
		$npp_nav .= "<li><a href=\"$PHP_SELF?mod=releasestatus&amp;start_from=$i&amp;news_per_page=$news_per_page\"> &gt;&gt; </a></li>";
	}
	$npp_nav = "<ul class=\"pagination pagination-sm\">".$npp_nav."</ul>";
}

// pagination
echo <<< HTML
<div class="box-footer padded">
	<div class="pull-left">{$npp_nav}</div>
</div>
HTML;
echofooter();
	break;
endswitch;
?>