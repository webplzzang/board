<?php
class BoardFile extends BoardAppModel {
	var $name = 'BoardFile';

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
	var $_board_user;
	function _init($o="")
	{
		$this -> _obj = $o;
	}



	function getDataListByReferenceMany($id="")
	{
		if(IsEmpty($id) == false)
		{
			$temp['fields'] = array("id", "root", "path", "convert", "original");
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
				$pt = 0;
				for($i=0;$i<$temp['size'];$i++)
				{
					$data = $temp['result'][$i]['BoardFile'];
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



	function getDataProcess($params="")
	{
		$pt = 0;
		for($i=1;$i<=$params['file_ea'];$i++)
		{
			if(IsEmpty($params['file_add_convert'.$i]) == false)
			{
				$data['BoardFile']['root'] = $params['file_add_root'.$i];
				$data['BoardFile']['path'] = $params['file_add_path'.$i];
				$data['BoardFile']['convert'] = $params['file_add_convert'.$i];
				$data['BoardFile']['original'] = $params['file_add_original'.$i];
				$data['BoardFile']['owner_id'] = $params['owner_id'];
				$data['BoardFile']['owner_name'] = $params['owner_name'];
				$data['BoardFile']['owner_ip'] = $_SERVER['REMOTE_ADDR'];
				if(IsEmpty($params['file_add_id'.$i]) == false) $data['BoardFile']['id'] = $params['file_add_id'.$i];
				if(IsEmpty($data) == false)
				{
					if($this->saveAll($data) == true) 
					{
						if(IsEmpty($data['BoardFile']['id']) == true) $return['id'][$pt++] = $this -> getLastInsertID();
						else $return['id'][$pt++] = $data['BoardFile']['id'];
						unset($data);
					}
					else 
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
			
		}

		$return['check'] = true;

		return $return;
	}
}
?>