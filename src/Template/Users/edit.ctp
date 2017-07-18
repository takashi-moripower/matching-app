<?php

use Cake\Core\Configure;
use App\Defines\Defines;

if ($user->isNew()) {
    switch ($user->group_id) {
        case Defines::GROUP_ADMINISTRATOR:
            $title = 'New Administrator';
            break;

        case Defines::GROUP_ENGINEER:
            $title = 'New Engineer';
            break;

        case Defines::GROUP_ENTERPRISE_PREMIUM:
        case Defines::GROUP_ENTERPRISE_FREE:
            $title = 'New Entreprise';
            break;
    }
} else {
    $title = $user->name;
}

$label_name = ( Defines::isEnterprise($user->group_id)) ? '企業名' : '氏名';
?>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
            <h2>
                <?= $title ?>
            </h2>

            <?= $this->Element('Users/editTabs') ?>
            <div class="panel panel-default panel-under-tab">
                <div class="panel-body">
                    <?php
                        $form_options = ['class' => 'form-horizontal'];
                        if (!empty($action)) {
                                $form_options += ['url' => $action];
                        }
                        echo $this->Form->create($user, $form_options );
                        echo $this->Form->hidden('id');
                        echo $this->Form->hidden('expunge');
                        $forms = [
                            $label_name => $this->Form->text('name'),
                            'Email' => $this->Form->text('email'),
                            'Password' => $this->Form->password('password', ['value' => '']),
                        ];
                        if( $this->getLoginUser('group_id') == Defines::GROUP_ADMINISTRATOR && Defines::isEnterprise( $user->group_id ) ){
                            $forms['Group'] = $this->Form->select( 'group_id' , $groups); 
                        }else{
                            echo $this->form->hidden('group_id');
                        }
                    ?>
                    <?php foreach ($forms as $label => $item):?>
                    <div class="form-group">
                        <label class="control-label col-lg-3"><?= $label ?></label>
                        <div class="col-lg-9">
                                <?= $item ?>
                        </div>
                    </div>
                    <?php endforeach ?>

                    <?php
                    if ($this->getAction() == 'edit_self') {
                        echo $this->Element('Users/sns');
                    } else {
                        echo $this->Element('Users/snsConst');
                    }
                    ?>

                    <div class="form-group">
                        <div class="col-lg-9 col-lg-offset-3">
                            <?= $this->Form->button('保存') ?>
                        </div>
                    </div>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>	
</div>

