<?php
class BoardFileRelation extends BoardAppModel {
	var $name = 'BoardFileRelation';

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
	function _init($o="")
	{
		$this -> _obj = $o;
	}



	function getDataListByReferenceMany($id="")
	{
		if(isset($this -> _obj['BoardFile']) == false) $this -> _obj['BoardFile'] = &ClassRegistry::init('BoardFile');
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array("board_file_id", "board_article_id");
			$temp['conditions'] = array("board_article_id" => $id);
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
				$pt = 0;
				for($i=0;$i<$temp['size'];$i++)
				{
					$data = $temp['result'][$i]['BoardFileRelation'];
					$temp['file_id'] = getFindCode($temp['result'],"BoardFileRelation","board_file_id");

					if(IsEmpty($temp['file_id']) == false) 
					{
						$temp['file'] = $this -> _obj['BoardFile'] -> getDataListByReferenceMany($temp['file_id']);
					}

					if(IsEmpty($chk[$data['board_article_id']]) == true)
					{
						$chk[$data['board_article_id']] = $data['board_article_id'];
						$pt = 0;
					}

					$return['result'][$data['board_article_id']][$pt++] = $temp['file']['result'][$data['board_file_id']];
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



	function getDataProcess($params="",$board_article_id="",$board_file_id="")
	{
		if(sizeof($board_file_id) > 0)
		{
			##		기존 데이타 모두 지우기
			$temp['delete'] = $this -> getDataDeleteByArticle($board_article_id);
			if($temp['delete']['check'] == false)
			{
				$return['check'] = false;
				return $return;
			}

			for($i=0;$i<sizeof($board_file_id);$i++)
			{
				$data['BoardFileRelation']['board_article_id'] = $board_article_id;
				$data['BoardFileRelation']['board_file_id'] = $board_file_id[$i];
				if(IsEmpty($data) == false)
				{
					if($this->saveAll($data) == false) 
					{
						$return['check'] = false;
						return $return;
					}
				}
				else 
				{
					$return['check'] = false;
					return $return;
				}

				unset($data);
			}
		}

		$return['check'] = true;

		return $return;
	}




	function getDataDeleteByArticle($board_article_id="")
	{
		if(IsEmpty($board_article_id) == false)
		{
			$temp['conditions'] = array("board_article_id" => $board_article_id);
			if(IsEmpty($temp['conditions']) == false)
			{
				if($this -> deleteAll($temp['conditions']) == false) 
				{
					$return['check'] = false;
					return $return;
				}
			}
			else
			{
				$return['check'] = false;
				return $return;
			}
		}
		else $return['check'] = false;

		$return['check'] = true;
		return $return;
	}
}
?>