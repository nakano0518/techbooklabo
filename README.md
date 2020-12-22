## 本サイトの概要

###### URL： https://www.techbooklabo.com
※ログイン画面の「テストログイン」で１クリックでログインできます。


本サイトは、「エンジニアのための技術書レビューサイト」です。  
私は技術書を購入する際、Amazonなどのレビューを参考にしています。
しかし、そこにレビュアーのエンジニアとしての経歴は掲載されておらず、完全に信用できるものではないと感じていました。
そこで、レビュアーの経歴を可視化したうえでレビューを掲載・蓄積することで、学習者が参考にして購入する際に役立つプラットフォームとして開発しました。


## 機能一覧および使用技術

- ### フロントエンド
  - HTML/CSSを用いて作成し、レスポンシブデザイン化
  - JavaScript(jQuery)を用いたAjaxによるUX/UIの向上
- ### サーバーサイド
  ※下記機能をフレームワークを使用せず素のPHPを用いて開発
  - 書籍の検索機能(キーワード検索、ソート機能)
  - 書籍の登録機能(楽天BooksAPI を利用)
  - ページネーション機能
  - 書籍のお気に入り追加・削除機能
  - 書籍のレビュー投稿・編集・削除機能
  - ユーザー登録機能
  - プロフィール画像の登録機能 
  - セキュリティ対策(XSS、CSRF、SQLインジェクション、セッションハイジャック対策etc..)  
- ### インフラ
  ※AWS (Amazon Web service) を用いて構築  
  - EC2 インスタンスにおける WEB サーバー構築(Apache)
  - DB として、RDS(MySQL) を使用
  - S3 を用いて、プロフィール画像の保存場所を外部化
  - Route53 で独自ドメインを設定し、ELB をエンドポイントとして常時 SSL 化
  - インフラをコード化し terraform で構築

## 参考画像

- ### AWS インフラ構成

<img width="600" alt="techbooklabo_AWS" src="https://user-images.githubusercontent.com/54522567/77849458-a9a8be80-7206-11ea-8f1b-6b939e2a4827.jpg">

- ### ER 図

<img width="600" alt="techbooklabo_ER" src="https://user-images.githubusercontent.com/54522567/77849465-bcbb8e80-7206-11ea-85aa-ee6c4a163d6e.jpg">

- ### 書籍一覧ページ (トップページ)  
  - HTML/CSSを用いて作成し、レスポンシブデザイン化
  - 書籍の検索機能(キーワード検索、ソート機能)
  - ページネーションの実装  
  　　
<img width="600" alt="TechBookLabo_top_pc" src="https://user-images.githubusercontent.com/54522567/77852053-7de20480-7217-11ea-8c39-7fccfc073e92.JPG">

<img width="300" alt="TechBookLabo_top_mobile" src="https://user-images.githubusercontent.com/54522567/77852063-89cdc680-7217-11ea-93e8-23220b2c594f.JPG">

<img width="600" alt="TechBookLabo_top_kw-sort" src="https://user-images.githubusercontent.com/54522567/77852065-8df9e400-7217-11ea-91da-caa59e7545c1.JPG">

- ### 新規登録ページ
  - ユーザーID入力段階で登録可否をAjaxにより瞬時に判定
  - パスワードの形式、一致などのバリデーション  

<img width="600" alt="techbooklabo_signup" src="https://user-images.githubusercontent.com/54522567/77852075-9baf6980-7217-11ea-8e55-42ae709e2af5.JPG">

- ### ログインページ
  - ユーザーID、パスワード入力必須などのバリデーション
  - テストログインボタンによりワンクリックでログイン可能  

<img width="600" alt="techbooklabo_signIn" src="https://user-images.githubusercontent.com/54522567/77852080-9eaa5a00-7217-11ea-8366-d2fd1b1052e4.JPG">

- ### 書籍登録ページ (ログイン後使用可)
  - 楽天BooksAPIを使用
  - 書籍の検索を技術キーワードに限定
  - Ajaxによりプラスボタンワンクリックで登録可能
  - ページネーションの実装  

<img width="600" alt="techbooklabo_bookInsert" src="https://user-images.githubusercontent.com/54522567/77852086-a538d180-7217-11ea-9271-05cfb08c890a.JPG">


- ### 書籍詳細ページ (ログイン前) 

<img width="600" alt="techbooklabo_login" src="https://user-images.githubusercontent.com/54522567/77852094-b5e94780-7217-11ea-8006-dc8f5bb4db72.JPG">

- ### 書籍詳細ページ (ログイン後) 
  - ログイン後、お気に入りボタンのクリックによりお気に入り数が増減
  - ログイン後、レビュー入力欄の表示  

<img width="600" alt="techbooklabo_login" src="https://user-images.githubusercontent.com/54522567/77852097-b84ba180-7217-11ea-8635-df803a7490da.JPG">

- ### マイページ　　 

<img width="600" alt="techbooklabo_mypage-profile" src="https://user-images.githubusercontent.com/54522567/77852104-beda1900-7217-11ea-8f49-d2fbb3175bc9.JPG">　　

<img width="600" alt="techbooklabo_mypage-good-review" src="https://user-images.githubusercontent.com/54522567/77852106-c13c7300-7217-11ea-8014-145a6dafcc31.JPG">

- ### マイページ編集ページ　　 
  - プロフィール画像のS3への登録機能
  - プロフィール画像の変更の即時反映
  - ×ボタンクリックによる画面遷移なしのお気に入りおよびレビューの削除機能 (Ajax)      
  - レビュー内容の編集機能  

<img width="600" alt="techbooklabo_mypageEdit-profile" src="https://user-images.githubusercontent.com/54522567/77852109-c4376380-7217-11ea-8be0-bf484e9b68de.JPG">　　

<img width="600" alt="techbooklabo_mypageEdit-good-review" src="https://user-images.githubusercontent.com/54522567/77852112-c699bd80-7217-11ea-8775-2a6ca4f06ee6.JPG">





