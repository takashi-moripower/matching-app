<?php

use \Cake\Core\Configure;

$sns = Configure::read('SNS');

foreach ($sns as $strategy):
	?>
	<div class="form-group">
		<label class="control-label col-lg-3"><?= ucfirst($strategy) ?></label>
		<div class="col-lg-9">
			<?php
			if (!empty($user->{$strategy})):
				echo '登録済　';
			else:
				echo '未登録　';
			endif;
			echo $this->Form->hidden($strategy);
			?>
		</div>
	</div>

	<?php
endforeach;
