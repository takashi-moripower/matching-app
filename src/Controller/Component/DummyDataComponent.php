<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use App\Defines\Defines;

class DummyDataComponent extends Component {

	/**
	 * 
	 * @param type $result	event
	 * @param type $auth	array user data
	 */
	public $Attributes;
	public $Users;
	protected $_listAttributes;
	protected $_controller;

	public function initialize(array $config) {
		parent::initialize($config);

		$this->Attributes = TableRegistry::get('Attributes');
		$this->Engineers = TableRegistry::get('Engineers');
		$this->Users = TableRegistry::get('Users');

		$this->_listAttributes = $this->Attributes->find('list');

		$this->_controller = $this->_registry->getController();
	}

	public function enterprises() {
		$r = 0;
		for ($i = 0; $i < 10; $i++) {
			$e = $this->enterprise($i+1);
			if ($this->Users->save($e)) {
				$r++;
			}
		}
		return $r;
	}

	public function enterprise($i) {
		$user = $this->Users->newEntity([
			'name' => sprintf('企業%04d', $i),
			'email' => sprintf('enterprise%04d@dummy.com', $i),
			'password' => '0123',
			'group_id' => ( $i < 5 ) ? Defines::GROUP_ENTERPRISE_FREE : Defines::GROUP_ENTERPRISE_PREMIUM,
			'expunge' => Defines::USER_EXPUNGE_FALSE,
			'enterprise' => [
				'employee' => rand(1, 9999),
				'capital' => rand(1, 9999),
				'establish' => new Date(sprintf('%04d-%02d-%02d', rand(1900, 2000), rand(1, 12), rand(1, 28))),
			]
				], ['associated' => ['Enterprises']]);

		return $user;
	}

	public function engineers() {
		$r = 0;
		for ($i = 0; $i < 100; $i++) {
			$e = $this->engineer($i + 1);
			if ($this->Users->save($e)) {
				$r++;
			}
		}
		return $r;
	}

	public function engineer($num) {

		$attributes = [];
		foreach ($this->_listAttributes as $id => $name) {
			if (rand(1, 3) == 1) {
				$attributes[] = $id;
			}
		}

		$user = $this->Users->newEntity([
			'name' => sprintf('技術者%04d', $num),
			'email' => sprintf('engineer%04d@dummy.com', $num),
			'password' => '0123',
			'group_id' => Defines::GROUP_ENGINEER,
			'expunge' => Defines::USER_EXPUNGE_FALSE,
			'engineer' => [
				'postalcode' => sprintf('%03d-%04d', rand(0, 999), rand(0, 9999)),
				'phone' => sprintf('090-%04d-%04d', rand(0, 9999), rand(0, 9999)),
				'birthday' => new Date(sprintf('%04d-%02d-%02d', rand(1900, 2000), rand(1, 12), rand(1, 28))),
				'attributes' => [ '_ids' => $attributes]
			]
				], ['associated' => ['Engineers' => ['associated' => ['Attributes']]]]);

		return $user;
	}

	public function offers() {
		$enterprises = TableRegistry::get('Enterprises')->find()
				->contain(['Users']);

		$id_attributes = TableRegistry::get('Attributes')->find('list')->toArray();

		$table_o = TableRegistry::get('Offers');


		foreach ($enterprises as $enterprise) {
			for ($i = 0; $i <= 10; $i++) {

				$keys = array_keys($id_attributes);
				shuffle($keys);

				$attributes = array_slice($keys, 0, rand(1, 5));

				$offer = $table_o->newEntity(
						[
					'title' => sprintf('%s-求人%02d', $enterprise->user->name, $i+1),
					'enterprise_id' => $enterprise->id,
					'operation1' => rand(0, 1),
					'operation2' => rand(0, 1),
					'operation3' => rand(0, 1),
					'operation4' => rand(0, 1),
					'attributes' => [ '_ids' => $attributes],
						], [
					'associated' => ['Attributes']
						]
				);
				$table_o->save($offer);
			}
		}
	}

	public function comments() {
		$enterprises = TableRegistry::get('enterprises')
				->find();

		$engineer = TableRegistry::get('engineers')
				->find()
				->first();

		$table_comments = TableRegistry::get('comments');

		foreach ($enterprises as $enterprise) {
			$a = rand(1, 1000) * 10;
			$b = rand(1, 11);
			$c = rand(13, 23);

			$content = "日給{$a}円\r勤務時間は{$b}:00～{$c}:00です";

			$new_comment = $table_comments->newEntity([
				'enterprise_id' => $enterprise->id,
				'engineer_id' => $engineer->id,
				'content' => $content,
				'flags' => Defines::COMMENT_FLAG_SEND_ENTERPRISE,
			]);
			$table_comments->save($new_comment);
		}

		$enterprise = TableRegistry::get('enterprises')
				->find()
				->first();
		for ($i = 0; $i < 10; $i++) {
			$new_comment = $table_comments->newEntity([
				'enterprise_id' => $enterprise->id,
				'engineer_id' => $engineer->id,
				'content' => 'いいえ',
				'flags' => Defines::COMMENT_FLAG_SEND_ENGINEER,
			]);
			$table_comments->save($new_comment);

			$new_comment2 = $table_comments->newEntity([
				'enterprise_id' => $enterprise->id,
				'engineer_id' => $engineer->id,
				'content' => 'そんな　ひどい',
				'flags' => Defines::COMMENT_FLAG_SEND_ENTERPRISE,
			]);
			$table_comments->save($new_comment2);
		}
	}

}
