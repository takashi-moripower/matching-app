<?php
$loginUser = $this->getLoginUser();
?>
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>
						<?= __('Edit Info') ?>
					</h2>
				</div>
				<div class="panel-body">
					<?php
					echo $this->Form->create($information, ['class' => 'form-horizontal', 'type' => 'file']);
					echo $this->Form->hidden('order');
					$forms = [
						'Title' => $this->Form->text('title', ['label' => false]),
						'Content' => $this->Form->TextArea('content'),
						'Publish' => $this->Form->checkbox('published')
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
					<?php foreach ($information->files as $file): ?>
						<div class="form-group">
							<label class="control-label col-lg-3">Files</label>
							<div class="col-lg-9">
								<a href="<?= $this->Url->build(['controller' => 'files', 'action' => 'load', $file->id, $file->name]) ?>">
									<?= $file->name ?>
								</a>
								削除する &nbsp; <?= $this->Form->checkbox("file_remove[{$file->id}]") ?>
							</div>
						</div>
					<?php endforeach ?>

					<div class="files-end"></div>

					<div class="form-group">
						<label class="control-label col-lg-3">Files</label>
						<div class="col-lg-9">
							<?= $this->Form->button('ファイルを添付', ['type' => 'button', 'class' => 'btn btn-default', 'name' => 'add-file']) ?>
						</div>
					</div>


					<div class="form-group">
						<div class="col-lg-9 col-lg-offset-3">
							<?= $this->Form->button(__('Submit')) ?>
						</div>
					</div>


					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>	
</div>

<div class="hidden file-form-source">
	<div class="form-group">
		<label class="control-label col-lg-3">Files</label>
		<div class="col-lg-9">
			<?= $this->Form->File('files[]') ?>
		</div>
	</div>
</div>

<?php $this->append('script') ?>
<script>
	$(function () {
		$('body').on({
			click: function () {
				html = $('.file-form-source').html();
				$('.files-end').prepend(html);
			}
		}, 'button[name="add-file"]');
	});
</script>
<?php $this->end() ?>