<?php

use App\Defines\Defines;
use Cake\Utility\Hash;

$loginUser = $this->getLoginUser();

if ($loginUser['group_id'] == Defines::GROUP_ADMINISTRATOR || Hash::get($loginUser, 'engineer_id') == $engineer->id) {
	$hide = false;
} else {
	$hide = true;
}
?>

<div class="col-lg-12">
	<?= $this->Element('Engineers/viewTabs', ['engineer_id' => $engineer->id]) ?>
    <div class="panel panel-default panel-under-tab">
        <table class="table table-bordered table-view table-view-engineers">
            <tbody>
                <tr>
                    <th>氏名</th>
                    <td><?= h($engineer->user->engineer_name) ?></td>
                </tr>
                <tr>
                    <th>生年月日</th>
                    <td><?= $engineer->birthday ?></td>
                </tr>
				<?php if ($hide): ?>
                <tr>
                    <th>技能・資格</th>
                    <td><?= h($engineer->attribute_names) ?></td>
                </tr>
				<?php else: ?>
                <tr>
                    <th>最終学歴</th>
                    <td><?= h($engineer->education) ?></td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td><?= h($engineer->tel) ?></td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    <td><?= h($engineer->postalcode) ?></td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td><?= h($engineer->address) ?></td>
                </tr>
                <tr>
                    <th>技能・資格</th>
                    <td><?= h($engineer->attribute_names) ?></td>
                </tr>
					<?php
					foreach ($engineer->user->informations as $information):
						echo $this->Element('Informations/row', ['information' => $information]);
					endforeach
					?>
				<?php endif ?>
            </tbody>
        </table>
    </div>
</div>