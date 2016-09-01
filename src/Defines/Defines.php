<?php
namespace App\Defines;

class Defines{
	const SYSTEM_NAME = 'Engineer Matching System';
	const SYSTEM_NAME_SHORT = 'E.M.S.';
	const SYSTEM_EMAIL = 'takashi@moripower.jp';
	
	const GROUP_ADMINISTRATOR = 1;
	const GROUP_ENGINEER = 2;
	const GROUP_ENTERPRISE_PREMIUM = 3;
	const GROUP_ENTERPRISE_FREE = 4;
	
	const GROUP_NAME = [
		self::GROUP_ADMINISTRATOR => '管理者',
		self::GROUP_ENGINEER => '技術者',
		self::GROUP_ENTERPRISE_PREMIUM => '企業(プレミア）',
		self::GROUP_ENTERPRISE_FREE => '企業',
	];
	
	static function isEnterprise( $group_id ){
		return in_array( $group_id , [ self::GROUP_ENTERPRISE_PREMIUM , self::GROUP_ENTERPRISE_FREE ]);
	}
	
	const INFORMATION_PUBLISH_FALSE = 0;
	const INFORMATION_PUBLISH_TRUE = 1;
	
	const ATTRIBUTE_TYPE_LANGUAGE = 1;
	const ATTRIBUTE_TYPE_CAREER = 2;
	const ATTRIBUTE_TYPE_LISENCE = 3;
	const ATTRIBUTE_TYPE_OTHER = 4;
	
	const ATTRIBUTE_TYPE_MAX = 4;
	
	const ATTRIBUTE_TYPE_NAME = [
		self::ATTRIBUTE_TYPE_LANGUAGE => '言語',
		self::ATTRIBUTE_TYPE_CAREER => '経歴',
		self::ATTRIBUTE_TYPE_LISENCE => '資格',
		self::ATTRIBUTE_TYPE_OTHER => 'その他',
	];
	
	
	const CONTACT_RECORD_SEARCH = 0x01;
	const CONTACT_RECORD_VIEW = 0x02;
	const CONTACT_RECORD_COMMENT = 0x04;
	const CONTACT_RECORD_OFFER = 0x08;
	const CONTACT_RECORD_DENY = 0x10;
	
	const CONTACT_RECORD_NAME = [
		self::CONTACT_RECORD_SEARCH => '検索',
		self::CONTACT_RECORD_VIEW => '閲覧',
		self::CONTACT_RECORD_COMMENT => 'コメント',
		self::CONTACT_RECORD_OFFER => '勧誘',
		self::CONTACT_RECORD_DENY => '拒絶'
	];
	
	const COMMENT_TYPE_ENGINEER_TO_ENTERPRISE = 1;
	const COMMENT_TYPE_ENTERPRISE_TO_ENGINEER = 2;
	
	
	const OFFER_OPERATION_AND = 0;
	const OFFER_OPERATION_OR = 1;
	
	const OFFER_OPERATION_NAME = [
		self::OFFER_OPERATION_AND => 'AND',
		self::OFFER_OPERATION_OR => 'OR',
	];
	
	
	const COMMENT_FLAG_SEND_ADMINISTRATOR = 0;
	const COMMENT_FLAG_SEND_ENGINEER = 1;
	const COMMENT_FLAG_SEND_ENTERPRISE = 2;
	const COMMENT_FLAG_SEND_MASK = 0x0f;
	
	const COMMENT_FLAG_READ_ENTERPRISE = 0x10;
	const COMMENT_FLAG_READ_ENGINEER = 0x20;
	const COMMENT_FLAG_READ_MASK = 0xf0;
	
	const USER_EXPUNGE_CHECKING = 2;
	const USER_EXPUNGE_DEAD = 1;
	const USER_EXPUNGE_ALIVE = 0;

	const USER_EXPUNGE_TRUE = 1;
	const USER_EXPUNGE_FALSE = 0;
}