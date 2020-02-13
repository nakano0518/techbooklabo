<?php

//composer.jsonでclassesフォルダをnamespace Classesでマッピングしたので
//namespace Classesを記述する。
namespace Classes;


class sampleClass{
	public function Hello() {
		echo "Hello World";
	}
}

//使い方
//アプリケーション直下のファイルで利用するとして
// require_once __DIR__.'/vendor/autoload.php';　読み込み(パスは適宜読み変え)
//use Classes\sampleCass; オートロ―ドでマッピングしたnamespace以下のFQDNをuseする→これでsampleClassと記述するだけで使える
//$sc = new sampleClass; インスタンス生成
//$sc->Hello();　メソッドの利用
