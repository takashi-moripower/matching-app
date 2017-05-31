<?php

use Cake\ORM\TableRegistry;
use App\Defines\Defines;
use App\Utils\AppUtility;

if( empty($type) ){
    $type = 'enterprise';
}

switch( $type ){
    default:
    case 'enterprise':
        
        //フリーコースの企業相手には名前の代わりにハッシュを表示
        if( $this->getLoginUser('group_id') == Defines::GROUP_ENTERPRISE_FREE ){
            $name = AppUtility::nameToHash( $comment->engineer_name );
        }else{
            $name = $comment->engineer_name;
        }
        
        $link = $this->Html->link( $name  , ['controller'=>'comments','action'=>'view',$comment->engineer_id , $comment->enterprise_id] );
        $read = $comment->read_enterprise ? '既読' : '未読';
        break;

    case 'engineer':
        $link = $this->Html->link( $comment->enterprise_name , ['controller'=>'comments','action'=>'view',$comment->engineer_id , $comment->enterprise_id] );
        $read = $comment->read_engineer ? '既読' : '未読';
		
}


echo "<tr>";
echo "<td clas='' >" . $link . "</td>";
echo "<td class='text-center'>" . $comment->direction_text . "</td>";
echo "<td class='text-right'>" . $comment->count . "件</td>";
echo "<td class='text-center'>" . $read . "</td>";
echo "<td class='text-left'><div class='trim' style='width:50rem;max-width:50rem'>" . $comment->content_with_file . "</div></td>";
echo "<td class='text-center'>" . $comment->modified . "</td>";
echo "</tr>";
