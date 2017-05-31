<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Defines\Defines;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Comment Entity
 *
 * @property int $id
 * @property int $engineer_id
 * @property int $enterprise_id
 * @property int $type
 * @property string $content
 *
 * @property \App\Model\Entity\Engineer $engineer
 * @property \App\Model\Entity\Enterprise $enterprise
 */
class Comment extends Entity {

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    protected function _getSenderName($value) {
        $flag_sender = $this->flags & Defines::COMMENT_FLAG_SEND_MASK;
        switch ($flag_sender) {
            case Defines::COMMENT_FLAG_SEND_ADMINISTRATOR:
                return '管理者';

            case Defines::COMMENT_FLAG_SEND_ENGINEER:
                return $this->engineer->user->name;

            case Defines::COMMENT_FLAG_SEND_ENTERPRISE:
                return $this->enterprise->user->name;
        }
    }

    protected function _getLast($value) {
        if ($value) {
            return $value;
        }
        if (empty($this->enterprise_id) || empty($this->engineer_id)) {
            return NULL;
        }

        $table = TableRegistry::get('comments');

        $last = $table->find()
                ->where(['engineer_id' => $this->engineer_id, 'enterprise_id' => $this->enterprise_id])
                ->order(['modified' => 'desc'])
                ->first();

        $this->last = $last;

        return $last;
    }

    protected function _getContentWithFile() {
        if (empty($this->file)) {
            return $this->content;
        }

        $url = \Cake\Routing\Router::url(['controller' => 'comments', 'action' => 'load', $this->id]);
        $label = h($this->content);
        $text = "<a href='{$url}'>{$label}</a>";

        return $text;
    }

    protected function _getDirectionText() {
        $flag_sender = $this->flags & Defines::COMMENT_FLAG_SEND_MASK;
        switch ($flag_sender) {
            case Defines::COMMENT_FLAG_SEND_ADMINISTRATOR:
                return '管理者';

            case Defines::COMMENT_FLAG_SEND_ENGINEER:
                return '技術者→企業';

            case Defines::COMMENT_FLAG_SEND_ENTERPRISE:
                return '企業→技術者';
        }
    }

}
