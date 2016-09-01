<?php
use Cake\Utility\Hash;
use App\Defines\Defines;

$loginUser = $this->getLoginUser();
?>

<header class="bg-primary">
	<div class="container">
		<div class="row">
			<div class="col-xs-8">
				<a href="<?= $this->Url->build(['controller'=>'home'])?>" class="title">
					<h1 class="hidden-xs hidden-sm"><?= Defines::SYSTEM_NAME ?></h1>
					<h1 class="hidden-md hidden-lg"><?= Defines::SYSTEM_NAME_SHORT ?></h1>
				</a>
			</div>
			<div class="col-xs-4">
				<?php
				if ($loginUser):
					?>
					<div class="dropdown text-right">
						<br>
						<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<?= $loginUser['name'] ?>
							<span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
							<li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'editSelf']) ?>"><i class="fa fa-user"></i>ユーザー設定</a></li>
							<li><a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'logOut']) ?>"><i class="fa fa-sign-out"></i> logout</a></li>
						</ul>
					</div>		
				<?php endif ?>
			</div>
		</div>
	</div>
</header>
<?php
switch( Hash::get( (array)$loginUser , 'group_id') ){
	case Defines::GROUP_ADMINISTRATOR:
		echo $this->Element('Navi/headerNavAdmin');
		break;
	
	case Defines::GROUP_ENGINEER:
		echo $this->Element('Navi/headerNavEngineer');
		break;
	
	case Defines::GROUP_ENTERPRISE_PREMIUM:
	case Defines::GROUP_ENTERPRISE_FREE:
		echo $this->Element('Navi/headerNavEnterprise');
		break;
		
}

?>
<br>