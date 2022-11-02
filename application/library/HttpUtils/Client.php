<?php

namespace HttpUtils;

class Client
{
    protected string $error = '';

    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param $url
     * @param $data
     * @return bool|string
     * @throws Exception
     */
    public function postJson($url, $data)
    {
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Content-Length: ' . strlen($data)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $ret = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code < 200 || $code >= 400) {
            $this->error = curl_error($ch);
            throw new Exception($this->error, Exception::error);
        }
        return $ret;
    }

    /**
     * @param $url
     * @param array $data
     * @return bool|string
     * @throws Exception
     */
    public function get($url, $data = [])
    {
        if ($data) {
            $pre = strpos($url, '&') > -1 ? '&' : '?';
            $url .= $pre . http_build_query($data);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',
        ));
        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($code < 200 || $code >= 400) {
            $this->error = curl_error($ch);
            throw new Exception($this->error, Exception::error);
        }
        return $result;
    }

//
//    public static function upload($url, $path, $data = [])
//    {
//        $curl = curl_init();
//        if (class_exists('\CURLFile')) {
//            curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
//            $data['file'] = new \CURLFile(realpath($path));//>=5.5
//        } else {
//            if (defined('CURLOPT_SAFE_UPLOAD')) {
//                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
//            }
//            $data['file'] = '@' . realpath($path);//<=5.5
//        }
//        $urlInfo = parse_url($url);
//        $data['encryption_time'] = time();
//        $_path = mb_substr($urlInfo['path'], 1);
//        $data['encryption_word'] = get_encryption_data($_path, $data, 'POST');
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_USERAGENT, "TEST");
//        $result = curl_exec($curl);
//        $error = curl_error($curl);
//        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        curl_close($curl);
//        if ($code < 200 || $code >= 400) {
//            Log_Error::write(Log_Error::HTTP_ERROR, [
//                'data' => $data,
//                'result' => $result,
//                'error' => $error
//            ]);
//            return [];
//        }
//        if ($error) {
//            return [];
//        }
//        return json_decode($result, true);
//    }
//
//    /**
//     * @param $url      接口url
//     * @param $path     文件绝对路径
//     * @param $title    文件唯一标识符
//     * @param array $data 文件元数据
//     *  [
//     *   'from_table' => 必须,
//     *   'from_table_id' => 必须,
//     *   'from_table_child' => 可空,
//     *   'from_table_child_id' => 可空,
//     *   'type' => 必须,
//     *   'child_type' => 可空,
//     *   'data' => [] 可空， 用于校验数据是否改变，
//     *      比如用户分享了一张图片， 但是用户改了昵称，
//     *      'data' => ['name' => 昵称]
//     *  ]
//     * @return bool|string
//     */
//    public static function fastDfsUpload($path, $title, $data = [])
//    {
//        $url = get_api() . 'static/create';
////        $url = 'http://local-api-test.mzq.com/static/create';
//        $data['title'] = $title;
//        $result = self::upload($url, $path, $data);
////        $result = json_decode($result, true);
//        return $result;
//    }
//
//    /**
//     * @param $url      接口url
//     * @param $imgUrl   远程图片地址
//     * @param $title    文件唯一标识符
//     * @param array $data 文件元数据
//     * @return bool|string
//     */
//    public static function fastDfsUploadUrl($imgUrl, $title, $data = [])
//    {
//        $url = get_api() . 'static/create';
//        $data['title'] = $title;
//        $data['url'] = $imgUrl;
//        $result = self::postJson($url, $data);
//        if (empty($result)) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        return $result;
//    }
//
//    /**
//     * @param $url
//     * @param array $data
//     *          [
//     *              [
//     *                  'title' => 唯一,
//     *                  'url' => 'https://files.jb51.net/image/henghost300.gif?0228'
//     *              ],
//     *              [
//     *                  'title' => 唯一,
//     *                  'url' => 'https://files.jb51.net/image/xxx.gif?0228'
//     *              ]
//     *          ]
//     * @return bool|string
//     */
//    public static function fastDfsUploadMore($data = [])
//    {
//        $url = get_api() . 'static/create-more';
//        $result = self::postJson($url, $data);
//        if (empty($result)) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        return $result;
//    }
//
//
//    /**
//     * @param $pathList [
//     *          图片路径1， 图片路径2， 图片路径3
//     *      ]
//     * @param $title
//     * @param array $data
//     * @return array|bool|float|int|mixed|stdClass|string|null
//     */
//    public static function fastDfsUploadMoreFile($pathList, $title, $data = [])
//    {
//        $query = 'static/create-more-file';
//        $url = get_api() . '/' . $query;
////        $url = 'http://local-api-test.mzq.com/static/create-more-file';
//        $data['title'] = $title;
//        $curl = curl_init();
//        foreach ($pathList as $key => $path) {
//            if (class_exists('\CURLFile')) {
//                curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
//                $data['file' . $key] = new \CURLFile(realpath($path));//>=5.5
//            } else {
//                if (defined('CURLOPT_SAFE_UPLOAD')) {
//                    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
//                }
//                $data['file' . $key] = '@' . realpath($path);//<=5.5
//            }
//        }
//        $data['encryption_time'] = time();
//        $data['encryption_word'] = get_encryption_data($query, $data, 'POST');
//        curl_setopt($curl, CURLOPT_URL, $url);
//        curl_setopt($curl, CURLOPT_POST, 1);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($curl, CURLOPT_USERAGENT, "TEST");
//        $result = curl_exec($curl);
//        $error = curl_error($curl);
//        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//        curl_close($curl);
//        if ($code < 200 || $code >= 400) {
//            Log_Error::write(Log_Error::HTTP_ERROR, [
//                'data' => $data,
//                'result' => $result,
//                'error' => $error
//            ]);
//            return [];
//        }
//        if ($error) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        return $result;
//    }
//
//
//    /**
//     * 通过连接删除图片
//     * @param $src
//     * @return array|bool|float|int|mixed|stdClass|string|null
//     */
//    public static function fastDfsDeleteBySrc($src)
//    {
//        $url = get_api() . 'static/delete';
////        $url = 'http://local-api-test.mzq.com/static/delete';
//        $data = [
//            'src' => $src
//        ];
//        $result = self::postJson($url, $data);
//        if (empty($result)) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        return $result;
//    }
//
//    /**
//     * @param $pathList
//     *      [ 图片地址1， 图片地址2， 图片连接1， 图片连接2]
//     * @return array|bool|float|int|mixed|stdClass|string|null
//     * 返回成功的图片连接地址
//     *      [
//     *          'file1' => [
//     *                  'src' => 文件服务器连接地址1,
//     *                  'url' => 图片地址
//     *              ]，
//     *          'file2' =>  [
//     *                  'src' => 文件服务器连接地址1,
//     *                  'url' => 图片地址
//     *              ]，
//     *  ]
//     */
//    public static function fastDfsCreateForce($pathList)
//    {
//        $query = 'static/create-force';
//        $url = get_api() . $query;
////        $url = 'http://local-api-test.mzq.com/static/create-force';
//        $list = [];
//        $curl = curl_init();
//        try {
//            foreach ($pathList as $key => $path) {
//                if (mb_substr($path, 0, 4) == 'http') {
////                如果是连接
//                    $data['file' . $key] = $path;
//                    continue;
//                }
//                if (class_exists('\CURLFile')) {
//                    curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
//                    $data['file' . $key] = new \CURLFile(realpath($path));//>=5.5
//                } else {
//                    if (defined('CURLOPT_SAFE_UPLOAD')) {
//                        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, false);
//                    }
//                    $data['file' . $key] = '@' . realpath($path);//<=5.5
//                }
//            }
//
//            $data['encryption_time'] = time();
//            $data['encryption_word'] = get_encryption_data($query, $data, 'POST');
//            curl_setopt($curl, CURLOPT_URL, $url);
//            curl_setopt($curl, CURLOPT_POST, 1);
//            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($curl, CURLOPT_USERAGENT, "TEST");
//            $result = curl_exec($curl);
//            $error = curl_error($curl);
//            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
//            curl_close($curl);
//        } catch (Exception $exception) {
//            curl_close($curl);
//            Log_Error::write(Log_Error::HTTP_ERROR, [
//                'data' => $pathList,
//                'result' => [],
//                'error' => $exception->getMessage()
//            ]);
//            return $list;
//        }
//
//        if ($code < 200 || $code >= 400) {
//            Log_Error::write(Log_Error::HTTP_ERROR, [
//                'data' => $data,
//                'result' => $result,
//                'error' => $error
//            ]);
//            return $list;
//        }
//
//        if ($error) {
//            return $list;
//        }
//        $result = json_decode($result, true);
//        foreach ($result['data'] as $key => $value) {
//            $_key = mb_substr($key, 4);
//            $list[$_key] = $value;
//        }
//        return $list;
//    }
//
//    /**
//     * 保存相关资源数据
//     * @param $data
//     * [
//     *  [
//     *   'title' => 必须,
//     *   'from_table' => 必须,
//     *   'from_table_id' => 必须,
//     *   'from_table_child' => 可空,
//     *   'from_table_child_id' => 可空,
//     *   'src' => 必须,
//     *   'type' => 可空,
//     *   'child_type' => 可空,
//     *   'data' => [] 可空， 用于校验数据是否改变，
//     *      比如用户分享了一张图片， 但是用户改了昵称，
//     *      'data' => ['name' => 昵称]
//     *  ]
//     * ]
//     * @return array|bool|float|int|mixed|stdClass|string|null1
//     */
//    public static function saveList($data)
//    {
//        if (empty($data)) {
//            return [];
//        }
//        $url = get_api() . 'static/save-list';
////        $url = 'http://local-api-test.mzq.com/static/save-list';
//        $result = self::postJson($url, $data);
//        if (empty($result)) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        return $result;
//    }
//
//
//    /**
//     * @param $picNameList
//     * @return array|mixed|string
//     * 暂时加的上传方法
//     */
//    public static function uploadPicBySYG($picNameList, $picFile = '')
//    {
//        $list = [];
//        foreach ($picNameList as $picName) {
//            if (empty($_FILES[$picName]) || empty($_FILES[$picName]['tmp_name'])) {
//                return '';
//            }
//            move_uploaded_file($_FILES[$picName]['tmp_name'], $picFile);
//            $list[$picName] = $picFile;
//        }
//        $result = self::fastDfsCreateForce($list);
//        @unlink($picFile);
//        return $result;
//    }
//
//    /**
//     * @param $where
//     *  [
//     *   'from_table' => 必须,
//     *   'from_table_id' => 必须,
//     *   'from_table_child' => 可空,
//     *   'from_table_child_id' => 可空,
//     *   'src' => 必须,
//     *   'type' => 可空,
//     *   'child_type' => 可空,
//     *  ]
//     * @return array|bool|float|int|mixed|stdClass|string|null
//     */
//    public static function fastDfsInfoWhere($where)
//    {
//        if (empty($where)) {
//            return [];
//        }
//        $url = get_api() . 'static/info-where';
////        $url = 'http://local-api-test.mzq.com/static/info-where';
//        $result = self::postJson($url, $where);
//        if (empty($result)) {
//            return [];
//        }
//        $result = json_decode($result, true);
//        if ($result['status'] == 'ok') {
//            return $result['data'];
//        }
//        return [];
//    }
//
//
//    public static function requestGetJson($url, $data = [])
//    {
//        if ($data) {
//            $data = json_encode($data);
//        }
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            "Content-Type: application/json; charset=UTF-8",
//            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',
//        ));
//        $result = curl_exec($ch);
//        $error = curl_error($ch);
//        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        if ($code < 200 || $code >= 400) {
//            throw new Exception(json_encode([
//                'url' => $url,
//                'data' => $data,
//                'result' => $result,
//                'error' => $error
//            ]));
//            return '';
//        }
//        return $result;
//    }
//
//
//    function requestDelete($url, $data = [])
//    {
//        $ch = curl_init();
//        if ($data) {
//            $data = json_encode($data);
//        }
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
//
//        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
//            "Content-Type: application/json; charset=UTF-8",
//            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/83.0.4103.97 Safari/537.36',
//        ));
//
//        $result = curl_exec($ch);
//        $error = curl_error($ch);
//        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//        curl_close($ch);
//        if ($code < 200 || $code >= 400) {
//            throw new Exception(json_encode([
//                'url' => $url,
//                'data' => $data,
//                'result' => $result,
//                'error' => $error
//            ]));
//            return '';
//        }
//        return $result;
//
//    }
//
//
//    public static function isAjax()
//    {
//        return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
//            ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
//    }


}
