<?php
/*
=====================================================
 Файл: modules/releasestatus.php
-----------------------------------------------------
 Назначение: Выводим информацию на сайт
=====================================================
 Автор: Maxim Harder
-----------------------------------------------------
 Сайт: http://maxim-harder.de
=====================================================
*/
if( !defined( 'DATALIFEENGINE' ) ) return;

include ENGINE_DIR . "/data/releasestatus.php";

$allow_cache = ($config['version_id'] >= '10.2') ? $config['allow_cache'] == '1' : $config['allow_cache'] == "yes";

if (!$config['allow_cache'])
{
	if ($config['version_id'] >= '10.2') 	$config['allow_cache'] = '1'; 
	else 									$config['allow_cache'] = "yes";
	$is_change = true;
}

$release_block = false;
$release_block  = dle_cache("rsb_" . $limit, $config['skin']);

if($release_block === false)
{
	$tpl_rsb = new dle_template();
	$tpl_rsb->dir = TEMPLATE_DIR;
	$sql = $db->query("SELECT * FROM " . PREFIX . "_releasestatus as r JOIN " . PREFIX . "_post as p ON r.news_id=p.id WHERE view=1 ORDER BY rid DESC LIMIT ".$release_cfg['limit']."");
	$tpl_rsb->load_template("/releasestatus/release_block.tpl");
	while( $row = $db->get_row($sql) ) {
		
		//Вывод изображения
		if($release_cfg['poster_get'] == "1") {
			if (stripos ( $tpl_rsb->copy_template, "{image-" ) !== false) {
				$images = array();
				preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $row['short_story'], $media);
				$data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);
			
				foreach($data as $url) {
					$info = pathinfo($url);
					if (isset($info['extension'])) {
						if ($info['filename'] == "spoiler-plus" OR $info['filename'] == "spoiler-plus" ) continue;
						$info['extension'] = strtolower($info['extension']);
						if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) array_push($images, $url);
					}
				}
			
				if ( count($images) ) {
					$i=0;
					foreach($images as $url) {
						$i++;
						$tpl_rsb->copy_template = str_replace( '{image-'.$i.'}', $url, $tpl_rsb->copy_template );
						$tpl_rsb->copy_template = str_replace( '[image-'.$i.']', "", $tpl_rsb->copy_template );
						$tpl_rsb->copy_template = str_replace( '[/image-'.$i.']', "", $tpl_rsb->copy_template );
					}
			
				}

				$tpl_rsb->copy_template = preg_replace( "#\[image-(.+?)\](.+?)\[/image-(.+?)\]#is", "", $tpl_rsb->copy_template );			
				$tpl_rsb->copy_template = preg_replace( "#\\{image-(.+?)\\}#i", "/uploads/noimage.jpg", $tpl_rsb->copy_template );
			
			}
		}
		
		if($release_cfg['poster_get'] == "2") {
			if (stripos ( $tpl_rsb->copy_template, "{image-" ) !== false) {
				$images = array();
				preg_match_all('/(img|src)=("|\')[^"\'>]+/i', $row['full_story'], $media);
				$data=preg_replace('/(img|src)("|\'|="|=\')(.*)/i',"$3",$media[0]);
			
				foreach($data as $url) {
					$info = pathinfo($url);
					if (isset($info['extension'])) {
						if ($info['filename'] == "spoiler-plus" OR $info['filename'] == "spoiler-plus" ) continue;
						$info['extension'] = strtolower($info['extension']);
						if (($info['extension'] == 'jpg') || ($info['extension'] == 'jpeg') || ($info['extension'] == 'gif') || ($info['extension'] == 'png')) array_push($images, $url);
					}
				}
			
				if ( count($images) ) {
					$i=0;
					foreach($images as $url) {
						$i++;
						$tpl_rsb->copy_template = str_replace( '{image-'.$i.'}', $url, $tpl_rsb->copy_template );
						$tpl_rsb->copy_template = str_replace( '[image-'.$i.']', "", $tpl_rsb->copy_template );
						$tpl_rsb->copy_template = str_replace( '[/image-'.$i.']', "", $tpl_rsb->copy_template );
					}
			
				}

				$tpl_rsb->copy_template = preg_replace( "#\[image-(.+?)\](.+?)\[/image-(.+?)\]#is", "", $tpl_rsb->copy_template );			
				$tpl_rsb->copy_template = preg_replace( "#\\{image-(.+?)\\}#i", "/uploads/noimage.jpg", $tpl_rsb->copy_template );
			
			}
		}
		
		if($release_cfg['poster_get'] == "3") {
			$xfields = xfieldsload();
			$xfieldsdata = xfieldsdataload ($row['xfields']);
			$field = $release_cfg["poster_field"];
			$poster = $xfieldsdata[$field];
			if($release_cfg['poster_field_ext'] == "1") {
				$poster_link = $config['http_home_url']."uploads/posts/".$poster;
			} else {
				$poster_link = $poster;
			}
			$tpl_rsb->set( '{poster}', $poster_link );
		}
		
		//Вывод названия
		if($release_cfg['name_get'] == "1") {
			$tpl_rsb->set( '{title}', strip_tags(stripslashes($row['title'])) );
		} else {
			$xfields = xfieldsload();
			$xfieldsdata = xfieldsdataload ($row['xfields']);
			$field = $release_cfg["name_field"];
			$title = $xfieldsdata[$field];
			$tpl_rsb->set( '{title}', strip_tags(stripslashes($title)) );
		}
		
		//Вывод типа релиза
		if($row['type'] == "2") {
			$type = $release_cfg['movie_name'];
			$number = "";
			$tpl_rsb->set( '{type}', $type );
			$tpl_rsb->set( '{number}', $number );
		} else {
			$type = $release_cfg['series_name'];
			$number = $row['number'];
			$tpl_rsb->set( '{type}', $type );
			$tpl_rsb->set( '{number}', ":&nbsp;".$number );
		}
		
		//Вывод статуса
		if($release_cfg['show_null'] == "1") {
			$null = "0";
			$tpl_rsb->set( '[status]', "<var>" );
			$tpl_rsb->set( '[/status]', "</var>" );
			$tpl_rsb->set( '{translate_name}', $release_cfg['translate_name'].":&nbsp;" );
			$tpl_rsb->set( '{dub_name}', $release_cfg['dub_name'].":&nbsp;" );
			$tpl_rsb->set( '{montage_name}', $release_cfg['montage_name'].":&nbsp;" );
			$tpl_rsb->set( '{post_name}', $release_cfg['post_name'].":&nbsp;" );
		} else {
			$null = "";
			$tpl_rsb->set( '[status]', "<var style=\"display:none;\">" );
			$tpl_rsb->set( '[/status]', "</var>" );
			$tpl_rsb->set( '{translate_name}', $release_cfg['translate_name'].":&nbsp;" );
			$tpl_rsb->set( '{dub_name}', "" );
			$tpl_rsb->set( '{montage_name}', "" );
			$tpl_rsb->set( '{post_name}', "" );
		}
		
		if($release_cfg['show_percent'] == "1") {
			if(empty($row['translate']) || $row['translate'] == "0") {
				$tpl_rsb->set( '{translate}', "0" );
			} else {
				$tpl_rsb->set( '{translate}', $row['translate'] );
			}
			if(empty($row['dub']) || $row['dub'] == "0") {
				$tpl_rsb->set( '{dub}', $null );
			} else {
				$tpl_rsb->set( '{dub}', $row['dub'] );
			}
			if(empty($row['montage']) || $row['montage'] == "0") {
				$tpl_rsb->set( '{montage}', $null );
			} else {
				$tpl_rsb->set( '{montage}', $row['montage'] );
			}
			if(empty($row['post']) || $row['post'] == "0") {
				$tpl_rsb->set( '{post}', $null );
			} else {
				$tpl_rsb->set( '{post}', $row['post'] );
			}
			$tpl_rsb->set( '{suffix}', $release_cfg['percent_sign'] );
		} else {
			if($release_cfg['show_null'] == "1") {
				if(empty($row['translate']) || $row['translate'] == "0") {
					$tpl_rsb->set( '{translate}', "Не переведено" );
				}
				if(empty($row['dub']) || $row['dub'] == "0") {
					$tpl_rsb->set( '{dub}', "Не озвучено" );
				}
				if(empty($row['montage']) || $row['montage'] == "0") {
					$tpl_rsb->set( '{montage}', "Не смонтировано" );
				}
				if(empty($row['post']) || $row['post'] == "0") {
					$tpl_rsb->set( '{post}', "Не проверялось" );
				}
			} else {
				if(empty($row['translate']) || $row['translate'] == "0") {
					$tpl_rsb->set( '{translate}', "" );
				}
				if(empty($row['dub']) || $row['dub'] == "0") {
					$tpl_rsb->set( '{dub}', "" );
				}
				if(empty($row['montage']) || $row['montage'] == "0") {
					$tpl_rsb->set( '{montage}', "" );
				}
				if(empty($row['post']) || $row['post'] == "0") {
					$tpl_rsb->set( '{post}', "" );
				}
			}
			if($row['translate'] >= 1 && $row['translate'] <= 99 ) {
				$tpl_rsb->set( '{translate}', "Переводится" );
			}
			if($row['translate'] == 100 ) {
				$tpl_rsb->set( '{translate}', "Переведено" );
			}
			if($row['dub'] >= 1 && $row['dub'] <= 99 ) {
				$tpl_rsb->set( '{dub}', "Озвучивается" );
			}
			if($row['dub'] == 100 ) {
				$tpl_rsb->set( '{dub}', "Озвучено" );
			}
			if($row['montage'] >= 1 && $row['montage'] <= 99 ) {
				$tpl_rsb->set( '{montage}', "Монтируется" );
			}
			if($row['montage'] == 100 ) {
				$tpl_rsb->set( '{montage}', "Смотировано" );
			}
			if($row['post'] >= 1 && $row['post'] <= 99 ) {
				$tpl_rsb->set( '{post}', "Проверяется" );
			}
			if($row['post'] == 100 ) {
				$tpl_rsb->set( '{post}', "Проверено" );
			}
			$tpl_rsb->set( '{suffix}', "" );
		}
		
		//Вывод прогресса
		if($release_cfg['show_percent'] == "1") {
			if(empty($row['translate']) || $row['translate'] == "0") {
				$tr = 0;
			} else {
				$tr = $row['translate'];
			}
			if(empty($row['dub']) || $row['dub'] == "0") {
				$dv = 0;
			} else {
				$dv = $row['dub'];
			}
			if(empty($row['montage']) || $row['montage'] == "0") {
				$mv = 0;
			} else {
				$mv = $row['montage'];
			}
			if(empty($row['post']) || $row['post'] == "0") {
				$pv = 0;
			} else {
				$pv = $row['post'];
			}
			$summe = $tr+$dv+$mv+$pv;
			$prozent = $summe/4;
			$prozid = $row['rid'];
			$progress_funktion = "<div class=\"progress\"><div id=\"prozent-{$prozid}\" class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"{$prozent}\" aria-valuemin=\"0\" aria-valuemax=\"100\" title=\"Релиз готов на: {$prozent}%\" data-original-title=\"Релиз готов на: {$prozent}%\" style=\"width:{$prozent}%;\">{$prozent}%</div></div>";
			$tpl_rsb->set( '{progress}', $progress_funktion );
		} else {
			$tpl_rsb->set( '{progress}', "" );
		}
		
		//Вывод ссылки на новость
		if( $config['allow_alt_url']) {
			if( $config['seo_type'] == 1 OR $config['seo_type'] == 2 ) {
				if( $category_id AND $config['seo_type'] == 2 ) {
					$c_url = get_url( $category_id );    
					$full_link = $config['http_home_url'] . $c_url . "/" . $row['id'] . "-" . $row['alt_name'] . ".html";
				} else {
					$full_link = $config['http_home_url'] . $row['id'] . "-" . $row['alt_name'] . ".html";
				}
			} else {
				$full_link = $config['http_home_url'] . date( 'Y/m/d/', $row['date'] ) . $row['alt_name'] . ".html";
			}
		} else {
			$full_link = $config['http_home_url'] . "index.php?newsid=" . $row['id'];
		}
		
		$tpl_rsb->set( '[link]', "<a href=\"" . $full_link . "\">" );
		$tpl_rsb->set( '[/link]', "</a>" );
		$tpl_rsb->set( '{link}', $full_link );
		$tpl_rsb->set( '{id}', $row['rid'] );
			
		$tpl_rsb->compile('release_block');
	}

	$tpl_rsb->clear();
	$release_block = $tpl_rsb->result['release_block'];
	unset($tpl_rsb->result['release_block']);
	create_cache("rsb_" . $limit, $release_block, $config['skin']);

	if($is_change)
		$config['allow_cache'] = false;
}
echo $release_block;
?>