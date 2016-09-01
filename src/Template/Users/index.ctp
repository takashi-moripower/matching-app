<table class="table table-bordered">
	<thead>
		<tr>
			<th>Name</th>
			<th>Group</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $users as $user ): ?>
		<tr>
			<td><?= h($user->name) ?></td>
			<td><?= h($user->group->name ) ?></td>
			<td>
				<?=$this->Html->link('edit',['controller'=>'users','action'=>'edit',$user->id]) ?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<?= $this->Element('paginator') ?>