<?php

use App\Defines\Templates;
use App\Defines\Defines;

$COURSE_OPTIONS = [
    Defines::GROUP_ENTERPRISE_PREMIUM => Defines::GROUP_NAME[Defines::GROUP_ENTERPRISE_PREMIUM],
    Defines::GROUP_ENTERPRISE_FREE => Defines::GROUP_NAME[Defines::GROUP_ENTERPRISE_FREE],
];


$this->append('script');
echo $this->Html->script('https://yubinbango.github.io/yubinbango/yubinbango.js');
$this->end();

echo $this->Form->create($enterprise,['class'=>'h-adr']);

?>
<span class="p-country-name" style="display:none;">Japan</span>
<div class="col-lg-8 col-lg-offset-2">
    <h2><?= h($enterprise->user->name) ?></h2>
	<?= $this->Element('Users/editTabs', ['user' => $enterprise->user]) ?>
    <div class="panel panel-default panel-under-tab">
        <div class="panel-body">

            <?php
                $forms = [
                        '郵便番号' => $this->Form->input('postalcode', ['label' => false,'class'=>'p-postal-code']),
                        '住所' => $this->Form->input('address', ['label' => false, 'class' => 'p-region p-locality p-street-address p-extended-address']),
                        '電話番号' => $this->Form->input('phone', ['label' => false]),
                        '担当者' => $this->Form->input('personnel_name', ['label' => false]),
                        '担当者電話' => $this->Form->input('personnel_phone', ['label' => false]),
                        '担当者Email' => $this->Form->input('personnel_email', ['label' => false]),
                        '設立' => $this->Form->input('establish', Templates::OPTIONS_DATE + ['minYear' => 1900]),
                        '資本金'=>$this->Form->input('capital',['type'=>'number','label'=>false]),
                        '従業員数'=>$this->Form->input('employee',['type'=>'number','label'=>false]),
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
