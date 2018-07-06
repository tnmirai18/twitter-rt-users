<?php

// 設定ファイルのパス
define("SETTING_FILE", __DIR__. "/data/setting.json");

// 設定ファイルのパス
define("OUTPUT_DIR", __DIR__. "/output/");

require_once(__DIR__. "/vendor/autoload.php");

use TwitterRtUsers\Api\ApiUtil;
use TwitterRtUsers\Setting\SettingUtil;

// コマンドライン引数の取得
if($argc <= 1){
	die("URLが指定されていません。");
}
$url = $argv[1];

// 設定の読み込み
SettingUtil::load(SETTING_FILE);

// Apiの初期化
$api = new ApiUtil();
$api->getRtUsers($url, OUTPUT_DIR);

