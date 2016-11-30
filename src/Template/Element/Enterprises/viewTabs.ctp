<?php

use App\Defines\Defines;

$tabs = [
	'企業情報' => ['controller' => 'enterprises', 'action' => 'view', $enterprise_id],
	'求人情報' => ['controller' => 'enterprises', 'action' => 'offers', $enterprise_id],
];

$loginUser = $this->getLoginUser();

if ( $loginUser['group_id'] == Defines::GROUP_ENGINEER ) {
	$tabs += [
		'コメント' => ['controller' => 'comments', 'action' => 'view', $loginUser['engineer_id'] , $enterprise_id ],
	];
}
?>


<ul class="nav nav-tabs">
	<?php foreach ($tabs as $label => $url):
		$match = $this->isMatch($url['controller'], $url['action']);
		if( $label == '求人情報' && $this->isMatch( 'offers' , 'index' )){
			$match = true;
		}
		?>
		<li role="presentation" class="<?= $match ? 'active' : '' ?>">
			<?= $this->Html->link($label, $url) ?>
		</li>
	<?php endforeach ?>
</ul>			
