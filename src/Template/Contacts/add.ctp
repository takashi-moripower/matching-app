<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Contacts'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Engineers'), ['controller' => 'Engineers', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Engineer'), ['controller' => 'Engineers', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Enterprises'), ['controller' => 'Enterprises', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Enterprise'), ['controller' => 'Enterprises', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="contacts form large-9 medium-8 columns content">
    <?= $this->Form->create($contact) ?>
    <fieldset>
        <legend><?= __('Add Contact') ?></legend>
        <?php
            echo $this->Form->input('engineer_id', ['options' => $engineers]);
            echo $this->Form->input('enterprise_id', ['options' => $enterprises]);
            echo $this->Form->input('engineer_access');
            echo $this->Form->input('engineer_viewdate', ['empty' => true]);
            echo $this->Form->input('engineer_interest');
            echo $this->Form->input('enterprise_access');
            echo $this->Form->input('enterprise_viewdate', ['empty' => true]);
            echo $this->Form->input('enterprise_interest');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
