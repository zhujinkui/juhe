<?php
// 类库名称：聚合综合类库
// +----------------------------------------------------------------------
// | PHP version 5.6+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.myzy.com.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 阶级娃儿 <262877348@qq.com> 群：304104682
// +----------------------------------------------------------------------

namespace think;

class Juhe
{
    /**
     * [zendJuheCurl 聚合通用调度API]
     * @param  string  $sendUrl    [API请求地址]
     * @param  array   $sceneParam [默认参数]
     * @param  boolean $isPost     [是否使用POST请求，默认POST]
     * @param  boolean $isTplParam [是否开启消息模板]
     * @param  array   $tplParam   [消息模板 多变量格式：'#code#=1234&#company#=聚合数据']
     */
    public function zendJuheCurl($sendUrl = '', array $sceneParam = [], $isPost = 1, $isTplParam = false, array $tplParam = [])
    {
        if (empty($sendUrl) || empty($sceneParam) ) {
            //缺少参数
            return -1;
        } else {
            if ($isTplParam && empty($tplParam)) {
                //缺少模板可选参数
                return -2;
            } else {
                $zendParam = array_merge($sceneParam, $tplParam);

                $content = $this->juhecurl($sendUrl, $zendParam, $isPost); //请求发送短信

                if($content){
                    return $result = json_decode($content, true);
                }else{
                    //请求失败
                    return -3;
                }
            }
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    protected function juhecurl($url, $params = false, $ispost = 0, array $httpInfo = [])
    {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );
        curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );

        if( $ispost ) {
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
            curl_setopt( $ch , CURLOPT_URL , $url );
        } else {
            if($params){
                curl_setopt( $ch , CURLOPT_URL , $url.'?'. http_build_query($params) );
            }else{
                curl_setopt( $ch , CURLOPT_URL , $url);
            }
        }

        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }
}