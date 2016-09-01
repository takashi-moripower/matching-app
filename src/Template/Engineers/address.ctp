<?php
use App\Defines\Templates;

echo $this->Form->create($engineer);

?>
<div class="col-lg-8 col-lg-offset-2">
	<h2><?= h($engineer->user->name) ?></h2>
	<?= $this->Element('Users/editTabs', ['user' => $engineer->user]) ?>
	<div class="panel panel-default panel-under-tab">
		<div class="panel-body">

			<?php
			$forms = [
				'郵便番号' => $this->Form->input('postalcode', ['label' => false]),
				'住所' => $this->Form->input('address', ['label' => false]),
				'電話番号' => $this->Form->input('phone', ['label' => false]),
				'生年月日' => $this->Form->input('birthday', Templates::OPTIONS_DATE01 + ['minYear' => 1900]),
				'最終学歴' => $this->Form->input('education', ['label' => false]),
			];
			foreach ($forms as $label => $item):
				?>
				<div class="form-group">
					<label class="control-label col-lg-3"><?= $label ?></label>
					<div class="col-lg-9">
						<?= $item ?>
					</div>
				</div>
			<?php endforeach ?>

			<div class="form-group">
				<div class="col-lg-9 col-lg-offset-3">
					<?= $this->Form->button('保存') ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
echo $this->Form->end();
?>
