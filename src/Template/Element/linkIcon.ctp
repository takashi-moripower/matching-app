<?php
echo $this->Html->link('<i class="fa fa-' . $item['icon'] . ' fa-fw fa-lg"></i>', $item['url'], ['escape' => false, 'title' => $item['title']]);
?>
