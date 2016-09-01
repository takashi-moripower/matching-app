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
			'action'=>'edit-self'
		]
	],
	[
		'label' => '企業',
		'url' => [
			'controller' => 'enterprises',
		]
	],
	[
		'label' => '求人',
		'url' => [
			'controller' => 'offers',
			'action'=>'match',
		]
	],
];

echo $this->Element('Navi/headerNav',['items'=>$items]);
?>
