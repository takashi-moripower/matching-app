<?php

use App\Defines\Templates;
use App\Defines\Defines;

$title = $notice->isNew() ? 'New Notice' : 'Edit Notice';
?>
<div class="container">
	<div class="row">
		<div class="col-lg-8 col-lg-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2>
						<?= $title ?>
					</h2>
				</div>
				<div class="panel-body">
					<?php
					echo $this->Form->create($notice, ['class' => 'form-horizontal',]);
					echo $this->Form->hidden('id');
					$forms = [
						'Content' => $this->Form->textArea('content'),
						'Set Start' => $this->Form->checkbox('start_set'),
						'Start' => $this->Form->input('start', Templates::OPTIONS_DATETIME),
						'Set End' => $this->Form->checkbox('end_set'),
						'End' => $this->Form->input('end', Templates::OPTIONS_DATETIME),
						'Target' => $this->Form->select('flags', Defines::NOTICE_FLAGS),
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
							<?= $this->Form->button(__('Submit')) ?>
						</div>
					</div>


					<?= $this->Form->end() ?>
				</div>
			</div>
		</div>
	</div>	
</div>

<?php $this->append('script') ?>
<script>
	$(function () {

		checkTime($('input[name=start_set][type=checkbox]'), $('select[name^=start]'));
		checkTime($('input[name=end_set][type=checkbox]'), $('select[name^=end]'));
	});


	function checkTime(check, select) {
		check.on('change',function(){
			if (check.prop("checked") === true) {
				select.removeAttr('disabled');
			} else {
				select.attr('disabled', 'disabled');
			}
		});
		check.trigger('change');
	}
</script>
<?php $this->end() ?>