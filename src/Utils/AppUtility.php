<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Utils;

/**
 * Description of AppUtility
 *
 * @author tsukasa
 */
class AppUtility {

    /**
     * 配列中の特定の値を削除
     * @return array
     */
    public static function array_remove($array_a, $array_b) {
        foreach ($array_b as $b) {
            $key = array_search($b, $array_a);
            if ($key !== false) {
                unset($array_a[$key]);
            }
        }
        return $array_a;
    }

    /**
     * camelからsnakeへ
     */
    public static function snake($str, $splitter = '_') {
        return ltrim(strtolower(preg_replace('/[A-Z]/', $splitter . '\0', $str)), $splitter);
    }

    /**
     * snakeからcamelへ
     */
    public static function lCamel($str, $splitter = '_') {
        return lcfirst(strtr(ucwords(strtr($str, [$splitter => ' '])), [' ' => '']));
    }

    /**
     * snakeからcamelへ(先頭大文字）
     */
    public static function Camel($str, $splitter = '_') {
        return ucfirst(strtr(ucwords(strtr($str, [$splitter => ' '])), [' ' => '']));
    }

    /**
     * json解釈できる場合はデコードして返す
     * できなかったらそのまま
     * @param type $str
     * @return type
     */
    public static function json_safe_decode($str, $assoc = false, $depth = 512, $options = 0) {
        $result = json_decode($str, $assoc, $depth, $options);
        if ($result == null) {
            return $str;
        } else {
            return $result;
        }
    }

    /**
     * パスワード用ランダム文字列生成　記号含まないのでちょっと脆弱
     * @param type $length
     * @return type
     */
    public static function makeRandStr($length) {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return $r_str;
    }

    /**
     * パスワード用ランダム文字列生成　数字のみなので脆弱
     * @param type $length
     * @return type
     */
    public static function makeRandNum($length) {
        $str = array_merge(range('0', '9'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return $r_str;
    }

    public static function nameToHash($name) {
        return strtoupper(substr(md5($name), -8, 8));
    }
}
