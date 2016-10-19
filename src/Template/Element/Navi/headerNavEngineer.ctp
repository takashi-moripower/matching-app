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
];

echo $this->Element('Navi/headerNav',['items'=>$items]);
?>
