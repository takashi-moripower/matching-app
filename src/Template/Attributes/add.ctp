<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Attributes'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="attributes form large-9 medium-8 columns content">
    <?= $this->Form->create($attribute) ?>
    <fieldset>
        <legend><?= __('Add Attribute') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo $this->Form->input('type');
            echo $this->Form->input('note');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
