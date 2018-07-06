<?php

namespace TwitterRtUsers\Api;

use Abraham\TwitterOAuth\TwitterOAuth;
use TwitterRtUsers\Setting\SettingUtil;

/*
	Twitter API関連
*/
class ApiUtil{

	// RTしたユーザーを取得
	public function getRtUsers($url, $dir){

		// URLからツイートIDを取得
		$id   = $this->getIdFromUrl($url);
		$path = $dir. $id. ".tsv";

		// TwitterOauthの初期化
		$twitter = new TwitterOAuth(
			SettingUtil::getConsumerKey(), 
			SettingUtil::getConsumerSecret(),
			SettingUtil::AccessToken(),
			SettingUtil::AccessTokenSecret()
		);

		$next_cursor = "0";

		// ヘッダーの出力
		$this->writeHeader($path);

		while(true){

			// RTしたユーザーを取得
			$params = array(
				"id" => $id,
			);
			if($next_cursor !== "0"){
				// 次のカーソルとなるIDをセット
				$params += array("cursor", $next_cursor);
			}
			$res = $twitter->get("statuses/retweeters/ids", $params);

			// エラーのチェック
			if(property_exists($res, "errors")){
				die($res->errors[0]->message);
			}

			// 本文の出力(追記)
			$content = "";
			foreach($res->ids as $id){
				$content .= $id. PHP_EOL;
			}
			$this->writeContent($path, $content);

			// 次のカーソルがなければ終了
			if(($next_cursor = $res->next_cursor_str) === "0"){
				echo PHP_EOL;
				echo "complete!!". PHP_EOL. PHP_EOL;
				echo "output: ". $path. PHP_EOL;
				break;
			}

			// スリープ処理(1分間に4回実行)
			sleep(15);
		}
	}

	// ヘッダーの出力
	private function writeHeader($path){
		$header = "user_id". PHP_EOL;
		@file_put_contents($path, $header);
	}

	// 本文の出力(追記)
	private function writeContent($path, $content){
		@file_put_contents($path, $content, FILE_APPEND | LOCK_EX);
		echo ".";
	}

	// URLからツイートIDを取得
	private function getIdFromUrl($url){

		$slugs = explode("/", trim($url));
		for($i = 0; $i < count($slugs); $i++){
			if(strpos($slugs[$i], "status") !== false){
				return $slugs[$i + 1];
			}
		}

		// IDが見つからなかった場合
		die("ツイートURLが不正です。");
	}

}
