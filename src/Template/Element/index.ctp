<?php

use Cake\Utility\Hash;
use App\Defines\Defines;
?>
<table class='table table-bordered table-striped'>
    <thead>
        <tr>
            <?php foreach ($template['data'] as $t): ?>
            <th class='<?= Hash::get($t, 'hclass', 'text-center') ?>'>
                <?php
                if (Hash::get($t, 'flags', 0) & Defines::INDEX_FLAG_SORTABLE) {
                        echo $this->Paginator->sort(Hash::get($t, 'key'), Hash::get($t, 'label'));
                } else {
                        echo Hash::get($t, 'label');
                }
                ?>
            </th>
                <?php endforeach ?>
                <?php if (isset($template['action'])): ?>
            <th class='text-center'>
                action
            </th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($entities as $entity): ?>
        <tr entity_id="<?= $entity->id ?>" 
            <?php if(!empty($entity->user_id)){ echo "user_id=\"{$entity->user_id}\"";}?>
            <?php if(!empty($entity->user->name)){ echo "name=\"{$entity->user->name}\"";}?>
        >
                <?php foreach ($template['data'] as $t): ?>
            <td class='<?= Hash::get($t, 'data_class') ?>'>
                <?php
                    $key = Hash::get($t, 'data_key', Hash::get($t, 'key'));
                    $value = Hash::get($entity, $key);

                    if (Hash::get($t, 'flags', 0) & Defines::INDEX_FLAG_NO_ESCAPE) {
                            echo $value;
                    } else {
                            echo h($value);
                    }
                ?>
            </td>
                <?php endforeach ?>
                <?php
                    if (!empty($template['action'])) {
                        echo "<td class='action link text-center'>";
                        foreach ($template['action'] as $action) {
                                $key = Hash::get($action, 'key', 'id');
                                $url = Hash::get($action, 'url', []);
                                if( is_array($url) ){
                                        $url[] = Hash::get($entity, $key);
                                }
                                if(is_callable( $url )){
                                        $url = $url($entity);
                                }
                                $options = Hash::get($action, 'options', []) + ['escape' => false];
                                echo $this->Html->link(Hash::get($action, 'label'), $url, $options);
                                echo ' ';
                        }
                        echo "</td>";
                    }
                ?>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>