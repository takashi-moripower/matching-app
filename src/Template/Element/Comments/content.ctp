<?php
use Cake\Utility\Hash;

if (empty($comment->file)) {
	echo nl2br(h($comment->content));
	return;
}
$reg = "/(.*)(?:\.([^.]+$))/";
$matches = [];
$result = preg_match($reg, $comment->content, $matches);

$ext = Hash::get($matches,2);

if( in_array( $ext , ['png','jpg','jpeg','bmp','gif'])){
	$url = $this->Url->build(['action'=>'load',$comment->id]);
	echo "<img src='{$url}'>";
	return;
}
echo $this->Html->link( h( $comment->content ) , ['action'=>'load',$comment->id]);