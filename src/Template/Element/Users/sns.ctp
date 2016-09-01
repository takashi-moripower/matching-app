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
				echo $this->Html->link('<button type="button" class="btn btn-default">登録解除</button>', ['controller' => 'users', 'action' => 'unLinkSns', $user->id, $strategy], ['escape' => false]);
			else:
				echo '未登録　';
				echo $this->Html->link('<button type="button" class="btn btn-default">登録</button>', ['controller' => 'users', 'action' => 'linkSns', $strategy], ['escape' => false]);
			endif;
			?>
		</div>
	</div>

	<?php
endforeach;
