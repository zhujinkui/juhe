<?php
// 短信配置文件
// +----------------------------------------------------------------------
// | PHP version 5.3+
// +----------------------------------------------------------------------
// | Copyright (c) 2012-2014 http://www.myzy.com.cn, All rights reserved.
// +----------------------------------------------------------------------
// | Author: 阶级娃儿 <262877348@qq.com> 群：304104682
// +----------------------------------------------------------------------
header("Content-Type: Text/Html;Charset=UTF-8");
require "./vendor/autoload.php";


$obj = new \think\Juhe();

$sendUrl = 'http://v.juhe.cn/sms/send';

$sceneParam = [
    'key'    => '4eee86a11cfd4dcbb488d6aee59c4183',
    'mobile' => '15501052244',
    'tpl_id' => '19710'
];

$tplParam = [
    'tpl_value' => '#code#=1234' //您设置的模板变量，根据实际情况修改
];

$sendStatus = $obj->getCurlRequest($sendUrl = 'http://v.juhe.cn/sms/send', $sceneParam, $isTplParam = true, $tplParam);


if ($sendStatus > 0) {
    if ($sendStatus['error_code'] == 0) {
        //状态为0，说明短信发送成功
        $error_code = '发送成功';
    } else {
        //状态非0，说明失败
        $error_code = '发送失败';
    }
} else {
    //返回内容异常，以下可根据业务逻辑自行修改
    switch ($sendStatus) {
        case -1:
            $error_code = '缺少参数';
            break;
        case -2:
            $error_code = '缺少模板可选参数';
            break;
        case -3:
            $error_code = '请求异常';
            break;
        default:
            $error_code = '未知错误';
            break;
    }
}

echo $error_code;
echo '<pre>';
print_r($sendStatus);



