<?php

$items = [
	[
		'label' => 'Home',
		'url' => [
			'controller' => 'home',
		]
	],
	[
		'label' => '自己情報',
		'url' => [
			'controller' => 'users',
			'action' => 'edit-self'
		]
	],
	[
		'label' => '技術者',
		'url' => [
			'controller' => 'engineers',
			'clear' => true,
		]
	],
];

if ($this->getLoginUser('group_id') == \App\Defines\Defines::GROUP_ENTERPRISE_PREMIUM) {
	array_push($items, [
		'label' => 'コメント',
		'url' => [
			'controller' => 'comments',
		]
	]);
}

echo $this->Element('Navi/headerNav', ['items' => $items]);
?>
