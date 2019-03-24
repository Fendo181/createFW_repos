<?php

/**
 * Class Request
 */
class Request
{

    /**
     *
     * POSTメソッドでリクエストが来たのか判定する
     *
     * @return bool
     */
    public function isPost()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            return true;
        }

        return false;
    }

    /**
     *
     * $_GETでリクエストがあった際のパラメータを取得して返す
     * なければnullを返す
     *
     * @param $name
     * @param null $default
     * @return $_GET[$name] | $default
     */
    public function getGet($name, $default= null)
    {
        if(isset($_GET[$name])){
            return $_GET[$name];
        }

        return $default;
    }


    /**
     *
     * サーバのホスト名を取得する
     * リクエストヘッダに無い場合はApache側に格納されている $_SERVER['SERVER_NAME']を返します
     *
     * @return $_SERVER['HTTP_HOST'] | $_SERVER['SERVER_NAME']
     */
    public function getHost(){
        if(!empty($_SERVER['HTTP_HOST'])){
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    /**
     *
     * HTTPSでアクセスされたか判定を行う
     *
     * @return bool
     */
    public function isSsl()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTP'] === 'on'){
            return true;
        }

        return false;
    }

    /**
     *
     * REQUEST_URIを返す
     *
     * @return $_SERVER['REQUEST_URI']
     */
    public function  getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     *
     * スクリプトが実行されるファイルパスを返します。
     *
     * @return  $scripts_name
     */
    public function getBaseURL()
    {
        $script_path = $_SERVER['SCRIPT_NAME'];
        $request_uri = $this->getRequestUri();

        if(0 === strpos($request_uri, $script_path)){
            return $script_path;
        }else if (strpos($request_uri, dirname($script_path))){
            return rtrim( dirname($script_path_name),'/');
        }

    }

    /**
     *
     * baseURL以下のPATHの情報を取得する
     *
     * @return $path_info
     */
    public function getPathInfo()
    {
        $base_uri = $this->getBaseURL();
        $request_uri = $this->getRequestUri();

        // クエリーパラメータの場合
        $pos = strpos($request_uri,'?');

        if($pos !== false){
            $request_uri = substr($request_uri,0, $pos);
        }

        $path_info = (string)substr($request_uri,strlen($base_uri));
        return $path_info;
    }

}

