LWC から fetch 関数を使用して外部 API をブラウザから直接コールする実装例です。

Salseforce は、デフォルトで CSP が有効化されています。基本的にはコンポーネントなどから外部 API を呼び出す場合、CSP の信頼済みサイトを追加する必要があります。(CSP はセッションの設定で無効化もできます)

参照:
[厳格な CSP 制限](https://developer.salesforce.com/docs/atlas.ja-jp.lightning.meta/lightning/security_csp_stricter.htm)

また、API 側は CORS 設定、つまりレスポンスの「Access-Control-Allow-Origin」ヘッダに Salseforce の組織をオリジンとしてセットして返します。

以下、このプロジェクトを試す手順です。

## sfdx プロジェクトの初期化

sfdx プロジェクトのディレクトリ forcecom に移動して npm パッケージをインストール

```
$ cd forcecom
$ npm install
```

Scratch 組織の作成とソースの push

```
$ sfdx force:org:create -f ./config/project-scratch-def.json -s -d 30
```

## API サーバーの用意

### Access-Control-Allow-Origin ヘッダのオリジンを変更する

ScratchOrg を開きます。

```
$ sfdx force:org:open
```

LEX の URL `https://<私のドメイン>.lightning.force.com`を`api/index.php`の以下にセットします。

```
header('Access-Control-Allow-Origin: <Scratch組織のLEXを開いたURL>');
```

### Heroku にデプロイ

ここでは、Heroku で API サーバーを動かす例を紹介します。

Heroku コマンドは以下のページを参考にインストールしてください。
https://devcenter.heroku.com/articles/getting-started-with-php#set-up

Heroku に新しいアプリケーションを作成します。

```
$ heroku create
```

表示された URL は、LWC から fetch する API のエンドポイントと Salseforce 組織の CSP に設定するので控えておきます。

例: https://peaceful-mesa-70180.herokuapp.com/

以下のコマンドでプロジェクトをデプロイします。

```
$ git subtree push --prefix api heroku master
```

## LWC の API エンドポイントの変更

`forcecom/force-app/main/default/lwc/fetchExample/fetchExample.js`を開き以下の`endPoint`にセットする URL を作成した Heroku アプリケーションのものに変更します。

```
let endPoint = "<作成したHerokuのURL>";
```

修正が終わったら push します。

```
$ sfdx force:source:push
```

## コンテンツセキュリティポリシー(CSP)の追加

Scratch 組織を開き、Heroku アプリケーションの URL を CSP の信頼済みサイトに追加します。

1. [設定] から、[クイック検索] ボックスに「CSP」と入力し、[CSP 信頼済みサイト] を選択
2. [新規信頼済みサイト] をクリック
3. [信頼済みサイト名]にてきた王な名前(例:「MyAPI」)と入力、[信頼済みサイト URL]に Heroku の URL(例:「https://peaceful-mesa-70180.herokuapp.com」)を入力、[connect-src のサイトを許可]にチェックする
4. [保存]をクリック
5. しばらく待つ(CSP 設定は適用されるまで数分くらいかかる)

## 動作確認

適当なアプリケーションのホームで App ビルダーを開き fetchExample を配置して動作を確認します。

[データを取得]ボタンをクリックすると API からデータを取得して表に表示します。

<img width="418" alt="image" src="https://user-images.githubusercontent.com/790480/101867168-dd255080-3bbd-11eb-964b-d57847908fb6.png">

## 参考情報

- [JavaScript からの API のコール](https://developer.salesforce.com/docs/component-library/documentation/ja-jp/lwc/lwc.js_api_calls)
- [サードパーティ API にアクセスするための CSP 信頼済みサイトの作成](https://help.salesforce.com/articleView?id=csp_trusted_sites.htm&type=5)
- [郵便番号の入力を Lightning Experience で実現する LWC 開発！](https://developer.salesforce.com/jpblogs/2020/05/create-postal-code-autocomplete-with-lwc/)
- [using fetch API in LWC](https://blog.salesforcecasts.com/using-fetch-api-in-lwc/)
