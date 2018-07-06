<?php

namespace TwitterRtUsers\Setting;

/*
	設定関連
*/
class SettingUtil{

	// 設定値オブジェクト
	private static $conf;

	// 設定値の読み込み
	public static function load($path){
		
		// 設定ファイルの存在チェック
		if(! file_exists($path)){
			die("設定ファイルが存在しません。");
		}

		// JSONをPHP配列にパース
		self::$conf = json_decode(@file_get_contents($path), true);
	}

	// コンシューマーキーの取得
	public static function getConsumerKey(){
		return self::$conf["consumer_key"];
	}

	// コンシューマーキー(シークレット)の取得
	public static function getConsumerSecret(){
		return self::$conf["consumer_secret"];
	}

	// アクセストークンの取得
	public static function AccessToken(){
		return self::$conf["access_token"];
	}

	// アクセストークン(シークレット)の取得
	public static function AccessTokenSecret(){
		return self::$conf["access_token_secret"];
	}

}
