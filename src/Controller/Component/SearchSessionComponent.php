<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;
//use App\Utils\AppUtility;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;

/**
 * 検索条件をセッションに保存、読み込む
 */
class SearchSessionComponent extends Component {

	protected $_actions = ['index'];
	protected $_new_request = [];

	public function initialize(array $config) {
		parent::initialize($config);
		$this->_actions = isset($config['actions']) ? $config['actions'] : $this->_actions;
	}

	/**
	 * indexアクションの前に割り込む
	 * @param \Cake\Event\Event $event
	 */
	public function startup(\Cake\Event\Event $event) {
		if (in_array($this->request->action, $this->_actions)) {
			return $this->queryToSession();
		}
	}

	public function queryToSession() {
		$controller = $this->_registry->getController();
		
		//仕切り直しが必要かどうかの判断
		if ($this->_isRedirect()){
			$this->writeSession($this->_new_request);
			return $controller->redirect([]);
		}

		//	処理済みの検索情報をセッションに保存		
		$this->writeSession($this->_new_request);

		//	controller->indexにも処理済みの検索条件を渡す
		$this->request->data = $this->_new_request;

		//	ページネーターに情報を渡す
		if (isset($this->_new_request['limit'])) {
			$controller->paginate['limit'] = $this->_new_request['limit'];
		}
		
		if (isset($this->_new_request['sort']) && isset($this->_new_request['direction'])) {
			$controller->paginate['order'] = [
				$this->_new_request['sort'] => $this->_new_request['direction']
			];
		}
	}

	protected function _isRedirect() {
		//query と postデータを統合
		$request = $this->request->data + $this->request->query;
		//session読み込み
		$session = $this->readSession();

		//session情報とrequestを統合　（競合した場合はrequestが優先）
		$this->_new_request = $request + $session;


		//	clearフラグが設定されていた場合
		//	セッション全情報をクリアしてリダイレクト
		if (!empty($request['clear'])) {
			$this->_new_request = [];
			return true;
		}

		//	一ページ当たりの表示数が変更されていた場合
		//	セッションのページ数をリセットしてリダイレクト
		if (isset($session['limit']) && Hash::get($session, 'limit') != Hash::get($request, 'limit')) {
			$this->_new_request['page'] = 1;
			return true;
		}

		//	searchフラグが設定されていた = 検索条件変更直後の場合
		//	searchフラグは消去
		//	セッションのページ数をリセットしてリダイレクト
		if (!empty($request['search'])) {
			unset($this->_new_request['search']);
			$this->_new_request['page'] = 1;
		}
		return false;
	}

	/**
	 * コントローラー名、アクション名を含む　セッション識別名を返す
	 * @return type
	 */
	protected function _getSessionName() {
		$controller = $this->_registry->getController();
		$controller_name = Inflector::underscore($controller->name);
		$action_name = Inflector::underscore($this->request->action);

		return "search.{$controller_name}.{$action_name}";
	}

	/**
	 * セッション読み込み　存在しない場合空配列を返す
	 * @return type
	 */
	public function readSession() {
		return (array)$this->request->session()->read($this->_getSessionName());
	}

	/**
	 * セッション書き込み
	 * @param type $data
	 */
	public function writeSession($data) {
		$this->request->session()->write($this->_getSessionName(), $data);
	}

}
