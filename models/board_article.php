<?php
class BoardArticle extends BoardAppModel {
	var $name = 'BoardArticle';
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
		$this -> _board_user = $u;
	}




	function getDataList($params="",$info="")
	{
		if(isset($this -> _obj['BoardCategory']) == false) $this -> _obj['BoardCategory'] = &ClassRegistry::init('BoardCategory');
		if(isset($this -> _obj['BoardFileRelation']) == false) $this -> _obj['BoardFileRelation'] = &ClassRegistry::init('BoardFileRelation');

		$temp['fields'] = array(
			"id", "board_info_id", "board_category_id", "back_id", "next_id", "depth", "owner_id", "owner_name", "owner_ip", "title", 
			"content", "view_count", "ment_count", "child_count", "subject_bold", "secret", "public", "write", "created", "modified"
		);
		$temp['base_conditions'] = array("board_info_id" => $params['board_info_id']);
		$temp['conditions'] = $temp['base_conditions'];

		if($this -> _board_user['admin'] == "NO")
		{
			$conditions = array("public" => "Y");
			$temp['conditions'] = array_merge($temp['conditions'],$conditions);
			unset($conditions);
		}
		if(ValueIsCheck($params['board_category_id'],"no","not") == true)
		{
			$conditions = array("board_category_id" => $params['board_category_id']);
			$temp['conditions'] = array_merge($temp['conditions'],$conditions);
			unset($conditions);
		}
		$return['chk_date'] = getSearchDate($params,365);
		if(IsEmpty($return['chk_date']['start_timestamp']) == false)
		{
			$conditions = array(
				"UNIX_TIMESTAMP(bdna_write_date) >=" => $return['chk_date']['start_timestamp'],
				"UNIX_TIMESTAMP(bdna_write_date) <=" => $return['chk_date']['end_timestamp']
			);
		}
		if(IsEmpty($params['search_keyword']) == false)
		{
			if(ValueIsCheck($params['search_method'],"all","not") == true && ValueIsCheck($params['search_method'],"no","not") == true)
			{
				unset($conditions);
				$conditions = array($params['search_method']." LIKE"=> "%".$params['search_keyword']."%");
				$temp['conditions'] = array_merge($temp['conditions'],$conditions);
			}
			else
			{
				unset($conditions);
				$conditions = array(
																		"AND" => array(
																		"OR" => array(
																									"title LIKE" => "%".$params['search_keyword']."%",
																									"content LIKE" => "%".$params['search_keyword']."%"
																								)
																		)
																		);
				$temp['conditions'] = array_merge($temp['conditions'],$conditions);
			}
		}
		$temp['orderby'] = array(
			"family" => "ASC",
			"field_sort" => "ASC",
		);
		$temp['page_per_data_ea'] = empty($params['page_per_data_ea']) == true ? $this -> _page_per_data_ea : $params['page_per_data_ea'];
		$this -> _obj['BoardArticleController'] -> paginate = array(
				'conditions' => $temp['conditions'],
				'fields' => $temp['fields'],
				'order' => $temp['orderby'],
				'limit' => $temp['page_per_data_ea'],
				'recursive' => -1
		);
		$temp['result'] = $this -> _obj['BoardArticleController'] -> paginate('BoardArticle');
		$return['search_total_count'] = $this -> _obj['BoardArticleController'] -> params['paging']['BoardArticle']['count'];
		$return['search_total_page'] = $this -> _obj['BoardArticleController'] -> params['paging']['BoardArticle']['pageCount'];
		$temp['virtual_num'] = getVirtualNum($this -> _obj['BoardArticleController'] -> params['paging'],"BoardArticle");


		##		전체/검색 개수
		$return['total_count'] = $this -> find('count');


		$temp['size'] = sizeof($temp['result']);
		if($temp['size'] > 0)
		{
			$temp['category_id'] = getFindCode($temp['result'],"BoardArticle","board_category_id");
			$temp['article_id'] = getFindCode($temp['result'],"BoardArticle","id");
			if(IsEmpty($temp['category_id']) == false) 
			{
				$temp['category'] = $this -> _obj['BoardCategory'] -> getDataListByReferenceMany($temp['category_id']);
			}
			if(IsEmpty($temp['article_id']) == false) 
			{
				$temp['relation'] = $this -> _obj['BoardFileRelation'] -> getDataListByReferenceMany($temp['article_id']);
			}
				
			for($i=0;$i<$temp['size'];$i++)
			{
				$data = $temp['result'][$i]['BoardArticle'];
				$space = "";
				for($j=1;$j<=$data['depth'];$j++)
				{
					$space.= "&nbsp;&nbsp;&nbsp;";
				}
				if($data['depth'] > 0) $re = "L->";
				else $re = "";
				$return['result'][$i] = array(
					'virtual_num' => $temp['virtual_num']--,
					'column' => $data,
					'category' => ValueNullCheck($temp['category']['result'][$data['board_category_id']]),
					'file' => ValueNullCheck($temp['relation']['result'][$data['id']]),
					"space" => $space,
					"re" => $re
				);
			}
		}
		else
		{
			$return['result'] = null;
		}

		return $return;
	}




	function getDataListByTotalSearch($params="")
	{
		if(isset($this -> _obj['BoardFileRelation']) == false) $this -> _obj['BoardFileRelation'] = &ClassRegistry::init('BoardFileRelation');
		if(isset($this -> _obj['BoardInfo']) == false) $this -> _obj['BoardInfo'] = &ClassRegistry::init('BoardInfo');

		$temp['fields'] = array(
			"id", "board_info_id", "board_category_id", "owner_id", "owner_name", "owner_ip", "title", "depth", "subject_bold", "secret", 
			"content", "write", "created", "modified"
		);
		$temp['conditions'] = array();
		if($this -> _board_user['admin'] == "NO")
		{
			$conditions = array("public" => "Y");
			$temp['conditions'] = array_merge($temp['conditions'],$conditions);
			unset($conditions);
		}
		if(IsEmpty($params['total_search_keyword']) == false)
		{
			$conditions = array(
																	"OR" => array(
																								"title LIKE" => "%".$params['total_search_keyword']."%",
																								"content LIKE" => "%".$params['total_search_keyword']."%"
																							)
																	);
			$temp['conditions'] = array_merge($temp['conditions'],$conditions);
		}
		$temp['orderby'] = array(
			"family" => "ASC",
			"field_sort" => "ASC",
		);
		$temp['page_per_data_ea'] = empty($params['page_per_data_ea']) == true ? $this -> _page_per_data_ea : $params['page_per_data_ea'];
		$this -> _obj['BoardArticleController'] -> paginate = array(
				'conditions' => $temp['conditions'],
				'fields' => $temp['fields'],
				'order' => $temp['orderby'],
				'limit' => $temp['page_per_data_ea'],
				'recursive' => -1
		);
		$temp['result'] = $this -> _obj['BoardArticleController'] -> paginate('BoardArticle');
		$return['search_total_count'] = $this -> _obj['BoardArticleController'] -> params['paging']['BoardArticle']['count'];
		$return['search_total_page'] = $this -> _obj['BoardArticleController'] -> params['paging']['BoardArticle']['pageCount'];
		$temp['virtual_num'] = getVirtualNum($this -> _obj['BoardArticleController'] -> params['paging'],"BoardArticle");


		##		전체/검색 개수
		$return['total_count'] = $this -> find('count');


		$temp['size'] = sizeof($temp['result']);
		if($temp['size'] > 0)
		{
			$temp['category_id'] = getFindCode($temp['result'],"BoardArticle","board_category_id");
			$temp['article_id'] = getFindCode($temp['result'],"BoardArticle","id");
			$temp['board_id'] = getFindCode($temp['result'],"BoardArticle","board_info_id");
			if(IsEmpty($temp['category_id']) == false) 
			{
//				$temp['category'] = $this -> _obj['BoardCategory'] -> getDataListByReferenceMany($temp['category_id']);
			}
			if(IsEmpty($temp['board_id']) == false) 
			{
				$temp['board'] = $this -> _obj['BoardInfo'] -> getDataListByReferenceMany($temp['board_id']);
			}
			if(IsEmpty($temp['article_id']) == false) 
			{
				$temp['relation'] = $this -> _obj['BoardFileRelation'] -> getDataListByReferenceMany($temp['article_id']);
			}
				
			for($i=0;$i<$temp['size'];$i++)
			{
				$data = $temp['result'][$i]['BoardArticle'];
				$space = "";
				for($j=1;$j<=$data['depth'];$j++)
				{
					$space.= "&nbsp;&nbsp;&nbsp;";
				}
				if($data['depth'] > 0) $re = "L->";
				else $re = "";
				$return['result'][$i] = array(
					'virtual_num' => $temp['virtual_num']--,
					'column' => $data,
//					'category' => ValueNullCheck($temp['category']['result'][$data['board_category_id']]),
					'board' => ValueNullCheck($temp['board']['result'][$data['board_info_id']]),
					'file' => ValueNullCheck($temp['relation']['result'][$data['id']]),
					"space" => $space,
					"re" => $re
				);
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
				"id", "board_info_id", "board_category_id", "title", "content", "subject_bold", "owner_id","owner_name", "owner_password", "secret", "public",
				"family", "field_sort", "depth"
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
			if(IsEmpty($temp['result']['BoardArticle']['id']) == false)
			{
				$return['result'] = array(
					'column' => $temp['result']['BoardArticle']
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
			if(IsEmpty($temp['result']['BoardArticle']['id']) == false)
			{
				$temp['id'] = $temp['result']['BoardArticle']['id'];
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
		if(IsEmpty($temp['result']['BoardArticle']['max_id']) == false)
		{
			$return = $temp['result']['BoardArticle']['max_id'];
		}
		else $return = 1;

		return $return;
	}




	function getDataModifyByHit($id="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array("view_count" => "view_count + 1");
			$temp['conditions'] = array(
				"id" => $id
			);
			if($this->updateAll($temp['fields'],$temp['conditions']) == false) $return['check'] = false;
			else $return['check'] = true;
		}
		else $return['check'] = false;

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
				$data = $temp['result'][$i]['BoardArticle'];
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





	function getDataProcess($params="",$info="")
	{
		if(isset($this -> _obj['BoardFile']) == false) $this -> _obj['BoardFile'] = &ClassRegistry::init('BoardFile');
		if(isset($this -> _obj['BoardFileRelation']) == false) $this -> _obj['BoardFileRelation'] = &ClassRegistry::init('BoardFileRelation');
		if(IsEmpty($params['owner_password']) == false)
		{
			if($params['user_check'] == "NO") $params['owner_password'] = Security::hash($params['owner_password'], null, true);
			else $params['owner_password'] = $params['owner_password'];
		}
		else unset($params['owner_password']);
		$data['BoardArticle'] = $params;
		if(IsEmpty($data['BoardArticle']['id']) == true) 
		{
			##		마지막 정보 알기
			$data['BoardArticle']['back_id'] = $this -> getDataInfoByLastId($data['BoardArticle']['board_info_id']);
			$data['BoardArticle']['owner_ip'] = $_SERVER['REMOTE_ADDR'];

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
			$temp['family'] = $temp['info']['0']['TABLES']['Auto_increment'] * -1;
			$temp['id'] = $temp['info']['0']['TABLES']['Auto_increment'];
			$data['BoardArticle']['family'] = $temp['family'];
		}
		else $temp['id'] = $data['BoardArticle']['id'];
		if(IsEmpty($data) == false)
		{
			if($this->save($data) == true) 
			{
				##		첨부파일 등록
				if($info['column']['file_use'] == "Y") 
				{
					$temp['file'] = $this -> _obj['BoardFile'] -> getDataProcess($params);
					if(IsEmpty($temp['file']['id']) == false)
					{
						$temp['relation'] = $this -> _obj['BoardFileRelation'] -> getDataProcess($params,$temp['id'],$temp['file']['id']);
					}
				}

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
			if($params['user_check'] == "NO") $params['owner_password'] = Security::hash($params['owner_password'], null, true);
			else $params['owner_password'] = $params['owner_password'];
		}
		else unset($params['owner_password']);
		$data['BoardArticle'] = $params;
		if(IsEmpty($data['BoardArticle']['id']) == true) 
		{
			$data['BoardArticle']['owner_ip'] = $_SERVER['REMOTE_ADDR'];
			$data['BoardArticle']['family'] = $params['parent_family'];
			$data['BoardArticle']['field_sort'] = $params['parent_field_sort'] + 1;
			$data['BoardArticle']['depth'] = $params['parent_depth'] + 1;
		}
		if(IsEmpty($data) == false)
		{
			if($this->save($data) == true) 
			{
				$temp['update'] = $this -> getDataModifyByReply($data['BoardArticle']);
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