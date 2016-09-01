<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Groups'), ['controller' => 'Groups', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Group'), ['controller' => 'Groups', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Comments'), ['controller' => 'Comments', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Comment'), ['controller' => 'Comments', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Enterprises'), ['controller' => 'Enterprises', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Enterprise'), ['controller' => 'Enterprises', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Informations'), ['controller' => 'Informations', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Information'), ['controller' => 'Informations', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th><?= __('Password') ?></th>
            <td><?= h($user->password) ?></td>
        </tr>
        <tr>
            <th><?= __('Group') ?></th>
            <td><?= $user->has('group') ? $this->Html->link($user->group->name, ['controller' => 'Groups', 'action' => 'view', $user->group->id]) : '' ?></td>
        </tr>
        <tr>
            <th><?= __('Facebook') ?></th>
            <td><?= h($user->facebook) ?></td>
        </tr>
        <tr>
            <th><?= __('Google') ?></th>
            <td><?= h($user->google) ?></td>
        </tr>
        <tr>
            <th><?= __('Twitter') ?></th>
            <td><?= h($user->twitter) ?></td>
        </tr>
        <tr>
            <th><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th><?= __('Expunge') ?></th>
            <td><?= $this->Number->format($user->expunge) ?></td>
        </tr>
        <tr>
            <th><?= __('Created') ?></th>
            <td><?= h($user->created) ?></td>
        </tr>
        <tr>
            <th><?= __('Modified') ?></th>
            <td><?= h($user->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Comments') ?></h4>
        <?php if (!empty($user->comments)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('Engineer Id') ?></th>
                <th><?= __('Offer Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Content') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->comments as $comments): ?>
            <tr>
                <td><?= h($comments->id) ?></td>
                <td><?= h($comments->engineer_id) ?></td>
                <td><?= h($comments->offer_id) ?></td>
                <td><?= h($comments->user_id) ?></td>
                <td><?= h($comments->content) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Comments', 'action' => 'view', $comments->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Comments', 'action' => 'edit', $comments->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Comments', 'action' => 'delete', $comments->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comments->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Enterprises') ?></h4>
        <?php if (!empty($user->enterprises)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Name') ?></th>
                <th><?= __('Postalcode') ?></th>
                <th><?= __('Address') ?></th>
                <th><?= __('Phone') ?></th>
                <th><?= __('Establish') ?></th>
                <th><?= __('Capital') ?></th>
                <th><?= __('Employee') ?></th>
                <th><?= __('Crated') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->enterprises as $enterprises): ?>
            <tr>
                <td><?= h($enterprises->id) ?></td>
                <td><?= h($enterprises->user_id) ?></td>
                <td><?= h($enterprises->name) ?></td>
                <td><?= h($enterprises->postalcode) ?></td>
                <td><?= h($enterprises->address) ?></td>
                <td><?= h($enterprises->phone) ?></td>
                <td><?= h($enterprises->establish) ?></td>
                <td><?= h($enterprises->capital) ?></td>
                <td><?= h($enterprises->employee) ?></td>
                <td><?= h($enterprises->crated) ?></td>
                <td><?= h($enterprises->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Enterprises', 'action' => 'view', $enterprises->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Enterprises', 'action' => 'edit', $enterprises->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Enterprises', 'action' => 'delete', $enterprises->id], ['confirm' => __('Are you sure you want to delete # {0}?', $enterprises->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
    <div class="related">
        <h4><?= __('Related Informations') ?></h4>
        <?php if (!empty($user->informations)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th><?= __('Id') ?></th>
                <th><?= __('User Id') ?></th>
                <th><?= __('Title') ?></th>
                <th><?= __('Content') ?></th>
                <th><?= __('Created') ?></th>
                <th><?= __('Modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($user->informations as $informations): ?>
            <tr>
                <td><?= h($informations->id) ?></td>
                <td><?= h($informations->user_id) ?></td>
                <td><?= h($informations->title) ?></td>
                <td><?= h($informations->content) ?></td>
                <td><?= h($informations->created) ?></td>
                <td><?= h($informations->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Informations', 'action' => 'view', $informations->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Informations', 'action' => 'edit', $informations->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Informations', 'action' => 'delete', $informations->id], ['confirm' => __('Are you sure you want to delete # {0}?', $informations->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
