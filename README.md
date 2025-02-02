# coachtechフリマ

## 環境構築
**Dockerビルド**
1. ```bash
      git clone git@github.com:Ikedarion/test-freemarket.git
   ```
2. ```
      cd test-freemarket
   ```
3.  DockerDesktopアプリを立ち上げる

4. ```bash
      docker-compose up -d --build
   ```

**Laravel環境構築**
1. ```bash
      docker-compose exec php bash
   ```
2. ```bash
      composer install
   ```
3. 「.env.example」ファイルを 「.env」ファイルに命名を変更。
   または、新しく.envファイルを作成
4. .envに以下の環境変数を追加
   ```
      DB_CONNECTION=mysql
      DB_HOST=mysql
      DB_PORT=3306
      DB_DATABASE=laravel_db
      DB_USERNAME=laravel_user
      DB_PASSWORD=laravel_pass

      # mailtrap使用する場合
      MAIL_MAILER=smtp
      MAIL_HOST=smtp.mailtrap.io
      MAIL_PORT=2525
      MAIL_USERNAME=   //ここにMailtrapのUsernameを指定
      MAIL_PASSWORD=   //ここにMailtrapのPasswordを指定
      MAIL_ENCRYPTION=null
      MAIL_FROM_ADDRESS=   //送信者のメールアドレス
      MAIL_FROM_NAME="${APP_NAME}"

      # StripeKey設定
      STRIPE_KEY=   //Stripeのパブリックキーを指定
      STRIPE_SECRET=   //Stripeのシークレットキーを指定
   ```
5. アプリケーションキーの作成（PHPコンテナ内で実行）
   ```bash
      php artisan key:generate
   ```
6. マイグレーションの実行（PHPコンテナ内で実行）
   ```bash
      php artisan migrate
   ```
7. シーディングの実行（PHPコンテナ内で実行）
   ```bash
      php artisan db:seed
   ```
8. ストレージリンクの作成
   ```bash
      php artisan storage:link
   ```

**テストユーザー**
- メールアドレス:test1@example.com パスワード:Password

## テスト環境設定
**テスト用データベースの準備**
1. まず、MySQLコンテナにログインします。`root`ユーザーでアクセスします。
   パスワードは、docker-compose.ymlファイルのMYSQL_ROOT_PASSWORDに設定されたパスワード（通常はroot）を入力します。
   ```bash
      mysql -u root -p
   ```
2. テスト用データベースを作成します。
   ```bash
      CREATE DATABASE demo_test;
      SHOW DATABASES;
   ```
3. config/database.phpに、テスト用のMySQL接続設定を追加しています。
   ```xml
    + 'mysql_test' => [
         'driver' => 'mysql',
         'url' => env('DATABASE_URL'),
         'host' => env('DB_HOST', '127.0.0.1'),
         'port' => env('DB_PORT', '3306'),
         'database' => 'demo_test',   //テスト用データベース名を指定
         'username' => 'root',        //rootユーザー
         'password' => 'root',        //rootパスワード
         'unix_socket' => env('DB_SOCKET', ''),
         'charset' => 'utf8mb4',
         'collation' => 'utf8mb4_unicode_ci',
         'prefix' => '',
         'prefix_indexes' => true,
         'strict' => true,
         'engine' => null,
         'options' => extension_loaded('pdo_mysql') ? array_filter([
            PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
         ]) : [],
      ],
   ```
4. .phpコンテナ内で以下のコマンドを実行し、.envをコピーして.env.testingを作成します。
   ```bash
      cp .env .env.testing
   ```
 - .env.testingにデータベース接続情報を加えます。
   ```
      APP_NAME=Laravel
      APP_ENV=test
    + APP_KEY=   //テスト用のキーは後で生成します
      APP_DEBUG=true
      APP_URL=http://localhost

      DB_CONNECTION=mysql
      DB_HOST=mysql
      DB_PORT=3306
    + DB_DATABASE=demo_test
    + DB_USERNAME=root
    + DB_PASSWORD=root
   ```
5. テスト用アプリケーションキーの生成
   ```bash
      php artisan key:generate --env=testing
   ```
6. .envの変更を反映させるため、キャッシュをクリアします。
   ```bash
      php artisan config:clear
   ```
7. テスト用のデータベースにマイグレーションを実行します。
   ```bash
      php artisan migrate --env=testing
   ```
8. プロジェクトの直下にあるphpunit.xmlで、以下のようにDB_CONNECTIONとDB_DATABASEを変更しています。
   ```xml
      <php>
         <server name="APP_ENV" value="testing"/>
         <server name="BCRYPT_ROUNDS" value="4"/>
         <server name="CACHE_DRIVER" value="array"/>
       + <server name="DB_CONNECTION" value="mysql_test"/>
       + <server name="DB_DATABASE" value="demo_test"/>
         <server name="MAIL_MAILER" value="array"/>
         <server name="QUEUE_CONNECTION" value="sync"/>
         <server name="SESSION_DRIVER" value="array"/>
         <server name="TELESCOPE_ENABLED" value="false"/>
    </php>
   ```

**Featureテストの実行**
- 以下のコマンドでFeatureテストを実行できます：

   ```bash
      vendor/bin/phpunit tests/Feature/
   ```


## 使用技術(実行環境)
- PHP 8.2.27
- MySQL 8.0.26
- Nginx 1.21.1
- Laravel 9.52.18


## ER図
<img width="400" alt="C40522A2-DCC4-4C95-9D2B-18E74CAD8C5F" src="https://github.com/user-attachments/assets/1a6cbeaf-20f0-40da-b806-acadf7bd75d8"/>

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

