<?php
use App\Defines\Defines;

$group_id = $this->getLoginUser('group_id');

$name = $contact->user_name;
$links = [
	[
		'icon'=>'user',
		'url'=>['controller' => 'engineers', 'action' => 'view', $contact->engineer_id],
		'title'=>'技術者情報',
	],
];

if( $group_id == Defines::GROUP_ENTERPRISE_PREMIUM ){
	
	$links[] = [
		'icon'=>'comment-o',
		'url'=>['controller'=>'comments','action'=>'view',$contact->engineer_id , $this->getLoginUser('enterprise_id')],
		'title'=>'コメント',
	];
}

if( $contact->count >= $border && $group_id == Defines::GROUP_ENTERPRISE_FREE ){
	$links = [];
	$name = "****";
}

?>

<tr>
	<td><?= h($name) ?></td>
	<td class="text-right"><?= $contact->search ?></td>
	<td class="text-right"><?= $contact->view ?></td>
	<td class="text-right"><?= $contact->comment ?></td>
	<td class="text-right"><?= $contact->count ?></td>
	<td class="text-center">
		<?php
		foreach( $links as $link ){
			echo $this->Element('linkIcon',['item'=>$link]);
		}
		?>
	</td>
</tr>

