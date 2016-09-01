<?php

namespace App\Controller\Component;
use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class LoginHelperComponent extends Component{
	/**
	 * 
	 * @param type $result	event
	 * @param type $auth	array user data
	 */
	public function afterIdentify( $result , $auth ){
		$table_u = TableRegistry::get('Users');
		$user = $table_u->get( $auth['id']);
		$auth['name'] = $user->name;
		
		return $auth;
	}
}