<?php
namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{
    public function translate($text){

        $appid = config('services.baidu_translate.appid');
        $key = config('services.baidu_translate.key');
        $salt = time();

        if( empty($appid) || empty($key) ){
            return $this->pinyin($text);
        }

        $sign = md5( $appid . $text . $salt . $key );
        
        $query = http_build_query([
            "q" => $text,
            "from" => 'zh',
            'to' => 'en',
            "appid" => $appid,
            "salt" => $salt,
            "sign" => $sign,
        ]);
        
        $url = 'http://api.fanyi.baidu.com/api/trans/vip/translate?' . $query;
        
        $http = new Client;
        $response = $http->get($url);
        $result = json_decode( $response->getBody(), true );

        if( !empty( $result['trans_result'][0]['dst']) ){
            return str_slug( $result['trans_result'][0]['dst'] );
        }else{
            return $this->pinyin($text);
        }
        
    }

    public function pinyin($text){
        return str_slug( app(Pinyin::class)->permalink($text) );
    }
}