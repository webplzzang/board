<?php
class BoardCommentController extends BoardAppController 
{
	var $name = "BoardComment";
	var $helpers = array('Html', 'Session');
	var $uses = array("board.BoardComment", "board.BoardInfo", "board.BoardCategory");

	function beforeFilter() 
	{
		$obj['BoardCommentController'] = $this;
		$this->Auth->allow("comment_confirm");
		$this -> BoardComment ->_init($obj);
		parent::beforeFilter();
	}





/*
	function admin_data_list($id="",$params="")
	{
//		$this->log("BoardCommentController->admin_data_list()", LOG_DEBUG);
		if(IsEmpty($id) == true)
		{
			$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_article"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}

		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		$temp['data'] = $this -> BoardComment -> getDataListByAdmin($parameter);

		$db['data'] = $temp['data'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));

		$this->layout = 'admin';
	}



	function admin_data_view($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		$parameter['id'] = $id;

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		if(IsEmpty($temp['board_info']['result']['column']['id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardComment -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				showMsg(Configure::read("description.not_find_board_article"));
				move_location(HOSTHOME."/board/board_info/data_list");
			}

			$db['data'] = $temp['data']['result'];
			if(IsEmpty($db['data']['column']['board_category_id']) == false)
			{
				$temp['board_category'] = $this -> BoardCategory -> getDataListByReferenceMany($db['data']['column']['board_category_id']);
				$db['board_category'] = $temp['board_category']['result'][$db['data']['column']['board_category_id']];
			}
		}
		else
		{
			$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_article"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}

		$db['board_info'] = $temp['board_info']['result'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));

		$this->layout = 'admin';
	}



	function admin_data_form($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		$parameter['id'] = $id;

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		if(IsEmpty($temp['board_info']['result']['column']['id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardComment -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				showMsg(Configure::read("description.not_find_board_article"));
				move_location(HOSTHOME."/board/board_info/data_list");
			}

			$db['data'] = $temp['data']['result'];
		}

		$db['board_info'] = $temp['board_info']['result'];
		$temp['board_category'] = $this -> BoardCategory -> getDataListByAll($parameter['board_info_id']);
		if(sizeof($temp['board_category']['result']) > 0) $db['board_category'] = $temp['board_category']['result'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));

		$this->layout = 'admin';
	}



	function admin_reply_data_form($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		$parameter['id'] = $id;

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		if(IsEmpty($temp['board_info']['result']['column']['id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['parent_data'] = $this -> BoardComment -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['parent_data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				showMsg(Configure::read("description.not_find_board_article"));
				move_location(HOSTHOME."/board/board_info/data_list");
			}

			$db['parent_data'] = $temp['parent_data']['result'];
			$db['parent_data']['column']['view_content'] = "&nbsp;\r\n\n\n\n[원문]\r\n";
			$db['parent_data']['column']['view_content'].= "----------------------------------------------\r\n";
			$db['parent_data']['column']['view_content'].= $temp['parent_data']['result']['column']['content'];
			if(IsEmpty($db['parent_data']['column']['board_category_id']) == false)
			{
				$temp['board_category'] = $this -> BoardCategory -> getDataListByReferenceMany($db['parent_data']['column']['board_category_id']);
				$db['board_category'] = $temp['board_category']['result'][$db['parent_data']['column']['board_category_id']];
			}
		}

		$db['board_info'] = $temp['board_info']['result'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));

		$this->layout = 'admin';
	}
*/




	function comment_confirm()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"");
		$temp['info'] = $this -> BoardComment -> getDataInfo($parameter['id']);
		$info = $temp['info']['result'];
		if(IsEmpty($info['column']['id']) == true)
		{
			$tpl['str'] = "FAIL";
		}
		else
		{
			if($this -> Session -> check($this->Auth->sessionKey.".comment_confirm") == true)
			{
				$comment_confirm = $this -> Session -> read($this->Auth->sessionKey.".comment_confirm");
			}

			$check = false;
			if(ValueIsCheck($comment_confirm[$parameter['id']],"YES","equal") == true) 
			{
				$check = true;
				$tpl['str'] = "YES,".$parameter['id'].",".$parameter['mode'];
			}
			else if($this -> _board_user['admin'] == "YES") 
			{
				$check = true;
				$comment_confirm[$parameter['id']] = "YES";
				$tpl['str'] = "YES,".$parameter['id'].",".$parameter['mode'];
			}
			else if(ValueIsCheck($this -> _board_user['id'],$info['column']['owner_id'],"equal") == true) 
			{
				$check = true;
				$comment_confirm[$parameter['id']] = "YES";
				$tpl['str'] = "YES,".$parameter['id'].",".$parameter['mode'];
			}
			else 
			{
				$tpl['str'] = "NO,".$parameter['id'].",".$parameter['mode'];
			}
			$this -> Session -> write($this->Auth->sessionKey.".comment_confirm",$comment_confirm);
		}

		$this->set('tpl',$tpl);
		$this->layout = 'none';
	}




	function confirm_data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"");
		$temp['info'] = $this -> BoardComment -> getDataInfo($parameter['id']);
		$info = $temp['info']['result'];
		if(IsEmpty($parameter['confirm_password']) == false)
		{
			if(Security::hash($parameter['confirm_password'], null, true) == $info['column']['owner_password']) 
			{
				$check = true;
				$comment_confirm[$parameter['id']] = "YES";
				$this -> Session -> write($this->Auth->sessionKey.".comment_confirm",$comment_confirm);
			}
			else $check = false;
		}

		$this->set('check',$check);
		$this->set('parameter',$parameter);
		$this->layout = 'none';
	}




	function admin_data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		$temp['process'] = $this -> BoardComment -> getDataProcess($parameter);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_article/data_view/".$parameter['board_article_id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}



	function admin_reply_data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params,"board_info_id,board_category_id");
		
		$temp['process'] = $this -> BoardComment -> getDataProcessbyReply($parameter);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_article/data_view/".$parameter['board_article_id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}



	function admin_data_delete()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardComment'],$this -> params);
		if(empty($parameter['id']) == false)
		{
			$temp['delete'] = $this -> BoardComment -> getDataDelete($parameter['id']);
			if($temp['delete']['check'] == true)
			{
				showMsg(Configure::read("description.yes_ok"));
				move_location(HOSTHOME."/board/board_article/data_view/".$parameter['board_article_id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
			}
			else
			{
				errMsgToBack(Configure::read("description.no_ok"));
				exit;
			}
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}
}
?>