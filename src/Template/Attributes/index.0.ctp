<?php 
use App\Defines\Defines;
$type_name = Defines::ATTRIBUTE_TYPE_NAME;
?>
<?= $this->Html->link('Add New',['action'=>'add']) ?>
<table class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>name</th>
			<th>type</th>
			<th>note</th>
			<th>action</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach( $attributes as $attribute ): ?>
		<tr>
			<td><?= h($attribute->name) ?></td>
			<td><?= $type_name[$attribute->type] ?></td>
			<td><?= $attribute->note ?></td>
			<td><?= $this->Html->link('edit',['action'=>'edit',$attribute->id]) ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<?= $this->element( 'paginator') ?>