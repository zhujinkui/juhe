<?php

namespace think;

/**
 * | Notes：Juhe
 * +----------------------------------------------------------------------
 * | PHP Version 7.2+
 * +----------------------------------------------------------------------
 * | Copyright (c) 2011-2020 https://www.xxq.com.cn, All rights reserved.
 * +----------------------------------------------------------------------
 * | Author: 阶级娃儿 <devloper@zhujinkui.com>
 * +----------------------------------------------------------------------
 * | Date: 2020/4/27 23:11
 * +----------------------------------------------------------------------
 */
class Juhe
{
    /**
     * 聚合通用调度API
     *
     * @param string $url
     * @param array  $param
     * @param int    $is_post
     * @param bool   $is_tpl_param
     * @param array  $tpl_param
     *
     * @return int|mixed
     */
    public function getCurlRequest(string $url = '', array $param = [], int $is_post = 1, bool $is_tpl_param = false, array $tpl_param = [])
    {
        if (empty($url) || empty($param)) {
            //缺少参数
            return -1;
        } else {
            if ($is_tpl_param && empty($tpl_param)) {
                //缺少模板可选参数
                return -2;
            } else {
                $zend_param = array_merge($param, $tpl_param);

                $content = $this->juheCurl($url, $zend_param, $is_post); //请求发送短信

                if ($content) {
                    return $result = json_decode($content, true);
                } else {
                    //请求失败
                    return -3;
                }
            }
        }
    }

    /**
     * 请求接口返回内容
     *
     * @param string $url
     * @param bool   $params
     * @param int    $is_post
     * @param array  $http_info
     *
     * @return bool|string
     */
    protected function juheCurl(string $url, $params = false, int $is_post = 0, array $http_info = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($is_post) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);
        if ($response === false) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode  = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $http_info = array_merge($http_info, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }
}