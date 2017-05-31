<?php

$types = App\Defines\Defines::ATTRIBUTE_TYPE_NAME;
$title = $attribute->isNew() ? 'New Attribute' : 'Edit Attribute';
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
                        echo $this->Form->create( $attribute , ['class' => 'form-horizontal',]);
                        echo $this->Form->hidden('id');
                        $forms = [
                            'Name' => $this->Form->text('name'),
                            'Type' => $this->Form->select('type',$types),
                            'Note'=>$this->Form->textArea('note'),
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

