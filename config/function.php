<?php
function getSearchDate($params,$term, $search_date_mode="",$first="")
{
	$array['date'] = getDateData();
	$params['search_date_mode'] = IsEmpty($search_date_mode) == true ? ValueNullCheck($params['search_date_mode']) : $search_date_mode;
	if(IsEmpty($params['search_date_mode']) == true)
	{
		if(empty($params['search_date_start_time']) == true)
		{
			$array['start_time'] = array('hour' => 0,'min' => 0,'sec' => 0);
		}
		else
		{
			$array['start_time'] = array('hour' => $params['search_date_start_time'],'min' => 0,'sec' => 0);
		}

		if(empty($params['search_date_end_time']) == true)
		{
			$array['end_time'] = array('hour' => 23,'min' => 59,'sec' => 59);
		}
		else
		{
			$array['end_time'] = array('hour' => $params['search_date_end_time'],'min' => 59,'sec' => 59);
		}

		if(empty($params['search_date_start']) == true) 
		{
			$return['start'] = getForSearchTermTargetDate($term);
			$temp['start'] = explode("-",$return['start']);
			$return['start_timestamp'] = mktime($array['start_time']['hour'],$array['start_time']['min'],$array['start_time']['sec'],$temp['start']['1'],$temp['start']['2'],$temp['start']['0']);
		}
		else 
		{
			$return['start'] = $params['search_date_start'];
			$temp['start'] = explode("-",$return['start']);
			$return['start_timestamp'] = mktime($array['start_time']['hour'],$array['start_time']['min'],$array['start_time']['sec'],$temp['start']['1'],$temp['start']['2'],$temp['start']['0']);
		}

		if(empty($params['search_date_end']) == true) 
		{
			$return['end'] = date("Y-m-d",time());
			$temp['end'] = explode("-",$return['end']);
			$return['end_timestamp'] = mktime($array['end_time']['hour'],$array['end_time']['min'],$array['end_time']['sec'],$temp['end']['1'],$temp['end']['2'],$temp['end']['0']);
		}
		else 
		{
			$return['end'] = $params['search_date_end'];
			$temp['end'] = explode("-",$return['end']);
			$return['end_timestamp'] = mktime($array['end_time']['hour'],$array['end_time']['min'],$array['end_time']['sec'],$temp['end']['1'],$temp['end']['2'],$temp['end']['0']);
		}
	}

	$temp['style'] = " id='search_date_start_time' class='list'";
	$temp['start_time'] = empty($params['search_date_start_time']) == true ? "0" : $params['search_date_start_time'];
	$return['start_time'] = getSelectBox("search_date_start_time",$array['date']['hour2'],$temp['start_time'],$temp['style']);
	$temp['style'] = " id='search_date_end_time' class='list'";
	$temp['end_time'] = empty($params['search_date_end_time']) == true ? "23" : $params['search_date_end_time'];
	$return['end_time'] = getSelectBox("search_date_end_time",$array['date']['hour2'],$temp['end_time'],$temp['style']);

	##		초기화를 위한 페이지 최초 로딩시 날짜 조건들
	if($first != "all")
	{
		$return['or_start'] = getForSearchTermTargetDate($term);
		$return['or_end'] = date("Y-m-d",time());
		$return['or_mode'] = "";
	}
	else
	{
		$return['or_start'] = "";
		$return['or_end'] = "";
		$return['or_mode'] = "all";
	}
	return $return;
}


function getForSearchTermTargetDate($term)
{
	$target_date = time() - (86400 * $term);
	$ret = date("Y-m-d",$target_date);
	return $ret;
}


function getDateData()
{
	//생일
	$return['birth_year']['0000'] = Configure::read('column.choice');
	for($i=1945;$i<=date("Y",time());$i++)
		$return['birth_year'][$i] = $i;

	//연도
	$return['year']['0000'] = Configure::read('column.choice');
	for($i=date("Y",time());$i<date("Y",time()) + 3;$i++)
		$return['year'][$i] = $i;

	//통계년도
//		$return['statistic_year']['no'] = Configure::read('column.choice');
	for($i=2010;$i<=date("Y",time());$i++)
		$return['statistic_year'][$i] = $i.Configure::read('column.year');

	//월
	$return['month']['00'] = Configure::read('column.choice');
	for($i=1;$i<13;$i++)
		$return['month'][$i] = sprintf("%02d",$i);

	//통계월
	for($i=1;$i<13;$i++)
		$return['statistic_month'][$i] = sprintf("%02d",$i).Configure::read('column.month');

	//일
	$return['day']['00'] = Configure::read('column.choice');
	for($i=1;$i<32;$i++)
		$return['day'][$i] = sprintf("%02d",$i);

	//시간
	$return['hour']['00'] = Configure::read('column.choice');
	for($i=0;$i<24;$i++)
	{
		$return['hour2'][$i] = sprintf("%02d",$i).Configure::read('column.hour');
		$return['hour'][$i] = sprintf("%02d",$i);
	}

	//분
	$return['min']['00'] = Configure::read('column.choice');
	for($i=0;$i<55;$i=$i+5)
		$return['min'][$i] = sprintf("%02d",$i);

	return $return;
}



function getSelectBox($name,$data_array,$select_key="",$add_tag="")
{
	unset($return);
	$return = "<select name=\"".$name."\" ".$add_tag.">";

	if(is_array($data_array))
	{
		while($data = each($data_array))
		{
			if(strval($data['key']) == $select_key)
			{
				$return.= "<option value='".$data['key']."' selected>".$data['value']."</option>";
			}
			else
			{
				$return.= "<option value='".$data['key']."'>".$data['value']."</option>\\n";
			}
		}
	}
	$return.= "</select>";

	return $return;
}




function _getMicroTime($mode="normal") 
{
	$tmp_time = explode(" ", microtime());
	if($mode == "normal")
	{
		$return = $tmp_time[0] + $tmp_time[1];
	}
	else if($mode == "order")
	{
		/*
		2010/11/02에 새코드로 바꾸기로 함.. 매장아이디_주문일_현재시간
		$data_time = $tmp_time[1].substr($tmp_time[0],0,5);
		$return = str_replace("0.","_",$data_time);
		2011/03/08에 새코드로 바꾸기로 함.. 일련번호(5000부터)_고객번호_매장아이디
		그러나 고객번호는 고객이 주문시 저장이 되는것으로 일련번호만 보낸다.
		*/
		$return = date("Ymd_His");
	}
	else if($mode == "id")
	{
		$data_time = $tmp_time[1].substr($tmp_time[0],0,3);
		$return = str_replace("0.","_",$data_time);
	}
	else if($mode == "new_style_code")
	{
		$data_time = date("Ymd_His",$tmp_time[1]).substr($tmp_time[0],0,5);
		$return = str_replace("0.","_",$data_time);
	}

	return $return;
}






function customEregi($chk_str,$or_str)
{
	unset($temp);
	$temp['pt'] = strpos($or_str,$chk_str);
	if(is_int($temp['pt']) == true) return true;
	else return false;
}




function uniqueStr($cut=15) 
{
	/*
	$return = crypt(mt_rand());
	$return = str_replace("$","",$return);
	$return = str_replace(".","",$return);
	$return = str_replace(",","",$return);
	$return = str_replace("/","",$return);
	$return = strtoupper(substr($return,3,15));
	*/
	$tmp_time = explode(" ", microtime());
	$data_time = $tmp_time['1'].$tmp_time['0'];
	$return = str_replace("0.","_",$data_time);

	return $return;
}
function uploadFile($upfile,$filename,$filepath) 
{
	if(is_uploaded_file($upfile) || is_file($upfile)) 
	{
		if(!copy($upfile,"$filepath/$filename"))
		{
			return false;
			exit;
		}
		chmod("$filepath/$filename",0777);
		return true;
	} 
	else 
	{
		return false;
		exit;
	}

	if(!copy($upfile,"$filepath/$filename"))
	{
		return false;
		exit;
	}
	chmod("$filepath/$filename",0777);
	return true;
}
function fileCopy($upfile,$filepath,$or_name=false) 
{
	$temp['ext'] = explode(".",$upfile);
	$temp['pt'] = sizeof($temp['ext']) - 1;
	$ext = $temp['ext'][$temp['pt']];
	if($or_name)
	{
		$temp['file_name'] = explode("/",$upfile);
		$temp['pt'] = sizeof($temp['file_name']) - 1;
		$file_name = str_replace(".".$ext,"",$temp['file_name'][$temp['pt']]);
	}
	else
	{
		$file_name = uniqueStr();
	}
	$return['convert_name'] = $file_name.".".$ext;

	if(is_file($upfile)) 
	{
		if(!copy($upfile,$filepath."/".$return['convert_name']))
		{
			return false;
			exit;
		}
		chmod($filepath."/".$return['convert_name'],0777);
		return $return;
	} 
	else 
	{
		return false;
		exit;
	}
}
function makeDir($path) 
{
	if(!is_dir($path))
	{
		if(!mkdir($path))
		{
			echo "FAIL";
		}
		else
		{
			chmod($path,0777);
		}
	}
	else
	{
		chmod($path,0777);
	}
}
function getFileExt($filename) 
{
	$filename = trim($filename);
	return strtolower(substr($filename,-(strlen(strrchr($filename,"."))-1)));
}
function getFileInfo($file)
{
	$tmp = getfileExt($file['name']);
	$temp['tmp_name'] = $file['tmp_name'];
	$temp['name'] = $file['name'];
	$temp['size'] = $file['size'];
	$temp['type'] = $file['type'];
	$temp['ext'] = $tmp;

	if(IsEmpty($temp['tmp_name']) == false)
	{
		$tmp_info = getimagesize($temp['tmp_name']);
		if($tmp_info['2'] > 0 && $tmp_info['2'] < 19)
		{
			$temp['img_width'] = $tmp_info['0'];
			$temp['img_height'] = $tmp_info['1'];
			$temp['img_type'] = $tmp_info['2'];
			$temp['mime'] = $tmp_info['3'];

			if($temp['img_width'] > 0 && $temp['img_height'] > 0)
			{
				$temp['rate'] = round(1 / (round(($temp['img_width'] / $temp['img_height']),2)), 2);
			}
		}
		else $temp['img_type'] = 0;
	}
	return $temp;
}
function getImageFileInfo($file,$or_name)
{
	if(is_file($file))
	{
		$temp_info = getimagesize($file);
		$temp_ext = explode(".",$file);
		$file_now['img_width'] = $temp_info['0'];
		$file_now['img_height'] = $temp_info['1'];
		$file_now['img_type'] = $temp_info['2'];
		$file_now['mime'] = $temp_info['3'];
		$file_now['tmp_name'] = $file;
		$file_now['ext'] = $temp_ext['1'];
		$file_now['name'] = $or_name;
		if($file_now['img_width'] > 0 && $file_now['img_height'] > 0)
		{
			$file_now['rate'] = round(1 / (round(($file_now['img_width'] / $file_now['img_height']),2)), 2);
		}
	}
	return $file_now;
}
function getThumbnailName($name="",$pt="1",$path="")
{
	if(IsEmpty($name) == false)
	{
		$temp = explode(".",$name);
		$thumb = $temp['0']."_thumbnail".$pt.".jpg";
		if(is_file(APP.'plugins'.DS."board".DS."webroot".DS."files".DS.str_replace("board/files/","",$path).$thumb) == true) $thumb = $temp['0']."_thumbnail".$pt.".jpg";
		else $thumb = "";
	}
	else $thumb = "";

	return $thumb;
}


function uploadFileProcess($file_now,$upfolder,$thumbnail_chk="yes", $use_orig_name=false)
{
	unset($temp);

	###	이름변경된 원본파일 설정
	if( $use_orig_name )
	{
		$temp['convert_name'] = str_replace(".JPG",".jpg",$file_now['name']);
		$temp['convert_name'] = str_replace(".GIF",".gif",$temp['convert_name']);
		$temp['convert_name'] = str_replace(".PNG",".png",$temp['convert_name']);
		$return['convert_name'] = $temp['convert_name'];
		$temp['file_name'] = explode(".",$return['convert_name']);
		$temp['thumb_parent_name'] = $temp['file_name']['0'];
	}
	else
	{
		$temp['file_name'] = uniqueStr();
		$return['convert_name'] = $temp['file_name'].".".$file_now['ext'];
		$temp['thumb_parent_name'] = $temp['file_name'];
	}

	if($thumbnail_chk == "yes")
	{
		$return['thumbnail_name1'] = $temp['thumb_parent_name']."_thumbnail1.".$file_now['ext'];
		$return['thumbnail_name2'] = $temp['thumb_parent_name']."_thumbnail2.".$file_now['ext'];
		$return['thumbnail_name3'] = $temp['thumb_parent_name']."_thumbnail3.".$file_now['ext'];

		#	파일등록..
		$thumbnailSize = array('0' => 50, '1' => 150);
		$deleteChk = array('0' => "no", '1' => "no");
		$useName = array(
			'0' => $return['thumbnail_name1'], 
			'1' => $return['thumbnail_name2']
		);

		uploadFile($file_now['tmp_name'],$return['convert_name'],$upfolder);

		for($i=0;$i<sizeof($useName);$i++)
		{
			imgThumbnail($file_now,$thumbnailSize[$i],$useName[$i],$upfolder,$deleteChk[$i]);
			@chmod($upfolder."/".$useName[$i],0777);
		}
	}

	else
	{
		#	파일등록..
		uploadFile($file_now['tmp_name'],$return['convert_name'],$upfolder);
	}
	return $return;
}


function imgThumbnail($file_now,$max_size,$img_name,$upfolder,$img_del_chk="")
{
	if($file_now['img_width'] > $max_size)
	{
		if($img_del_chk == "yes")
		{
			if(is_file($upfolder."/".$img_name)) unlink($upfolder."/".$img_name);
		}

		if($file_now['rate'] > 1) 
			$max_size = $max_size * (1/$file_now['rate']);
		else 
			$max_size = $max_size;
		$max_width = $max_size;
		$max_height = round($max_size * $file_now['rate']);


		if($file_now['img_type'] == 1)
			$_fullimage = ImageCreateFromGif($file_now['tmp_name']);
		elseif($file_now['img_type'] == 2)
			$_fullimage = ImageCreateFromJPEG($file_now['tmp_name']);
		elseif($file_now['img_type'] == 3)
			$_fullimage = ImageCreateFromPNG($file_now['tmp_name']);
		else return 0;

		if($file_now['img_type'] == 1) 
			$dst_img = @imagecreate($max_width, $max_height);
		else
		{
			$dst_img = @imagecreatetruecolor($max_width, $max_height);
		}

		$bgc = @ImageColorAllocate($dst_img, 255, 255, 255);
		@ImageFilledRectangle($dst_img, 0, 0, $max_width, $max_height, $bgc); 
		@ImageCopyResampled($dst_img, $_fullimage, 0, 0, 0, 0, $max_width, $max_height, $file_now['img_width'],$file_now['img_height']);

		if($file_now['img_type'] == 1) 
		{
			@ImageInterlace($dst_img);
			@ImageGif($dst_img, $upfolder."/".$img_name);
		}
		elseif($file_now['img_type'] == 2)
		{
			@ImageInterlace($dst_img);
			@ImageJPEG($dst_img, $upfolder."/".$img_name);
		}
		elseif($file_now['img_type'] == 3)
		{
			@ImagePNG($dst_img, $upfolder."/".$img_name);
		}

		@ImageDestroy($dst_img);
		ImageDestroy($_fullimage);
	}
	else
	{
		if($img_del_chk == "yes")
		{
			if(is_file($upfolder."/".$img_name)) unlink($upfolder."/".$img_name);
		}

		uploadFile($file_now['tmp_name'],$img_name,$upfolder);
	}
}





function deleteFile($file) 
{
	if(file_exists($file)) 
	{
		unlink($file);
		return true;
	}
	else 
	{
		return false;
	}
}










function showMsg($str)
{
	header("Content-Type: text/html; charset=UTF-8");
	echo "
		<html><head><title></title></head><body>
		<script>
		alert('".$str."');
		</script>
		</body></html>
	";
}





function errMsgToBack($str)
{
	header("Content-Type: text/html; charset=UTF-8");
	echo "
		<html><head><title></title></head><body>
		<script>
		alert('".$str."');
		history.back(1);
		</script>
		</body></html>
	";
	exit;
}









function move_location($url)
{
//	if(!customEregi(Configure::read('PROTOCOL.default'),$url)) $url = Configure::read('PROTOCOL.default').$url;
	echo "
		<html><head><title></title></head><body>
		<script>
		location.href='".$url."';
		</script>
		</body></html>
	";
	exit;
	exit;
}





function errMsgToEnd($str)
{
	echo "
		<html><head><title></title></head><body>
		<script>
		alert('".$str."');
		window.close();
		</script>
		</body></html>
	";
	exit;
}





function windowsClose()
{
	echo "
		<html><head><title></title></head><body>
		<script>
		window.close();
		</script>
		</body></html>
	";
	exit;
}









function getVirtualNum($oPaging,$oName)
{
	return $oPaging[$oName]['count'] - $oPaging[$oName]['current'] * ($oPaging[$oName]['page'] - 1);
}





function getFileType($ext) 
{
	if(customEregi($ext, "html|htm|shtml"))
	{
		$ftype = "htm.gif";
	}
	elseif(customEregi($ext, "exe|msi|bat"))
	{
		$ftype = "exe.gif";
	}
	elseif(customEregi($ext, "dll|ini|sys"))            $ftype = "dll.gif";
	elseif(customEregi($ext, "zip|gz|tar|ace"))         $ftype = "zip.gif";
//		elseif(customEregi($ext, "jpeg|jpg"))               $ftype = "jpg.gif";
//		elseif(customEregi($ext, "gif"))                    $ftype = "gif.gif";
	elseif(customEregi($ext, "jpeg|jpg|gif|png"))               $ftype = "img.gif";
	elseif(customEregi($ext, "ra|ram|rm"))              $ftype = "ra.gif";
	elseif(customEregi($ext, "mpeg|asf|mpg|avi|asx"))   $ftype = "mpeg.gif";
	elseif(customEregi($ext, "cgi|pl|pm"))              $ftype = "cgi.gif";
	elseif(customEregi($ext, "php|php3|php4"))          $ftype = "php3.gif";
	elseif(customEregi($ext, "js|css|class"))           $ftype = "js.gif";
	elseif(customEregi($ext, "asp|jsp"))                $ftype = "asp.gif";
	elseif(customEregi($ext, "swf|fla|swi"))            $ftype = "swf.gif";
	elseif(customEregi($ext, "txt"))                    $ftype = "text.gif";
	elseif(customEregi($ext, "hwp"))                    $ftype = "hwp.gif";
	elseif(customEregi($ext, "ppt"))                    $ftype = "ppt.gif";
	elseif(customEregi($ext, "xls"))                    $ftype = "xls.gif";
	else  $ftype = "unknown.gif";

	return $ftype;
}








function viewDate($type="A",$timestamp)
{
	unset($temp,$return);

	//A  년(같은년일 경우 표시안함) 월 일 오전/오후 시간 분 8월 6일 오후 9:17  (월/일/시간/분을 한자리로 표현)
	if($type == "A")
	{
		if(date("Y",$timestamp) == date("Y",time()))
		{
			$temp['date'] = date("n월 j일 A g:i",$timestamp);
		}
		else
		{
			$temp['date'] = date("Y년 n월 j일 A g:i",$timestamp);
		}
		$temp['date'] = str_replace("AM","오전",$temp['date']);
		$return = str_replace("PM","오후",$temp['date']);
	}
	
	else if($type == "Z")
	{
		$temp['t_date'] = explode("-",$timestamp);//여기서는 0000-00-00으로 받는다.
		$return = $temp['t_date']['0']."년 ".$temp['t_date']['1']."월 ".$temp['t_date']['2']."일";
	}

	return $return;
}






function endDay($year,$month)
{
	for($i=28;$i<=32;$i++) 
	{
		if(!checkdate($month,$i,$year)) 
		{ 
			return $i-1;
			break;
		}
	}
}








function cut_string($src, $limit,$suffix="..")
{

	/*
	if(strlen($str)<$len)
	{
		return $str; //지정된 길이보다 문자열이 작으면 그냥 리턴
	}

	for($i=0; $i<$len-1; $i++)
	{
		if(ord($str[$i])>127)
		{
			$i++;  //한글일 경우 2byte 이동 
		}
	}

	$str = substr($str,0,$i);
	return $str.$tail;
	*/

	$len = strlen($src); 
	if ( $len <= $limit ) return $src; 

	$idx = $limit; 
	while ( $idx > 0 ) 
	{ 
		$char_value = ord($src[$idx]); 
		if ( $char_value < 0x80 || ( $char_value & 0x40 ) ) break; 
		$idx--; 
	} 
	return ( 0 == $idx ) ? ( "" . $suffix ) : ( substr($src, 0, $idx) . $suffix ); 
}




function ValueIsCheck(&$params,$value,$mode="equal")
{
	if($mode == "not")
	{
		if(IsEmpty($params) == false)
		{
			if($params != $value) $return = true;
			else $return = false;
		}
		else $return = false;
	}
	else if($mode == "equal")
	{
		if(IsEmpty($params) == false)
		{
			if($params == $value) $return = true;
			else $return = false;
		}
		else $return = false;
	}
	else if($mode == "nullAndValue")
	{
		if(IsEmpty($params) == false)
		{
			if($params == $value) $return = true;
			else $return = false;
		}
		else $return = true;
	}

	return $return;
}




function ValueNullCheck(&$value)
{
	return IsEmpty($value) == true ? null : $value;
}




function IsEmpty(&$s)
{
	if(isset($s) == true)
	{
		if(is_array($s) == true) return false;
		else if(is_string($s) || is_double($s)) 
		{
			$s = trim($s);
			if(strlen($s) > 0) return false;
			else return true;
		}
	}
	else return true;
}




function ViewParameterParsing($params="",$add="")
{
	$return = "";
	$check = "";
	$temp = "page_per_data_ea,search_date_start,search_date_end,search_date_mode,search_method,search_keyword";
	if(IsEmpty($add) == false) $temp.= ",".$add;
	$base = explode(",",$temp);
	
	for($i=0;$i<sizeof($base);$i++)
	{
		if(IsEmpty($params[$base[$i]]) == false)
		{
			if($base[$i] == "search_keyword") $params[$base[$i]] = urlencode($params[$base[$i]]);
			$return.= $check.$base[$i]."=".$params[$base[$i]];
			$check = "&";
		}
	}

	return $return;
}





function ControllerParameterParsing($params="",$page_params="",$add="")
{
	$temp = "page_per_data_ea,search_date_start,search_date_end,search_date_mode,search_method,search_keyword";
	if(IsEmpty($add) == false) $temp.= ",".$add;
	$base = explode(",",$temp);
	$params['data_page'] = IsEmpty($page_params['named']['page']) == true ? 1 : $page_params['named']['page'];

	for($i=0;$i<sizeof($base);$i++)
	{
		if(IsEmpty($page_params['url'][$base[$i]]) == false)
		{
			$params[$base[$i]] = $page_params['url'][$base[$i]];
		}
	}
	return $params;
}





function getFindCode($oData,$mName,$cName)
{
	unset($temp,$chk,$return);

	$temp['size'] = sizeof($oData);
	if($temp['size'] > 0 )
	{
		$chk['code'] = "";
		$temp['find_code'] = "";
		for($i=0;$i<$temp['size'];$i++)
		{
			if(empty($chk['code'][$oData[$i][$mName][$cName]]) == true && isset($oData[$i][$mName][$cName]) == true)
			{
				$chk['code'][$oData[$i][$mName][$cName]] = $oData[$i][$mName][$cName];
				$temp['find_code'][] = $oData[$i][$mName][$cName];
			}
		}
	}
	else $temp['find_code'] = "";

	$return = arrayEaCheck($temp['find_code']);
/*
	if($temp['find_code'] == "") $temp['find_code'] = array("NO_DATA");
	if(sizeof($temp['find_code']) <= 1)
	{
		$temp['temp_code'] = $temp['find_code']['0'];
		unset($temp['find_code']);
		$temp['find_code'] = $temp['temp_code'];
	}
	return $temp['find_code'];
	*/
	return $return;
}
function arrayEaCheck($arr)
{
	if(empty($arr) == true) $return = "NOT_FIND_CODE";
	else if(is_array($arr) == true)
	{
		if(sizeof($arr) == 1) 
		{
			$pt = 0;
			while($value = each($arr))
			{
				if($pt == 0) {$return = $arr[$value['key']];$pt++;}
				if($pt > 0) break;
			}
		}
		else $return = $arr;
	}
	else $return = "NOT_FIND_CODE";

	return $return;
}



function AddFileImageCheck($file,$return_type="url")
{
	$check = "";
	for($i=0;$i<sizeof($file);$i++)
	{
		$data = $file[$i];
		if(IsEmpty(getThumbnailName($data['column']['convert'],"1",$data['column']['path'])) == false)
		{
			if($return_type == "url")
			{
				$check = $data['column']['root'].$data['column']['path'].getThumbnailName($data['column']['convert'],"1",$data['column']['path']);
			}
			else if($return_type == "path")
			{
				$check = base64_encode(APP.'plugins'.DS."board".DS."webroot".DS."files".DS.str_replace("board/files/","",$data['column']['path']).getThumbnailName($data['column']['convert'],"1",$data['column']['path']));
			}
			break;
		}
	}
	return $check;
}


function appleMobileCheck()
{
	$iPod = stripos($_SERVER['HTTP_USER_AGENT'],"iPod"); 
	$iPhone = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone"); 
	$iPad = stripos($_SERVER['HTTP_USER_AGENT'],"iPad"); 
	if($iPod != false || $iPhone != false || $iPad != false)
	{
		return true;
	}
	else return false;
}
?>