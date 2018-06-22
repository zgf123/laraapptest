#laravel-niconico

yarn config set registry https://registry.npm.taobao.org
yarn install
npm run watch-poll


composer require "mews/captcha:~2.0"
php artisan vendor:publish --provider="Mews\Captcha\CaptchaServiceProvider"


composer requrie "overtrue/laravel-lang:~3.0"
\Carbon\Carbon::setLocale('zh');


composer require intervention/image
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravel5"


composer requrie "summerblue/generator:~0.5"
composer artisan make:scaffold Project --schema=""


composer require "barryvdh/laravel-debugbar:~3.1" --dev
php artisan vendor:publish --provider="Barryvdh\Debugbar\ServiceProvider"
'enabled' => env('APP_DEBUG', false),


composer require "hieu-le/active:~3.5"


composer require "mews/purifier:~2.0"
php artisan vendor:publish --provider="Mews\Purifier\PurifierServiceProvider"


composer require "guzzlehttp/guzzle:~6.3"
composer require "overtrue/pinyin:~3.0"


composer require "predis/predis:~1.0"
composer require "laravel/horizon:~1.0"
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"