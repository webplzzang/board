<?php
if(file_exists(APP.DS."plugins".DS."board".DS."config".DS."function.php") == true) require_once APP."plugins".DS."board".DS."config".DS."function.php";


define("PROTOCOL",																											"http://");																					#프로토콜 표시
define("PROTOCOL_HTTP",																							"http://");																					#프로토콜 표시
define("HOSTHOME",																											PROTOCOL.$_SERVER['HTTP_HOST']."/pay");	#루트
define("HOSTHOME_HTTP",																						"http://".$_SERVER['HTTP_HOST']);			#루트


##		데이타 처리관련
$config['description']['no_param_value']																			= "파라미터가 정상적으로 전달되지 않았습니다.";
$config['description']['yes_ok']																										= "정상적으로 처리되었습니다.";
$config['description']['no_ok']	 																									= "데이타를 처리 하지 못했습니다.";
$config['description']['no_data']																									= "데이타가 없습니다.";
$config['description']['confirm_delete']																				= "정말로 삭제하시겠습니까?";

##		검색관련
$config['description']['searching']																								= "검색중입니다...";

##		카테고리 관련
$config['description']['category_choice']																			= "카테고리를 먼저 선택하세요!";
$config['description']['category_top']																					= "가장 상위입니다.";
$config['description']['category_bottom']																		= "가장 하위입니다.";

##		게시판
$config['description']['board_confirm_delete']															= "정말로 삭제하시겠습니까?\\n하단의 확인 버튼을 클릭하셔야 최종 삭제됩니다.";
?>