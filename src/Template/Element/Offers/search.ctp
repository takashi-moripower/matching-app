<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$type_name = Defines::ATTRIBUTE_TYPE_NAME;
?>
<?= $this->Form->create() ?>
<div class="panel panel-default panel-search">
	<div class="panel-heading clearfix">
		募集元
	</div>
	<div class="panel-body">
		<?= $this->Form->select('enterprise', ['' => '企業名'] + $enterprises) ?>
	</div>

	<?php foreach ($attributes as $type => $attrs): ?>
		<div class="panel-heading clearfix">
			<?= h($type_name[$type]) ?>
		</div>
		<div class="panel-body">
			<ul class="nav nav-pills nav-stacked">
				<?php
				$values = Hash::get($this->request->data, "attributes.{$type}", []);
				foreach ($attrs as $id => $name):
					$checked = in_array($id, $values);
					?>
					<li>
						<?= $this->Form->checkbox('attributes[' . $type . '][]', ['value' => $id, 'hiddenField' => false, 'checked' => $checked]) ?>
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