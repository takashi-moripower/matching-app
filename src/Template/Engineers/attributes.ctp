<?php

use App\Defines\Defines;

$type_name = Defines::ATTRIBUTE_TYPE_NAME;

$attribute_selected = array_map(function($obj) {
	return $obj->id;
}, $engineer->attributes);
?>
<h2><?= h($engineer->user->name) ?></h2>
<?php
echo $this->Form->create($engineer, ['class' => 'form-horizontal']);
echo $this->Form->hidden('attributes[_ids]', [ 'value' => NULL]);
echo $this->Element('Users/editTabs', ['user' => $engineer->user]);
?>
<div class="panel panel-default panel-under-tab">
	<table class="table">
		<tbody>
			<?php foreach ($attributes as $type => $list): ?>
				<tr>
					<th><?= h($type_name[$type]) ?></th>
					<td>
						<?php foreach ($list as $id => $name): ?>
							<div class="col-lg-3 trim">
								<?= $this->Form->checkbox("attributes[_ids][]", ['value' => $id, 'hiddenField' => false, 'checked' => in_array($id, $attribute_selected)]) ?>
								<?= $name ?>
							</div>
						<?php endforeach; ?>
					</td>
				</tr>
			<?php endforeach; ?>
			<tr>
				<td></td>
				<td>
					<?= $this->Form->button(__('保存')) ?>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
	echo $this->Form->end();
	?>
</div>