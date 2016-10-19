<?php
use Cake\ORM\TableRegistry;

$table_enterprises = TableRegistry::get('enterprises');
foreach ($comments as $comment) {

	$enterprise = $table_enterprises->get($comment->enterprise_id, ['contain' => 'Users']);
}
?>
<div class="panel panel-success">
	<div class="panel-heading">コメント</div>
	<table class="table table-bordered table-comments">
		<tbody>
		<?php
		\Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');
		

		foreach ($comments as $comment) {
			$enterprise = $table_enterprises->get($comment->enterprise_id, ['contain' => 'Users']);
			$url = ['controller'=>'comments','action'=>'view',$engineer->id,$enterprise->id];
			
			echo "<tr>";
			echo "<td class='col-xs-3'>".$this->Html->link( h($enterprise->user->name) , $url )."</td>";
			echo "<td class='col-xs-3 text-right'>".$comment->count."件</td>";
			echo "<td class='col-xs-3 text-center'>".$comment->modified."</td>";
			echo "<td class='col-xs-3 text-center'><div class='trim'>".h($comment->last->content)."</div></td>";
			echo "</tr>";
		}
		?>
		</tbody>
	</table>

</div>