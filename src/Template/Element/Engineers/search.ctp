<?php
use App\Defines\Defines;
use Cake\Utility\Hash;

$type_name = Defines::ATTRIBUTE_TYPE_NAME;

$age_options = ['' => '無制限'];
for ($i = 18; $i < 100; $i++) {
	$age_options[$i] = $i . '才';
}
?>
<?= $this->Form->create() ?>
<div class="panel panel-default panel-search">
	<div class="panel-heading clearfix">
		年齢
	</div>
	<div class="panel-body">
		<?= $this->Form->select('age_min', $age_options, ['class' => 'age']) ?> ～
		<?= $this->Form->select('age_max', $age_options, ['class' => 'age']) ?>
	</div>

	<?php foreach ($attributes as $type => $attrs): ?>
		<div class="panel-heading clearfix">
			<?= h($type_name[$type]) ?>
			<div class="btn-group pull-right" data-toggle="buttons">
				<?php
				//デフォルトではAND検索
				$operation = Hash::get( $this->request->data ,  "attributes.{$type}.operation" );
				foreach ( Defines::OFFER_OPERATION_NAME as $value => $label ):
					$checked = ( $operation == $value );
					?>
					<label class="btn btn-default btn-xs <?= $checked ? 'active' : '' ?>">
						<input type="radio" name="attributes[<?= $type ?>][operation]" value='<?= $value ?>' <?= $checked ? ' checked="checked"' : '' ?>><?= $label ?>
					</label>
				<?php endforeach ?>
			</div>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<?php
				$values = empty($this->request->data['attributes'][$type]['values']) ? [] : $this->request->data['attributes'][$type]['values'];
				foreach ($attrs as $id => $name):
					$checked = in_array($id, $values);
					?>
					<li>
						<?= $this->Form->checkbox('attributes[' . $type . '][values][]', ['value' => $id, 'hiddenField' => false, 'checked' => $checked]) ?>
						<?= h($name) ?>
					</li>
				<?php endforeach ?>
			</ul>
		</div>
	<?php endforeach ?>
</div>
<div class='btn-group'>
	<?php
	echo $this->Form->button('search', ['class' => '']);
	echo $this->Form->button('clear', ['name' => 'clear', 'value' => 1, 'class' => '']);
	?>
</div>
<?php
echo $this->Form->end();
?>