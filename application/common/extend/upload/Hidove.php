<?php
/**
 * @Author：Hidove 余生
 * @CreateTime 2019/11/20 22:50
 */

namespace app\common\extend\upload;

class Hidove
{
    public $name = 'Hidove图床';
    public $ver = '1.0';
    public function submit($filePath)
    {
        $api = $GLOBALS['config']['upload']['api']['hidove']['api'];
        if (class_exists('CURLFile')) {     // php 5.5
            $post['image'] = new \CURLFile(realpath($filePath));
        } else {
            $post['image'] = '@' . realpath($filePath);
        }
        $post['token'] = $GLOBALS['config']['upload']['api']['hidove']['token'];
        $post['apiType'] = $GLOBALS['config']['upload']['api']['hidove']['apiType'];
        // 创建一个新 cURL 资源
        $curl = curl_init();
        // 设置URL和相应的选项
        // 需要获取的 URL 地址
        curl_setopt($curl, CURLOPT_URL, $api);
        #启用时会将头文件的信息作为数据流输出。
        curl_setopt($curl, CURLOPT_HEADER, false);
        #设置头部信息
        // curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        #在尝试连接时等待的秒数。设置为 0，则无限等待。
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        #允许 cURL 函数执行的最长秒数。
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        #设置请求信息
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        #关闭ssl
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        #TRUE 将 curl_exec获取的信息以字符串返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 抓取 URL 并把它传递给浏览器
        $return = curl_exec($curl);
        curl_close($curl);
        //删除本地图片
        unlink($filePath);
        $return = json_decode($return, true);
        return $return['data']['url']['distribute'];
    }
}