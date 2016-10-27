<?php

$items = [
	[
		'label' => 'Home',
		'url' => [
			'controller' => 'home',
		]
	],
	[
		'label' => '技術者',
		'url' => [
			'controller' => 'engineers',
		]
	],
	[
		'label' => '企業',
		'url' => [
			'controller' => 'enterprises',
		]
	],
	[
		'label' => '技能・資格',
		'url' => [
			'controller' => 'attributes',
		]
	],
	[
		'label' => '求人',
		'url' => [
			'controller' => 'offers',
		]
	],
	[
		'label' => '関連性',
		'url' => ['controller' => 'contacts', 'action' => 'index']
	],
	[
		'label' => 'コメント',
		'url' => ['controller' => 'comments', 'action' => 'index']
	],
	[
		'label' => '通知',
		'url' => ['controller' => 'notices', 'action' => 'index']
	],
	[
		'label' => '権限',
		'url' => ['plugin' => 'TakashiMoripower/AclManager', 'controller' => 'groups', 'action' => 'index']
	],
];

echo $this->Element('Navi/headerNav', ['items' => $items]);
?>
