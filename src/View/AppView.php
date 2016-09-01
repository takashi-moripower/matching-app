<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\View;

use Cake\View\View;
use App\Utils\AppUtility;
use Cake\Event\Event;
use Cake\Event\EventListenerInterface;
use App\Defines\Defines;
use Cake\Utility\Hash;



/**
 * Application View
 *
 * Your application’s default view class
 *
 * @link http://book.cakephp.org/3.0/en/views.html#the-app-view
 */
use BootstrapUI\View\UIViewTrait;

class AppView extends View {

	use UIViewTrait;

	/**
	 * Initialization hook method.
	 *
	 * Use this method to add common initialization code like loading helpers.
	 *
	 * e.g. `$this->loadHelper('Html');`
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		$this->loadHelper('Html', ['className' => 'BootstrapUI.Html']);
		$this->loadHelper('Form', ['className' => 'BootstrapUI.Form']);
		$this->loadHelper('Flash', ['className' => 'BootstrapUI.Flash']);
		$this->loadHelper('Paginator', ['className' => 'BootstrapUI.Paginator']);
		
		$this->loadHelper('Acl' , ['className'=>'TakashiMoripower/AclManager.Acl']);

		$events = $this->eventManager();
		$events->on('View.beforeRender', [$this, 'beforeRender']);
	}

	public function beforeRender(Event $event, $viewFile) {
		$this->_setDateFormat();
	}

	protected function _setDateFormat() {
		$this->Form->templates(['dateWidget' => '{{year}}年{{month}}月{{day}}日　　　{{hour}}時{{minute}}分']);
		\Cake\I18n\Time::setToStringFormat('yy/MM/dd HH:mm');
	}

	public function getAction($splitter = "_") {
		$action = AppUtility::snake($this->request->action, $splitter);
		return $action;
	}

	public function getController($splitter = "_") {
		$controller = AppUtility::snake($this->name, $splitter);
		return $controller;
	}

	public function isMatch($controller = NULL, $action = NULL) {
		if ($action != NULL) {
			if (is_array($action) && !in_array($this->getAction(), $action)) {
				return false;
			}
			if ($action != $this->getAction()) {
				return false;
			}
		}

		if ($controller != NULL) {
			if (is_array($controller) && !in_array($this->getController(), $controller)) {
				return false;
			}
			if ($controller != $this->getController()) {
				return false;
			}
		}

		return true;
	}

	public function getLoginUser($key = NULL) {

		$auth = $this->request->Session()->read('Auth');

		if ($key != NULL) {
			$key = "User.{$key}";
		} else {
			$key = "User";
		}

		return Hash::get((array) $auth, $key);
	}

	protected function _getBodyClass() {
		$class = [
			'action-' . appUtility::snake($this->request->action, '-'),
			'controller-' . appUtility::snake($this->request->controller, '-'),
		];

		return implode(' ', $class);
	}
}
