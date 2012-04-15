<?php
class BoardCategory extends BoardAppModel {
	var $name = 'BoardCategory';

/*
	var $hasOne = array(
		'BoardArticle' => array(
			'className' => 'BoardArticle',
			'foreignKey' => 'board_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
*/

	var $_obj;
	var $_page_per_data_ea;
	function _init($o="",$u="")
	{
		$this -> _page_per_data_ea = 20;
		$this -> _obj = $o;
	}





	function getDataListByAdmin($params="")
	{
		if(isset($this -> _obj['BoardInfo']) == false) $this -> _obj['BoardInfo'] = &ClassRegistry::init('BoardInfo');
		$temp['board_info'] = $this -> _obj['BoardInfo'] -> getDataListByAll("array");
		$temp['fields'] = array("id", "board_info_id", "title", "created", "modified");

		$temp['conditions'] = "";
		if(IsEmpty($params['board_info_id']) == false && ValueIsCheck($params['board_info_id'],"no","not") == true)
		{
			$temp['conditions'] = array("board_info_id" => $params['board_info_id']);
		}
		if(IsEmpty($return['chk_date']['start_timestamp']) == false)
		{
			unset($conditions);
			$conditions = array(
				"UNIX_TIMESTAMP(created) >=" => $return['chk_date']['start_timestamp'],
				"UNIX_TIMESTAMP(created) <=" => $return['chk_date']['end_timestamp']
			);
			$temp['conditions'] = array_merge($temp['conditions'],$conditions);
		}
		$temp['orderby'] = array("id" => "DESC");
		$temp['page_per_data_ea'] = empty($params['page_per_data_ea']) == true ? $this -> _page_per_data_ea : $params['page_per_data_ea'];
		$this -> _obj['BoardCategoryController'] -> paginate = array(
				'conditions' => $temp['conditions'],
				'fields' => $temp['fields'],
				'order' => $temp['orderby'],
				'limit' => $temp['page_per_data_ea'],
				'recursive' => -1
		);
		$temp['result'] = $this -> _obj['BoardCategoryController'] -> paginate('BoardCategory');
		$return['search_total_count'] = $this -> _obj['BoardCategoryController'] -> params['paging']['BoardCategory']['count'];
		$return['search_total_page'] = $this -> _obj['BoardCategoryController'] -> params['paging']['BoardCategory']['pageCount'];
		$temp['virtual_num'] = getVirtualNum($this -> _obj['BoardCategoryController'] -> params['paging'],"BoardCategory");


		##		전체/검색 개수
		$return['total_count'] = $this -> find('count', array("recursive" => -1));


		$temp['size'] = sizeof($temp['result']);
		if($temp['size'] > 0)
		{
			for($i=0;$i<$temp['size'];$i++)
			{
				$data = $temp['result'][$i]['BoardCategory'];
				$return['result'][$i] = array(
					'virtual_num' => $temp['virtual_num']--,
					'column' => $data,
					'board_info_title' => $temp['board_info']['result'][$data['board_info_id']],
				);
			}
		}
		else
		{
			$return['result'] = null;
		}

		return $return;
	}





	function getDataListByAll($board_id="",$return_type="list")
	{
		$temp['fields'] = array("id", "title");
		if(IsEmpty($board_id) == false) $temp['conditions'] = array("board_info_id" => $board_id);
		else $temp['conditions'] = "";
		$temp['orderby'] = array("title" => "ASC");
		$temp['result'] = $this -> find('all',array(
			'conditions' => $temp['conditions'],
			'fields' => $temp['fields'],
			'order' => $temp['orderby']
			)
		);
		$temp['size'] = sizeof($temp['result']);
		if($temp['size'] > 0)
		{
			for($i=0;$i<$temp['size'];$i++)
			{
				$data = $temp['result'][$i]['BoardCategory'];

				if($return_type == "list")
				{
					$return['result'][$i] = array(
						'column' => $data,
					);
				}
				else
				{
					$return['result'][$data['id']] = $data['title'];
				}
			}
		}
		else
		{
			$return['result'] = null;
		}

		return $return;
	}





	function getDataListByReferenceMany($id="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array("id", "title");
			$temp['conditions'] = array("id" => $id);
			$temp['orderby'] = array();
			$temp['result'] = $this -> find('all',array(
				'conditions' => $temp['conditions'],
				'fields' => $temp['fields'],
				'order' => $temp['orderby']
				)
			);
			$temp['size'] = sizeof($temp['result']);
			if($temp['size'] > 0)
			{
				for($i=0;$i<$temp['size'];$i++)
				{
					$data = $temp['result'][$i]['BoardCategory'];
					$return['result'][$data['id']]['column'] = $data;
				}
			}
			else
			{
				$return['result'] = null;
			}
		}
		else
		{
			$return['result'] = null;
		}

		return $return;
	}





	function getDataInfo($id="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array(
				"id", "board_info_id", "title", "created" 
			);
			$temp['conditions'] = array("id" => $id);
			$temp['orderby'] = array();
			$temp['result'] = $this -> find('first',array(
				'conditions' => $temp['conditions'],
				'recursive' => -1, //int    
				'fields' => $temp['fields'], //array of field names    
				'order' => $temp['orderby'],
				)
			);
			if(IsEmpty($temp['result']['BoardCategory']['id']) == false)
			{
				$return['result'] = array(
				'column' => $temp['result']['BoardCategory']
			);
			}
			else $return['result'] = null;
		}
		else $return['result'] = null;

		return $return;
	}





	function getDataProcess($params="")
	{
		$data['BoardCategory'] = $params;
		if(IsEmpty($data) == false)
		{
			if($this->save($data) == true) 
			{
				$return['check'] = true;
			}
			else $return['check'] = false;
		}
		else $return['check'] = false;

		return $return;
	}





	function getDataDelete($id="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['data'] = $this -> getDataInfo($id);
			if($this -> delete($temp['data']['result']['column']['id'],false) == true) 
			{
				$return['check'] = true;
				$return['result'] = $temp['data']['result'];
			}
			else $return['check'] = false;
		}
		else $return['check'] = false;

		return $return;
	}




	function getDataDeleteByFast($params="")
	{
		$temp['id'] = explode(",",$params['prkey_value']);
		for($i=0;$i<sizeof($temp['id']);$i++)
		{
			if($temp['id'][$i] != "")
			{
				$chk['delete'] = $this -> getDataDelete($temp['id'][$i]);
				if(!$chk['delete']['check'])
				{
					$return['check'] = false;
				}
			}
		}

		$return['check'] = true;
		return $return;
	}
}
?>