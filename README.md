# Laravel 8 Mail 
###### tags `Laravel` `php` `redis` `queue` `mail` `mailtrap`
## 前言

利用 Redis 與 Queue , [Mailtrap](https://mailtrap.io/) 完成 Email寄發

## 安裝
新建一個專案
```
$ laravel new laravel-mail
```
## 設定環境
[Mailtrap](https://mailtrap.io/) 辦帳號登入後看到這個畫面![](https://i.imgur.com/N80qIC1.png)


點進去
![](https://i.imgur.com/2ZV3Olc.png)

.env 根據上圖更改
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=587
MAIL_USERNAME=33c66cbd6da20f
MAIL_PASSWORD=25d484781e03e9
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

## 設定路由

```
$ php artisan make:controller MailController   
```
web.php
```
Route::get('/send', [MailController::class, 'send']);
```
MailController.php
```
public function send()
{
    Mail::to('abc@abc.com')->send(new FirstMail());
    
    return 'check you email!!';
}
```
## 生成 Mailables

```
$ php artisan make:mail FirstMail   
```

FirstMail.php
```
public function build()
{
    return $this->from('example@example.com')
    ->view('mail.index');
}
```

## 新增郵件
在 resources/views 裡新增Mail資料夾

![](https://i.imgur.com/5V0DX4b.png)

在Mail裡新增 index.blade.php


打上你想說的話 

![](https://i.imgur.com/m9xWhGD.png)

## 寄出第一封信
![](https://i.imgur.com/vm6KKoS.png)

到 Mailtrap 檢查
![](https://i.imgur.com/sOKj5Lw.png)

寄信花太多時間了，下一步把它丟給Queue列隊慢慢執行。
![](https://i.imgur.com/OJcrYsG.png)



## 設定環境 
要使用 redis 隊列，需要在 config/database.php 配置文件中配置一个 redis 數據庫連接。

```
'redis' => [
    'driver' => 'redis',
    'connection' => 'default',
    'queue' => '{default}',
    'retry_after' => 90,
],

```
修改.env
```
QUEUE_CONNECTION=redis
```

## 修改MailController
onQueue('自行設定隊列名稱')
```
public function send()
{
    $message = (new FirstMail())->onQueue('emails');
    Mail::to('abc@abc.com')->queue($message);

    return 'check you email!!';
}
```

## 查看Redis
搓一下路由 查看 Redis 有沒有任務
```
#進入redis
redis-cli

#查詢任務
keys *
```
有了
![](https://i.imgur.com/bxG84ZM.png)

## 開啟Queue
```
#emails 為我剛取的名字
$ php artisan queue:work --queue=emails   
```
![](https://i.imgur.com/uEbliir.png)


任務成功

## 速度比較

不使用Queue
![](https://i.imgur.com/IGJb4tc.png)

使用Queue
![](https://i.imgur.com/aOA9DzX.png)


## 備註

程式碼更改時記得重啟Queue
```
php artisan queue:restart
```