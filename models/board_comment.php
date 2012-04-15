<?php
class BoardComment extends BoardAppModel {
	var $name = 'BoardComment';
	var $virtualFields = array(
			'max_id' => "MAX(id)",
			'write' => "UNIX_TIMESTAMP(created)"
	);

	/*
	var $belongsTo = array(
		'BoardCategory' => array(
			'className' => 'BoardCategory',
			'foreignKey' => 'board_category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	*/


	var $_obj;
	var $_page_per_data_ea;
	var $_type;
	function _init($o="",$u="")
	{
		$this -> _page_per_data_ea = 20;
		$this -> _obj = $o;
	}




	function getDataListByAdmin($id="",$comment_page="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array(
				"id", "content", "write", "created", "modified", "family","field_sort","depth", "owner_id", "owner_name", "owner_password"
			);
			$temp['conditions'] = array("board_article_id" => $id);
			$temp['orderby'] = array(
				"family" => "ASC",
				"field_sort" => "ASC",
			);

/*
			$temp['page_per_data_ea'] = IsEmpty($params['page_per_data_ea']) == true ? $this -> _page_per_data_ea : $params['page_per_data_ea'];
			$this -> _obj['BoardCommentController'] -> paginate = array(
					'conditions' => $temp['conditions'],
					'fields' => $temp['fields'],
					'order' => $temp['orderby'],
					'limit' => $temp['page_per_data_ea'],
					'recursive' => -1
			);
			$temp['result'] = $this -> _obj['BoardCommentController'] -> paginate('BoardComment');
			$return['search_total_count'] = $this -> _obj['BoardCommentController'] -> params['paging']['BoardComment']['count'];
			$return['search_total_page'] = $this -> _obj['BoardCommentController'] -> params['paging']['BoardComment']['pageCount'];
			$temp['virtual_num'] = getVirtualNum($this -> _obj['BoardCommentController'] -> params['paging'],"BoardComment");
*/
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
					$data = $temp['result'][$i]['BoardComment'];
					$space = "";
					for($j=1;$j<=$data['depth'];$j++)
					{
						$space.= "&nbsp;&nbsp;&nbsp;";
					}
					if($data['depth'] > 0) $re = "L->";
					else $re = "";
					$return['result'][$i] = array(
						'column' => $data,
						"space" => $space,
						"re" => $re
					);
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
				"id", "board_info_id", "content", "family", "field_sort", "depth", "owner_id", "owner_password"
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
			if(IsEmpty($temp['result']['BoardComment']['id']) == false)
			{
				$return['result'] = array(
					'column' => $temp['result']['BoardComment']
				);
			}
			else $return['result'] = null;
		}
		else $return['result'] = null;

		return $return;
	}




	function getDataInfoByLastId($board_info_id="")
	{
		if(IsEmpty($board_info_id) == false)
		{
			$temp['fields'] = array("id");
			$temp['conditions'] = array("board_info_id" => $board_info_id, "depth" => 0);
			$temp['orderby'] = array("id" => "DESC");
			$temp['result'] = $this -> find('first',array(
				'conditions' => $temp['conditions'],
				'recursive' => -1, //int    
				'fields' => $temp['fields'], //array of field names    
				'order' => $temp['orderby'],
				)
			);
			if(IsEmpty($temp['result']['BoardComment']['id']) == false)
			{
				$temp['id'] = $temp['result']['BoardComment']['id'];
			}
			else $temp['id'] = "";
		}
		else $temp['id'] = "";

		return $temp['id'];
	}




	function getDataInfoByMax()
	{
		$temp['fields'] = array("max_id");
		$temp['conditions'] = array();
		$temp['orderby'] = array();
		$temp['result'] = $this -> find('first',array(
			'conditions' => $temp['conditions'],
			'recursive' => -1, //int    
			'fields' => $temp['fields'], //array of field names    
			'order' => $temp['orderby'],
			)
		);
		if(IsEmpty($temp['result']['BoardComment']['max_id']) == false)
		{
			$return = $temp['result']['BoardComment']['max_id'];
		}
		else $return = 1;

		return $return;
	}




	function getDataModifyByReply($params="")
	{
		$temp['max'] = $this -> getDataInfoByMax();
		$temp['fields'] = array("id");
		$temp['conditions'] = array("id <" => $temp['max'], "family" => $params['family'], "field_sort >=" => $params['field_sort']);
		$temp['orderby'] = array("id" => "ASC");
		$temp['result'] = $this -> find('all',array(
			'conditions' => $temp['conditions'],
			'recursive' => -1, //int    
			'fields' => $temp['fields'], //array of field names    
			'order' => $temp['orderby'],
			)
		);
		$temp['sizeof'] = sizeof($temp['result']);
		if($temp['sizeof'] > 0)
		{
			for($i=0;$i<$temp['sizeof'];$i++)
			{
				$data = $temp['result'][$i]['BoardComment'];
				$temp['fields'] = array("field_sort" => "field_sort + 1");
				$temp['conditions'] = array(
					"id" => $data['id']
				);
				if($this->updateAll($temp['fields'],$temp['conditions']) == false)
				{
					$return['check'] = false;
					return $return;
				}
				unset($temp['conditions'], $temp['fields'],$data);
			}
		}
		$return['check'] = true;

		return $return;
	}





	function getDataProcess($params="")
	{
		if(IsEmpty($params['owner_password']) == false)
		{
			if($params['user_check'] == "NO") $params['owner_password'] = Security::hash($params['owner_password'], null, true);
			else $params['owner_password'] = $params['owner_password'];
		}
		else unset($params['owner_password']);

		$data['BoardComment'] = $params;
		if(IsEmpty($data['BoardComment']['id']) == true) 
		{
			$data['BoardComment']['owner_ip'] = $_SERVER['REMOTE_ADDR'];

			if(class_exists('DATABASE_CONFIG') == true)
			{
				$db = new DATABASE_CONFIG;
				$_database_name = $db -> default['database'];
				unset($db);
			}
			else
			{
				$return['check'] = false;
				return $return;
			}

			$temp['query'] = "SHOW TABLE STATUS FROM ".$_database_name." LIKE '".$this -> useTable."'";
			$temp['info'] = $this -> query($temp['query']); 
			$temp['family'] = $temp['info']['0']['TABLES']['Auto_increment'];
			$data['BoardComment']['family'] = $temp['family'];
		}

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




	function getDataProcessByReply($params="")
	{
		if(IsEmpty($params['owner_password']) == false)
		{
			$params['owner_password'] = Security::hash($params['owner_password'], null, true);
		}
		else unset($params['owner_password']);
		$data['BoardComment'] = $params;
		if(IsEmpty($data['BoardComment']['id']) == true) 
		{
			$data['BoardComment']['owner_ip'] = $_SERVER['REMOTE_ADDR'];
			$data['BoardComment']['family'] = $params['parent_family'];
			$data['BoardComment']['field_sort'] = $params['parent_field_sort'] + 1;
			$data['BoardComment']['depth'] = $params['parent_depth'] + 1;
		}
		if(IsEmpty($data) == false)
		{
			if($this->save($data) == true) 
			{
				$temp['update'] = $this -> getDataModifyByReply($data['BoardComment']);
				$return['check'] = $temp['update']['check'];
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
}
?>