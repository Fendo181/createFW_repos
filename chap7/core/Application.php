<?php

/**
 *
 * Class Application
 * アプリケーションの中心となるクラスを
 *
 */
abstract class Application
{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $db_manager;

    /**
     * Application constructor.
     * インスタンス生成時にデバッグモードの確認と、初期化を行う
     *
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /**
     *
     * デバッグモードを設定する
     * $debug=trueの時にエラー表示処理を変更する
     *
     * @param $debug
     */
    protected function setDebugMode($debug)
    {
        if($debug){
            $this->debug = true;
            ini_set('display_errors',1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors',0);
        }
    }

    /**
     * 初期化を行う
     */
    public function initialize()
    {
        $this->request = new Request();
        $this->request = new Response();
        $this->request = new Session();
        $this->db_manager = new DbManager();
        $this->router = new Router($this->registerRouter());
    }

    protected function configure()
    {

    }

    abstract public function getRootDir();

    abstract protected function registerRoutes();


    /**
     *
     * デバッグモードの状態を返す
     *
     * @return bool
     */
    public function getDebugMode()
    {
        return $this->debug;
    }

    /**
     *
     * Requestのインスタンスを取得する
     *
     * @return request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     *
     * Responseのインスタンスを取得する
     *
     * @return response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     *
     * Sessionクラスのインスタンスを取得する
     *
     * @return session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     *
     * DbManagerクラスのインスタンスを取得する
     *
     * @return db_manager
     */
    public function getDbManager()
    {
        return $this->db_manager;
    }

    /**
     *
     * Controllerが置かれているディレクトリを取得する
     *
     * @return Controllerファイルが置かれているディレクトリ
     */
    public function getControllerDir()
    {
        return$this->getRootDir(). '/controllers';
    }


    /**
     *
     * Viewが置かれているディレクトリを取得する
     *
     * @return Viewファイルが置かれているディレクトリ
     */
    public function getViewDir()
    {
        return$this->getRootDir(). '/views';
    }

    /**
     *
     * Modelが置かれているディレクトリを取得する
     *
     * @return Modelファイルが置かれているディレクトリ
     */
    public function getModelDir()
    {
        return$this->getRootDir(). '/models';
    }

    /**
     *
     * webファイルが置かれているディレクトリを取得する
     *
     * @return webファイルが置かれているディレクトリ
     */
    public function getWebDir()
    {
        return$this->getRootDir(). '/web';
    }


    /**
     *
     * Routerからコントローラーを特定して、runAction()を呼び出して実行して、レスポンスの送信を行う
     *
     */
    public function run()
    {
        $params = $this->router->resolve($this->request->getPathInfo());
        if($params === false){
            // todo-A
        }

        $controller = $params['controller'];
        $action = $params['action'];

        $this->runAction($controller,$action,$params);
        $this->response->send();
    }

    /**
     *
     * Controllerのアクションを実行するメソッド
     *
     * @param $controller_name
     * @param $action
     * @param array $params
     */
    public function runAction($controller_name,$action,$params = [])
    {
        // ルーティングはコントローラーの小文字を指定するようにしたので、ucfirstで大文字に変換する
        $controller_class = ucfirst($controller_name).'Controller';

        $controller = $this->findController($controller_class);
        if($controller === false){
            // todo-B
        }

        $content = $controller->run($action,$params);

        // コンテンツをセットする
        $this->response->setContent($content);
    }

    /**
     *
     * コントローラークラスが読み込まれてない場合にクラスファイルを読み込みます。
     *
     * @param $controller_class
     * @return Controllerインスタンス | false
     */
    public function findController($controller_class)
    {
        if(!class_exists($controller_class)){
            $controller_file = $this->getControllerDir(). '/' . $controller_class.'php';
        }

        if(!is_readable($controller_file)){
            return false;
        }else{

            require_once $controller_file;

            if(!class_exists($controller_file)){
                return false;
            }
        }

        return new $controller_file;
    }
}