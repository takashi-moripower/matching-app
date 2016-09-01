<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>user</th>
			<th>title</th>
			<th>content</th>
			<th>files</th>
			<th>published</th>
			<th>order</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $informations as $information ): ?>
		<tr>
			<td><?= h($information->user->name) ?></td>
			<td><?= h($information->title) ?></td>
			<td><?= h($information->content) ?></td>
			<td><?= h($information->count_files) ?></td>
			<td><?= h($information->published) ?></td>
			<td><?= h($information->order_num) ?></td>
			<td><?= $this->Html->link('edit',['action'=>'edit',$information->id]) ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?= $this->element( 'paginator') ?>