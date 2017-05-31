<?php

namespace App\Controller;

use App\Controller\AppController;

/**
 * Files Controller
 *
 * @property \App\Model\Table\FilesTable $Files
 */
class FilesController extends AppController {

	public function load($id) {
		$this->autoRender = false;
		$file = $this->Files->get($id);
		$img = stream_get_contents($file->content);

		$reg = "/(.*)(?:\.([^.]+$))/";
		$matches = [];
		$result = preg_match($reg, $file->name, $matches);


		if ($result && isset($matches[2])) {
			$this->response->type($matches[2]);
		}
		$this->response->body($img);
	}

}
