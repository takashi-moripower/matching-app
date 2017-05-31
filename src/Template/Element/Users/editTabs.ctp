<?php

use App\Defines\Defines;

if ($user->isNew()) {
    $tabs = [
            '新規作成' => NULL
    ];
} else {
    if ($user->id == $this->getLoginUser('id')) {
        $tabs = [
            'ログイン情報' => ['controller' => 'users', 'action' => 'edit_self']
        ];

        switch ($user->group_id) {
            case Defines::GROUP_ENGINEER:
                $tabs += [
                    '連絡先' => ['controller' => 'engineers', 'action' => 'address_self'],
                    '技能・資格' => ['controller' => 'engineers', 'action' => 'attributes_self'],
                    '追加情報' => ['controller' => 'informations', 'action' => 'management_self'],
                ];
                break;

            case Defines::GROUP_ENTERPRISE_PREMIUM:
            case Defines::GROUP_ENTERPRISE_FREE:
                $tabs += [
                    '連絡先' => ['controller' => 'enterprises', 'action' => 'address_self'],
                    '追加情報' => ['controller' => 'informations', 'action' => 'management_self'],
                ];
                break;
        }
    } else {
        $tabs = [
                'ログイン情報' => ['controller' => 'users', 'action' => 'edit', $user->id]
        ];

        switch ($user->group_id) {
            case Defines::GROUP_ENGINEER:
                $tabs += [
                    '連絡先' => ['controller' => 'engineers', 'action' => 'address', $user->id],
                    '技能・資格' => ['controller' => 'engineers', 'action' => 'attributes', $user->id],
                    '追加情報' => ['controller' => 'informations', 'action' => 'management', $user->id],
                ];
                break;

            case Defines::GROUP_ENTERPRISE_PREMIUM:
            case Defines::GROUP_ENTERPRISE_FREE:
                $tabs += [
                    '連絡先' => ['controller' => 'enterprises', 'action' => 'address', $user->id],
                    '追加情報' => ['controller' => 'informations', 'action' => 'management', $user->id],
                ];
                break;
        }
    }
}
?>


<ul class="nav nav-tabs">
    <?php
        foreach ($tabs as $label => $url):
            if (empty($url)):
                    echo "<li role='presentation' class='active' ><a>{$label}</a></li>";
            else:
    ?>
                <li role="presentation" class="<?= $this->isMatch($url['controller'], $url['action']) ? 'active' : '' ?>">
                    <?= $this->Html->link($label, $url) ?>
                </li>
            <?php
                endif;
            ?>

    <?php 
    endforeach 
    ?>
</ul>			
