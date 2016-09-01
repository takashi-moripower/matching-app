<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Network\Session;


class LoginUserBehavior extends Behavior{

	protected $_session;
	
	public function getLoginUser( $key = NULL ){
		
		$this->_session = \Cake\Network\Request::Session();

		if( $key == NULL ){
			return $this->_session->read('Auth.User');
		}
		return $this->_session->read('Auth.User.'.$key);
		
	}
}