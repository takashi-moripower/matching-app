<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace App\Controller\Component;

use  \Search\Controller\Component\PrgComponent;
/*
 * Search.PrgComponentは勝手にリダイレクトするので
 * オーバーライドして、その機能をキャンセル
 */
class MpPrgComponent extends PrgComponent {
    public function startup()
    {
        if ($this->_actionCheck()) {
            return $this->conversion(false);
        }
    }	
}