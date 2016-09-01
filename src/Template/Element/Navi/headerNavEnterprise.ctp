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
		'label' => '技術者',
		'url' => [
			'controller' => 'engineers',
		]
	],
];

echo $this->Element('Navi/headerNav',['items'=>$items]);
?>
