<?php
if (!empty($notices)) {
	echo "<br>";
	echo "<div class='panel panel-info'>";
	echo "<div class='panel-heading'>通知</div>";
	echo "<div class='panel-body'>";
	echo "<ul>";
	foreach ($notices as $notice) {
		echo "<li>" . h($notice->content) . "</li>";
	}
	echo "</ul>";
	echo "</div>";
	echo "</div>";
}
