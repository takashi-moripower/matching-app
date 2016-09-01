<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Event\Event;
use Cake\Controller\Controller;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;
use Acl\Controller\Component\AclComponent;
use App\Defines\Defines;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public function beforeFilter(Event $event) {
		// デバッグ用　全アクションアクセス可にする
		$this->Auth->allow();
	}

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading components.
	 *
	 * e.g. `$this->loadComponent('Security');`
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		$this->loadComponent('RequestHandler');
		$this->loadComponent('Flash');

		$this->loadComponent('Auth', [
			'authorize' => 'Controller',
			// 権限無しページに飛ぶと無限ループになったり、変なURLにリダイレクトされるのを防ぐ
			'unauthorizedRedirect' => ['plugin'=>NULL,'controller' => 'users', 'action' => 'login'],
			'authError' => 'アクセス権限がありません',
			'authenticate' => [
				'Form' => [
					'fields' => ['username' => 'email']
				]
			]
		]);

		$this->loadComponent('LoginHelper');
		
		$this->Auth->eventManager()->on('Auth.afterIdentify', [$this->LoginHelper, 'afterIdentify']);
		$this->eventManager()->on('beforeAccessControl', [$this, 'beforeAccessControl']);
	}

	/**
	 * Before render callback.
	 *
	 * @param \Cake\Event\Event $event The beforeRender event.
	 * @return void
	 */
	public function beforeRender(Event $event) {
		if (!array_key_exists('_serialize', $this->viewVars) &&
				in_array($this->response->type(), ['application/json', 'application/xml'])
		) {
			$this->set('_serialize', true);
		}
	}

	public function isAuthorized($user) {
		$Collection = new ComponentRegistry();
		$acl = new AclComponent($Collection);
		$controller = $this->request->controller;
		$action = $this->request->action;
		return $acl->check(['Users' => ['id' => $user['id']]], "$controller/$action");
	}
	
	protected function setDateFormat(){
		//	日付の読み込みフォーマットを　年　月　日にする
		\Cake\I18n\Date::$wordFormat = 'yyyy-MM-dd';
		//	日付の出力フォーマットを　年　月　日にする
		\Cake\I18n\Date::setToStringFormat('yyyy-MM-dd');
		
	}

	/**
	 * 検索用コンポーネント詰め合わせ
	 * @param type $actions
	 * 　検索機能を使うアクション、Camel記法で
	 */
	protected function _loadSearchComponents($actions = ['index']) {
		if (in_array($this->request->action, $actions)) {
			$this->loadComponent('MpPrg'); //	検索用Search.Prg　をカスタマイズ
			$this->loadComponent('SearchSession', [ 'actions' => $actions]);  //	検索データをセッションに格納するコンポーネント
			$this->loadComponent('Paginator');

			if (!isset($this->paginate)) {
				$this->paginate = [];
			}
			$this->paginate['limit'] = Configure::read('Index.Limit.Default');
		}
	}
	
	protected function _getLoginUser( $key = NULL ){
		return $this->Auth->user( $key );
	}
	
	
	public function beforeAccessControl( Event $event , $data = NULL ){
		$group_id = $this->_getLoginUser('group_id');
		
		if( $group_id != Defines::GROUP_ADMINISTRATOR ){
			return false;
		}
	}
}
