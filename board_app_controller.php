<?php

class BoardAppController extends AppController 
{
	public $components = array(
		'Auth',
		'Session'
	);

	public $_board_user;
	public $_url;

	public function beforeFilter()
	{
		parent::beforeFilter();
		Configure::load('board.config_constant');
		Configure::load('board.config_description');

		$this -> _url['index'] = "/users/edit";

		if(IsEmpty($this -> user[$this->Auth->getModel()->alias]['User']) == false)
		{
			if(IsEmpty($this -> user[$this->Auth->getModel()->alias]['UserAdmin']['level']) == false) $this -> _board_user['admin'] = "YES";
			else $this -> _board_user['admin'] = "NO";
			$this -> _board_user['check'] = "YES";
			$this -> _board_user['id'] = $this -> user[$this->Auth->getModel()->alias]['User']['username'];
			$this -> _board_user['name'] = $this -> user[$this->Auth->getModel()->alias]['User']['realname'];
			$this -> _board_user['password'] = $this -> user[$this->Auth->getModel()->alias]['User']['password'];
		}
		else
		{
			$this -> _board_user['admin'] = "NO";
			$this -> _board_user['check'] = "NO";
		}

		$this->set('_board_user',$this -> _board_user);
		$this->set('controller',$this -> params['controller']);
	}
}
?>