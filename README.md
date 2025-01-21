# coachtechフリマ

## 環境構築
**Dockerビルド**
1. ```
   git clone git@github.com:estra-inc/confirmation-test-contact-form.git
   ```
   
3. DockerDesktopアプリを立ち上げる
   
5. ```
   docker-compose up -d --build
   ```

**Laravel環境構築**
1. ```
   docker-compose exec php bash
   ```
   
3. ```
   composer install
   ```
   
5. 「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
   
7. .envに以下の環境変数を追加
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
    MAIL_USERNAME= # ここにMailtrapのUsernameを指定
    MAIL_PASSWORD= # ここにMailtrapのPasswordを指定
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS= # 送信者のメールアドレス
    MAIL_FROM_NAME="${APP_NAME}"

    # StripeKey設定
    STRIPE_KEY=　// Stripeのパブリックキーを指定
    STRIPE_SECRET= // Stripeのシークレットキーを指定
  ```
5. アプリケーションキーの作成（PHPコンテナ内で実行）
  ```
    php artisan key:generate
  ```

6. マイグレーションの実行（PHPコンテナ内で実行）
  ```
    php artisan migrate
  ```

7. シーディングの実行（PHPコンテナ内で実行）
  ```
    //カテゴリー作成
    php artisan db:seed --class=CategorySeeder
    //ダミーデータ作成
    php artisan db:seed --class=ProductSeeder
    php artisan db:seed --class=CategoryProductSeeder
    php artisan db:seed --class=ShippingAddressSeeder
  ```

## 使用技術(実行環境)
- PHP8.3.0
- Laravel8.83.27
- MySQL8.0.26

## ER図

## URL
- 開発環境：http://localhost/
- phpMyAdmin:：http://localhost:8080/

