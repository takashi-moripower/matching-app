このメールはシステムより自動配信されています。

<?= App\Defines\Defines::SYSTEM_NAME ?>　にご登録いただき　まことにありがとうございます
以下のアドレスにアクセスして、登録作業を続けてください

<?= $this->Url->build(['controller'=>'users','action'=>'check',$code],true) ?>