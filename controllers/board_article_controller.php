<?php
class BoardArticleController extends BoardAppController 
{
	var $name = "BoardArticle";
	var $helpers = array('Html', 'Session', 'board.Fck');
	var $uses = array("board.BoardArticle", "board.BoardInfo", "board.BoardCategory", "board.BoardComment", "board.BoardFile", "board.BoardFileRelation");

	function beforeFilter() 
	{
		parent::beforeFilter();
		$obj['BoardArticleController'] = $this;
		$obj['BoardCommentController'] = $this;
		$this->Auth->allow();
		$this -> BoardArticle ->_init($obj,$this -> _board_user);
		$this -> BoardComment ->_init($obj);
		$this -> BoardFileRelation ->_init();
		$this -> BoardFile ->_init();
	}




	function auth_check($mode="read",$board_info="")
	{
		$check = false;

		if($board_info[$mode] == "no") $check = true;
		else if($board_info[$mode] == "user") 
		{
			if($this -> _board_user['check'] == "YES") $check = true;
		}
		else if($board_info[$mode] == "admin") 
		{
			if($this -> _board_user['admin'] == "YES") $check = true;
		}

		if($check == false)
		{
			showMsg(Configure::read("description.not_connect"));
			move_location(HOSTHOME.$this -> _url['index']);
		}
	}




	function data_list()
	{
//		$this->log("BoardArticleController->admin_data_list()", LOG_DEBUG);

		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id,view_type");
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

		$temp['board_category'] = $this -> BoardCategory -> getDataListByAll($parameter['board_info_id']);
		$temp['data'] = $this -> BoardArticle -> getDataList($parameter,$temp['board_info']['result']);

		$db['data'] = $temp['data'];
		$db['board_info'] = $temp['board_info']['result'];
		if(sizeof($temp['board_category']['result']) > 0) $db['board_category'] = $temp['board_category']['result'];

		if(IsEmpty($parameter['view_type']) == true) 
		{
			if($db['board_info']['column']['type'] == "B") $parameter['view_type'] = "gallery";
			else $parameter['view_type'] = "board";
		}

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));

		if(ValueIsCheck($parameter['view_type'],"board","equal") == true || IsEmpty($parameter['view_type']) == true)
		{
			$this->render("data_list");
		}
		else if(ValueIsCheck($parameter['view_type'],"gallery","equal") == true)
		{
			$this->render("data_list_gallery");
		}
	}




	function total_search_data_list()
	{
//		$this->log("BoardArticleController->admin_data_list()", LOG_DEBUG);

		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"");
		$temp['data'] = $this -> BoardArticle -> getDataListByTotalSearch($parameter);

		$db['data'] = $temp['data'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));
	}





	function data_view($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id,comment_data_page");
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
		$this -> auth_check("read",$temp['board_info']['result']['column']);

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardArticle -> getDataInfo($parameter['id'],$temp['board_info']['result']);
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
			if(IsEmpty($db['data']['column']['id']) == false && $temp['board_info']['result']['column']['comment_use'] == "Y")
			{
				$temp['board_comment'] = $this -> BoardComment -> getDataListByAdmin($db['data']['column']['id'],ValueNullCheck($parameter['comment_data_page']));
				$db['board_comment'] = $temp['board_comment']['result'];
			}
			if($temp['board_info']['result']['column']['file_use'] == "Y")
			{
				$temp['board_file'] = $this -> BoardFileRelation -> getDataListByReferenceMany($db['data']['column']['id']);
				$db['board_file'] = ValueNullCheck($temp['board_file']['result'][$db['data']['column']['id']]);
			}

			##		비밀글 비번 검사..
			if($db['data']['column']['secret'] == "Y") $this -> board_confirm($db['data'],$parameter,str_replace("admin_","",$this -> params['action']));

			##		뷰수 증가
			if(ValueIsCheck($this -> _board_user['id'],$db['data']['column']['owner_id'],"not") == true) $this -> BoardArticle -> getDataModifyByHit($db['data']['column']['id']);
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
	}



	function data_form($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");
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
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardArticle -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				showMsg(Configure::read("description.not_find_board_article"));
				move_location(HOSTHOME."/board/board_info/data_list");
			}
			$db['data'] = $temp['data']['result'];

			if($temp['board_info']['result']['column']['file_use'] == "Y")
			{
				$temp['board_file'] = $this -> BoardFileRelation -> getDataListByReferenceMany($db['data']['column']['id']);
				$db['board_file'] = ValueNullCheck($temp['board_file']['result'][$db['data']['column']['id']]);
			}

			##		비밀번호 확인을 한다.
			$this -> board_confirm($db['data'],$parameter,str_replace("admin_","",$this -> params['action']));
		}


		$db['board_info'] = $temp['board_info']['result'];
		$temp['board_category'] = $this -> BoardCategory -> getDataListByAll($parameter['board_info_id']);
		if(sizeof($temp['board_category']['result']) > 0) $db['board_category'] = $temp['board_category']['result'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));
	}



	function reply_data_form($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");
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
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['parent_data'] = $this -> BoardArticle -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['parent_data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				showMsg(Configure::read("description.not_find_board_article"));
				move_location(HOSTHOME."/board/board_info/data_list");
			}

			$db['parent_data'] = $temp['parent_data']['result'];
			$db['parent_data']['column']['view_content'] = "&nbsp;<br /><br /><br />[원문]<br /><br />";
			$db['parent_data']['column']['view_content'].= "----------------------------------------------<br /><br />";
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
	}



	function data_confirm($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id,action");
		$parameter['id'] = $id;

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));
	}


	function confirm_data_process($params="")
	{
		if(IsEmpty($params) == true) $parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");
		else $parameter = ControllerParameterParsing($params,$this -> params,"board_info_id,board_category_id");

		if(IsEmpty($parameter['confirm_password']) == true)
		{
			$this->log("비밀번호가 없습니다.", LOG_DEBUG);
			errMsgToBack(Configure::read("description.no_ok"));
		}
		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			errMsgToBack(Configure::read("description.not_find_board_info"));
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		if(IsEmpty($temp['board_info']['result']['column']['id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			errMsgToBack(Configure::read("description.not_find_board_info"));
		}

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardArticle -> getDataInfo($parameter['id']);
			if(IsEmpty($temp['data']['result']['column']['id']) == true)
			{
				$this->log("게시글 정보를 찾을 수 없습니다.", LOG_DEBUG);
				errMsgToBack(Configure::read("description.not_find_board_article"));
			}
			$db['data'] = $temp['data']['result'];
		}


		##		비밀번호 확인을 한다.
		$this -> board_confirm($db['data'],$parameter,$parameter['action'],"process");
	}




	function data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		$temp['process'] = $this -> BoardArticle -> getDataProcess($parameter,$temp['board_info']['result']);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));

			if(IsEmpty($parameter['id']) == true)
			{
				move_location(HOSTHOME."/board/board_article/data_list/page:1?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
			}
			else 
			{
				move_location(HOSTHOME."/board/board_article/data_view/".$parameter['id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
			}
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}



	function reply_data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		$temp['process'] = $this -> BoardArticle -> getDataProcessbyReply($parameter);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}



	function data_delete()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		if(empty($parameter['id']) == false)
		{
			$temp['delete'] = $this -> BoardArticle -> getDataDelete($parameter['id']);
			if($temp['delete']['check'] == true)
			{
				showMsg(Configure::read("description.yes_ok"));
				move_location(HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
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




	function data_fast_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardArticle'],$this -> params,"board_info_id,board_category_id");

		if(IsEmpty($parameter['board_info_id']) == true)
		{
			$this->log("게시판 정보를 찾을 수 없습니다.", LOG_DEBUG);
			showMsg(Configure::read("description.not_find_board_info"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		$temp['board_info'] = $this -> BoardInfo -> getDataInfo($parameter['board_info_id']);
		$this -> auth_check("write",$temp['board_info']['result']['column']);

		$temp['process'] = $this -> BoardArticle -> getDataDeleteByFast($parameter);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_article/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}




	function board_confirm($info="",$parameter="",$action="data_view",$mode="check")
	{
		if($this -> Session -> check($this->Auth->sessionKey.".article_confirm") == true)
		{
			$article_confirm = $this -> Session -> read($this->Auth->sessionKey.".article_confirm");
		}


		$check = false;
		if(ValueIsCheck($article_confirm[$parameter['id']],"YES","equal") == true) $check = true;
		else if($this -> _board_user['admin'] == "YES") 
		{
			$check = true;
			$article_confirm[$parameter['id']] = "YES";
		}
		else if(ValueIsCheck($this -> _board_user['id'],$info['column']['owner_id'],"equal") == true) 
		{
			$check = true;
			$article_confirm[$parameter['id']] = "YES";
		}
		else
		{
			if(IsEmpty($parameter['confirm_password']) == false)
			{
				if(Security::hash($parameter['confirm_password'], null, true) == $info['column']['owner_password']) 
				{
					$check = true;
					$article_confirm[$parameter['id']] = "YES";
				}
			}
			if($info['column']['depth'] > 0)
			{
				$parent = $this -> BoardArticle -> getDataInfo(($info['column']['family'] * -1));
				if(IsEmpty($parent['result']['column']['id']) == false)
				{
					if(ValueIsCheck($this -> _board_user['id'],$parent['result']['column']['owner_id'],"equal") == true) 
					{
						$check = true;
						$article_confirm[$parameter['id']] = "YES";
					}
					if(Security::hash($parameter['confirm_password'], null, true) == $parent['result']['column']['owner_password']) 
					{
						$check = true;
						$article_confirm[$parameter['id']] = "YES";
					}
				}
			}
		}
		$this -> Session -> write($this->Auth->sessionKey.".article_confirm",$article_confirm);

		if($check == false)
		{
			if(IsEmpty($this -> params['prefix']) == false)
			{
				move_location(HOSTHOME."/board/".$this -> params['prefix']."/board_article/data_confirm/".$parameter['id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id")."&action=".$action);
			}
		}
		else
		{
			if($mode == "process")
			{
				if(IsEmpty($this -> params['prefix']) == false)
				{
					move_location(HOSTHOME."/board/".$this -> params['prefix']."/board_article/".$action."/".$parameter['id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
				}
				else
				{
					move_location(HOSTHOME."/board/board_article/".$action."/".$parameter['id']."/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"board_info_id,board_category_id"));
				}
			}
		}

		return;
	}
}
?>