<?php
namespace App\Defines;

class Templates{
	const DATE01 = '<ul class="list-inline"><li class="year">{{year}}</li><li>年</li><div class="separator hidden-lg"></div><li class="month">{{month}}</li><li>月</li><li class="day">{{day}}</li><li>日</li></ul>';
	const TIME01 = '<ul class="list-inline"><li class="hour">{{hour}}</li><li>時</li><li class="minute">{{minute}}</li><li>分</li></ul>';
	
	
	const OPTIONS_DATE01 = [
		'type' => 'date', 'label' => false, 'monthNames' => false, 'templates' => ['dateWidget' => self::DATE01]
	];
}