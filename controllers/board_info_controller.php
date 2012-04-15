<?php
class BoardInfoController extends BoardAppController 
{
	var $name = "BoardInfo";
	var $helpers = array('Html', 'Session');
	var $uses = array("board.BoardInfo");

	function beforeFilter() 
	{
		$obj['BoardInfoController'] = $this;
		$this->Auth->allow();
		$this -> BoardInfo ->_init($obj);
		parent::beforeFilter();

		##		게시판은 전체관리자만 접속이 가능하다.
		if($this -> _board_user['admin'] == "NO")
		{
			showMsg(Configure::read("description.not_connect"));
			move_location(HOSTHOME.$this -> _url['index']);
		}
	}






	function data_list()
	{
//		$this->log("BoardInfoController->admin_data_list()", LOG_DEBUG);

		$parameter = ControllerParameterParsing($this -> data['BoardInfo'],$this -> params);
		$temp['data'] = $this -> BoardInfo -> getDataList($parameter);

		$db['data'] = $temp['data'];

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));
	}



	function data_form($id="")
	{
		$parameter = ControllerParameterParsing($this -> data['BoardInfo'],$this -> params);
		$parameter['id'] = $id;

		if(IsEmpty($parameter['id']) == false)
		{
			$temp['data'] = $this -> BoardInfo -> getDataInfo($parameter['id']);
			$db['data'] = $temp['data']['result'];
		}

		$this->set('parameter',$parameter);
		$this->set('db',ValueNullCheck($db));
		$this->set('tpl',ValueNullCheck($tpl));
	}



	function data_process()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardInfo'],$this -> params);

		$temp['process'] = $this -> BoardInfo -> getDataProcess($parameter);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}



	function data_delete()
	{
		$parameter = ControllerParameterParsing($this -> data['BoardInfo'],$this -> params);
		if(empty($parameter['id']) == false)
		{
			$temp['delete'] = $this -> BoardInfo -> getDataDelete($parameter['id']);
			if($temp['delete']['check'] == true)
			{
				showMsg(Configure::read("description.yes_ok"));
//				move_location("/baseballid/admin/style/data_list/page:".(IsEmpty($parameter['data_page'])==true?1:$parameter['data_page'])."?".ViewParameterParsing($parameter,"item,style_search_type1,style_search_type2,style_search_type3,style_search_type4,style_search_type5,style_search_type6,b_style_search_type1,b_style_search_type2"));
				move_location(HOSTHOME."/board/board_info/data_list");
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
		$temp['process'] = $this -> BoardInfo -> getDataDeleteByFast($this -> data['BoardInfo']);
		if($temp['process']['check'] == true)
		{
			showMsg(Configure::read("description.yes_ok"));
			move_location(HOSTHOME."/board/board_info/data_list");
		}
		else
		{
			errMsgToBack(Configure::read("description.no_ok"));
			exit;
		}
	}
}
?>