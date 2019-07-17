使用laravel5.6 + dingo/api + jwt 验证的练习，[教程地址](https://segmentfault.com/a/1190000010449592#articleHeader9)

提示：由于此教程中，我把创建的model放到了 app/Models文件夹里，所以需要在 config/auth.php中修改一下配置，如下：


`'providers' => [
         'users' => [
             'driver' => 'eloquent',
             'model' => App\Models\User::class,
         ],`
         
        