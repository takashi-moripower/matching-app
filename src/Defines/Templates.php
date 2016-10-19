<?php
namespace App\Defines;

class Templates{
	const DATE = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
	const TIME = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';
	const DATETIME = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';
	
	const OPTIONS_DATE = [
		'type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => self::DATE]
	];
	
	const OPTIONS_DATETIME = [
		'type' => 'datetime', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => self::DATETIME]
	];
	
}