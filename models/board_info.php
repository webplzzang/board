<?php
class BoardInfo extends BoardAppModel {
	var $name = 'BoardInfo';

	var $_obj;
	var $_page_per_data_ea;
	var $_type;
	function _init($o="",$u="")
	{
		$this -> _page_per_data_ea = 20;
		$this -> _obj = $o;

		$this -> _type = array(
			'A' => "일반",
			'B' => "이미지",
			'C' => "자료"
		);
	}





	function getDataList($params="")
	{
		$temp['fields'] = array("id", "title", "type", "read", "write", "created", "modified", "last_article_date", "article_count");
		$temp['conditions'] = "";
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
		$this -> _obj['BoardInfoController'] -> paginate = array(
				'conditions' => $temp['conditions'],
				'fields' => $temp['fields'],
				'order' => $temp['orderby'],
				'limit' => $temp['page_per_data_ea'],
				'recursive' => -1
		);
		$temp['result'] = $this -> _obj['BoardInfoController'] -> paginate('BoardInfo');
		$return['search_total_count'] = $this -> _obj['BoardInfoController'] -> params['paging']['BoardInfo']['count'];
		$return['search_total_page'] = $this -> _obj['BoardInfoController'] -> params['paging']['BoardInfo']['pageCount'];
		$temp['virtual_num'] = getVirtualNum($this -> _obj['BoardInfoController'] -> params['paging'],"BoardInfo");


		##		전체/검색 개수
		$return['total_count'] = $this -> find('count');


		$temp['size'] = sizeof($temp['result']);
		if($temp['size'] > 0)
		{
			for($i=0;$i<$temp['size'];$i++)
			{
				$data = $temp['result'][$i]['BoardInfo'];
				$return['result'][$i] = array(
					'virtual_num' => $temp['virtual_num']--,
					'column' => $data,
					'type' => $this -> _type[$data['type']],
				);
			}
		}
		else
		{
			$return['result'] = null;
		}

		return $return;
	}





	function getDataListByAll($return_type="list")
	{
		$temp['fields'] = array("id", "title");
		$temp['conditions'] = "";
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
				$data = $temp['result'][$i]['BoardInfo'];

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
					$data = $temp['result'][$i]['BoardInfo'];
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
				"id", "title", "read", "write", "comment", "comment_use", "reply_use", "file_use", "file_ea", "file_size", "thumbnail_use", 
				"article_count", "public", "article_count", "last_article_date", "created", "type"
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
			if(IsEmpty($temp['result']['BoardInfo']['id']) == false)
			{
				$return['result'] = array(
				'column' => $temp['result']['BoardInfo']
			);
			}
			else $return['result'] = null;
		}
		else $return['result'] = null;

		return $return;
	}





	function getDataProcess($params="")
	{
		$data['BoardInfo'] = $params;
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