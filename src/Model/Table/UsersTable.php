<?php

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Defines\Defines;

/**
 * Users Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Groups
 * @property \Cake\ORM\Association\HasMany $Comments
 * @property \Cake\ORM\Association\HasMany $Enterprises
 * @property \Cake\ORM\Association\HasMany $Informations
 */
class UsersTable extends Table {

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config) {
        parent::initialize($config);

        $this->table('users');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Acl.Acl', ['type' => 'requester']);

        $this->belongsTo('Groups', [
            'foreignKey' => 'group_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Comments', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Enterprises', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasOne('Engineers', [
            'foreignKey' => 'user_id'
        ]);
        $this->hasMany('Informations', [
            'foreignKey' => 'user_id',
            'sort' => ['order_num' => 'ASC']
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator) {
        $validator
                ->integer('id')
                ->allowEmpty('id', 'create');

        $validator
                ->email('email')
                ->requirePresence('email', 'create')
                ->notEmpty('email')
                ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table',]);

        $validator
                ->requirePresence('password', 'create')
                ->notEmpty('password', 'create');

        $validator
                ->allowEmpty('facebook');

        $validator
                ->allowEmpty('google');

        $validator
                ->allowEmpty('twitter');

        $validator
                ->integer('expunge')
                ->allowEmpty('expunge');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules) {
        $rules->add($rules->isUnique(['email']));
        $rules->add($rules->existsIn(['group_id'], 'Groups'));
        return $rules;
    }

    /**
     * Userに紐づけられたデータを消去したあとUserを消去
     * Informations,Files,
     * Engineers,AttributesEngineers
     * Enterprises,Offers
     * Comments
     * @param type $user
     */
    public function deleteAssociated($user) {
        $table_i = TableRegistry::get('Informations');
        $table_f = TableRegistry::get('Files');

        $informations = $table_i->find()
                ->where(['user_id' => $user->id])
                ->select(['id']);

        $files = $table_f->query()
                ->delete()
                ->where(['information_id IN' => $informations])
                ->execute();

        $table_i->query()
                ->delete()
                ->where(['user_id' => $user->id])
                ->execute();


        //技術者情報
        $table_eg = TableRegistry::get('Engineers');
        $table_ae = TableRegistry::get('AttributesEngineers');

        $engineers = $table_eg->find()
                ->select(['id'])
                ->where(['user_id' => $user->id]);

        $table_ae->query()
                ->delete()
                ->where(['engineer_id IN' => $engineers])
                ->execute();

        $table_eg->query()
                ->delete()
                ->where(['user_id' => $user->id])
                ->execute();

        //企業情報
        $table_ep = TableRegistry::get('Enterprises');
        $table_o = TableRegistry::get('Offers');

        $enterprises = $table_ep->find()
                ->select('id')
                ->where(['user_id' => $user->id]);

        $table_o->query()
                ->delete()
                ->where(['enterprise_id IN' => $enterprises])
                ->execute();

        $table_ep->query()
                ->delete()
                ->where(['user_id' => $user->id])
                ->execute();


        //コメント
        $table_c = TableRegistry::get('Comments');

        $table_c->query()
                ->delete()
                ->where(['or' => ['engineer_id IN' => $engineers, 'enterprise_id IN' => $enterprises]])
                ->execute();

        $this->delete($user);
    }

    public function getLoginData($user_id) {
        $user = $this->get($user_id);
        unset($user->password);
        unset($user->created);
        unset($user->modified);

        switch ($user->group_id) {
            case Defines::GROUP_ENGINEER:
                $engineer = TableRegistry::get('Engineers')->find()
                        ->where(['user_id' => $user->id])
                        ->select(['id'])
                        ->first();

                if (!empty($engineer->id)) {
                    $user->engineer_id = $engineer->id;
                }
                break;

            case Defines::GROUP_ENTERPRISE_PREMIUM:
            case Defines::GROUP_ENTERPRISE_FREE:
                $enterprise = TableRegistry::get('Enterprises')->find()
                        ->where(['user_id' => $user->id])
                        ->select(['id'])
                        ->first();
                if (!empty($enterprise->id)) {
                    $user->enterprise_id = $enterprise->id;
                }
                break;
        }
        return $user->toArray();
    }

    /**
     * Email登録処理　ランダムコードをデータベースに保存後、メール送信
     * @param type $data
     */
    public function setChecker($data) {
        $table_o = TableRegistry::get('Options');


        //ランダムコード生成　万一重複していたらやり直し
        while (1) {
            $code = \App\Utils\AppUtility::makeRandStr(30);
            $title = "mailAliveCheck.{$code}";
            if (!$table_o->exists(['title' => $title])) {
                break;
            }
        }

        $content = serialize($data);

        $opt = $table_o->newEntity([
            'title' => $title,
            'content' => $content,
            'autoload' => 0,
                ]
        );

        $table_o->save($opt);

        $email = new \Cake\Network\Email\Email();
        $email
                ->transport('default')
                ->viewVars(['code' => $code])
                ->from([Defines::SYSTEM_EMAIL => Defines::SYSTEM_NAME])
                ->subject(Defines::SYSTEM_NAME . '登録手続き')
                ->to($data['email'])
                ->template('check')
                ->send();
    }

}
