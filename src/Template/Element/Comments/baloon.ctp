<?php

use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUser = $this->getLoginUser();

$side = "left";

\Cake\I18n\FrozenTime::setToStringFormat('yyyy-MM-dd HH:mm');

if ($comment->flags & Defines::COMMENT_FLAG_SEND_ENGINEER) {
	$side = "right";
}
?>
<div class="comment comment-<?= $side ?> clearfix">
	<div class="comment-body">
		<?= $this->Element('Comments/content',['comment'=>$comment]) ?>
		<div class="comment-time text-right">
			<?= h($comment->modified->format('Y-m-d')) ?><br>
			<?= h($comment->modified->format('H:i')) ?>
		</div>
	</div>
</div>
