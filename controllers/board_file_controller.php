<?php
class BoardFileController extends BoardAppController 
{
	var $name = 'BoardFile';
	var $helpers = array('Html', 'Session');
	var $uses = array();



	
	function beforeFilter() 
	{
		$this->Session->start(); //SwfUpload 의 세션정보를 유지하기 위한 코드
		parent::beforeFilter();
		$this->Auth->allow();
	}





	function upload_process() 
	{
		##		파일 처리
		$temp['date'] = date("Ymd");
		$temp['saveFolder'] = APP.'plugins'.DS."board".DS."webroot".DS."files".DS.$temp['date'];
		$parameter = $this -> data['BoardFile'];

		##		폴더 생성
		if(!is_dir($temp['saveFolder'])) makeDir($temp['saveFolder']);

		$temp['file_name'] = "add";
		$temp['file_path'] = "add_path";
		$temp['file_convert'] = "add_convert";
		$temp['file_original'] = "add_original";

		if(isset($parameter[$temp['file_name']]) == true)
		{
			if($parameter[$temp['file_name']]['tmp_name'] != "") $file_info = getFileInfo($parameter[$temp['file_name']]);
			else $file_info = array();
		}

		if(IsEmpty($parameter[$temp['file_name']]['tmp_name']) == false)
		{
			if($file_info['img_type'] > 0 && $parameter['thumb'] == "Y") $temp['fileInfo'] = uploadFileProcess($file_info,$temp['saveFolder'],"yes");
			else $temp['fileInfo'] = uploadFileProcess($file_info,$temp['saveFolder'],"no");
			$parameter['root'] = HOSTHOME.DS;
			$parameter[$temp['file_path']] = "board".DS."files".DS.$temp['date'].DS;
			$parameter[$temp['file_convert']] = $temp['fileInfo']['convert_name'];
			$parameter[$temp['file_original']] = $parameter[$temp['file_name']]['name'];
		}


		$this->layout = 'none';
		$this->set('parameter',$parameter);
	}





	function http_upload_process() 
	{
		##		파일 처리
		$temp['date'] = date("Ymd");
		$temp['saveFolder'] = APP.'plugins'.DS."board".DS."webroot".DS."files".DS.$temp['date'];
		$parameter = $this -> data['BoardFile'];

		if(IsEmpty($parameter['add']) == false)
		{
			if($parameter['add']['error'] > 0)
			{
				$tpl['check'] = false;
			}
			else
			{
				##		폴더 생성
				if(!is_dir($temp['saveFolder'])) makeDir($temp['saveFolder']);

				$temp['file_name'] = "add";
				$temp['file_path'] = "add_path";
				$temp['file_convert'] = "add_convert";
				$temp['file_original'] = "add_original";

				if(isset($parameter[$temp['file_name']]) == true)
				{
					if($parameter[$temp['file_name']]['tmp_name'] != "") $file_info = getFileInfo($parameter[$temp['file_name']]);
					else $file_info = array();
				}

				if(IsEmpty($parameter[$temp['file_name']]['tmp_name']) == false)
				{
					if($file_info['img_type'] > 0 && $parameter['thumb'] == "Y") $temp['fileInfo'] = uploadFileProcess($file_info,$temp['saveFolder'],"yes");
					else $temp['fileInfo'] = uploadFileProcess($file_info,$temp['saveFolder'],"no");
					$parameter['root'] = HOSTHOME.DS;
					$parameter[$temp['file_path']] = "board".DS."files".DS.$temp['date'].DS;
					$parameter[$temp['file_convert']] = $temp['fileInfo']['convert_name'];
					$parameter[$temp['file_original']] = $parameter[$temp['file_name']]['name'];

					$tpl['check'] = true;
				}
			}
		}
		else
		{
			$tpl['check'] = false;
		}

		$this->layout = 'none';
		$this->set('parameter',$parameter);
		$this->set('tpl',$tpl);
	}





	function upload_form()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardFile'],$this -> params,"pt,thumb,file_size");

		$this->set('parameter',$parameter);

		$this->layout = 'popup';
	}




	function image($str="")
	{
		if(IsEmpty($str) == false)
		{
			$path = base64_decode($str);
			if(is_file($path) == true)
			{
				ob_start();
				$img = imagecreatefromjpeg($path);
				imagejpeg($img);
				imagedestroy($img);
				$imagevariable = ob_get_contents();
				ob_end_clean();

				header("Content-type: image/jpeg");
				header("Content-Length: ".strlen($imagevariable));
				echo $imagevariable;
			}
		}

		exit;
	}




	function down($str)
	{
		if(IsEmpty($str) == false)
		{
			$t_str = explode(",",base64_decode($str));

			if(is_file($t_str['0']) == true)
			{
				header("Content-Type: text/html; charset=UTF-8");
				if(customEregi("(MSIE 5.0|MSIE 5.1|MSIE 5.5|MSIE 6.0|MSIE 7.0|MSIE 8.0|MSIE 9.0)",$_SERVER['HTTP_USER_AGENT']))
				{ 
					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.5")) 
					{ 
						header("Content-Type: doesn/matter"); 
						header("Content-disposition: filename=".str_replace("+","%20", urlencode($t_str['1']))); 
						header("Content-Transfer-Encoding: binary"); 
						header("Expires: 0"); 
					} 

					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.0")) 
					{ 
						Header("Content-type: file/unknown"); 
						header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1']))); 
						Header("Content-Description: PHP3 Generated Data"); 
						header("Expires: 0"); 
					} 

					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5.1")) 
					{ 
						Header("Content-type: file/unknown"); 
						header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1']))); 
						Header("Content-Description: PHP3 Generated Data"); 
						header("Expires: 0"); 
					} 
					
					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0"))
					{  	
						Header("Content-type: application/x-msdownload"); 
						Header("Content-Length: ".(string)(filesize($t_str['0'])));
						Header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1'])));   
						Header("Content-Transfer-Encoding: binary");   
						Header("Expires: 0");   
					}
					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 7.0"))
					{  	
						Header("Content-type: application/x-msdownload"); 
						Header("Content-Length: ".(string)(filesize($t_str['0'])));
						Header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1'])));   
						Header("Content-Transfer-Encoding: binary");   
						Header("Expires: 0");   
					}
					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 8.0"))
					{
						header("Content-Type: text/html; charset=UTF-8");
						Header("Content-type: application/x-msdownload"); 
						Header("Content-Length: ".(string)(filesize($t_str['0'])));
						Header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1'])));   
						Header("Content-Transfer-Encoding: binary");   
						Header("Expires: 0");   
					}
					if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 9.0"))
					{  	
						Header("Content-type: application/x-msdownload"); 
						Header("Content-Length: ".(string)(filesize($t_str['0'])));
						Header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1'])));   
						Header("Content-Transfer-Encoding: binary");   
						Header("Expires: 0");   
					}
				} 
				else 
				{ 
					Header("Content-type: file/unknown");     
					Header("Content-Length: ".(string)(filesize($t_str['0']))); 
					Header("Content-Disposition: attachment; filename=".str_replace("+","%20", urlencode($t_str['1']))); 
					Header("Content-Description: PHP3 Generated Data"); 
					Header("Expires: 0"); 
				} 

				$fp = fopen($t_str['0'], "rb"); 

				if (!fpassthru($fp))  
				{
					fclose($fp); 
				}
			}
			else 
			{ 
				echo "해당 파일이나 경로가 존재하지 않습니다."; 
			} 
		}
	}
}
?>