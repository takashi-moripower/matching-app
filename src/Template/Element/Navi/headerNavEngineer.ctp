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
		'label' => 'コメント',
		'url' => [
			'controller' => 'comments',
			'action'=>'index'
		]
	],
];

echo $this->Element('Navi/headerNav',['items'=>$items]);
?>
