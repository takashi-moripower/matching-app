<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$type_name = Defines::ATTRIBUTE_TYPE_NAME;

$attribute_selected = array_map(function($obj) {
	return $obj->id;
}, $offer->attributes);
?>
<?php
echo $this->Form->create($offer, ['class' => 'form-horizontal']);
echo $this->Form->hidden('attributes[_ids]', [ 'value' => NULL]);
?>
<div class="panel panel-default panel-under-tab">
	<table class="table">
		<tbody>
			<tr>
				<th>タイトル</th>
				<td><?= $this->Form->text('title') ?></td>
			</tr>
			<?php foreach ($attributes as $type => $list): ?>
				<tr>
					<th>
			<div>
				<?= h($type_name[$type]) ?><br>
			</div>
			<div class="btn-group" data-toggle="buttons">
				<?php
				//デフォルトではAND検索
				foreach ( Defines::OFFER_OPERATION_NAME as $value => $label ):
					$key = 'operation'.$type;
					$checked = ($value == $offer->{$key});
					?>
					<label class="btn btn-default btn-xs <?= $checked ? 'active' : '' ?>">
						<input type="radio" name="<?= $key ?>" value='<?= $value ?>' <?= $checked ? 'checked="checked"' : '' ?>> <?= strtoupper($label) ?>
					</label>
					<?php
				endforeach
				?>
			</div>
			</th>
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
	<?php echo $this->Form->end(); ?>
</div>

