<?php

namespace Core;

class Application
{
    private static ?Application $application = null;

    private $defaultController = "ProductsController";
    private $defaultAction = "index";
    private $defaultParams = [];

    private $controller = "ProductsController";
    private $action = "index";
    private $params = [];

    /**
     * Private constructor since this is a singleton.
     */
    private function __construct()
    {
        try {
            $this->readEndPoint();

            if (!file_exists(CONTROLLER . $this->controller . ".php")) {
                throw new \Exception("Controller does not exist.");
            }
            if (!method_exists('\Controllers\\' . $this->controller, $this->action)) {
                throw new \Exception("Action does not exist.");
            }

            $controller = "\Controllers\\" . $this->controller;
            $action = $this->action;
            $params = $this->params;
            $controller::$action(...$params);
        } catch(\Exception $e) {
            echo json_encode(['message' => 'failed', 'data' => $e->getMessage()]);
            http_response_code(404);
            return false;
        }
    }

    private function readEndPoint()
    {
        // CORS
        if ($_SERVER["REQUEST_METHOD"] == 'OPTIONS') {
            http_response_code(200);
            exit;
        }

        $url = $_SERVER["REQUEST_URI"];

        $indexOfAPI = strpos($url, "api");

        if ($indexOfAPI === false) {
            throw new \Exception("Not Found");
        }

        $apiLengthPlusOne = strlen("api") + 1;

        $endPoint = trim(substr($url, $indexOfAPI + $apiLengthPlusOne), "/");
        $endPointArr = explode("/", $endPoint);
        if (count($endPointArr) > 0) {
            $this->controller = strlen($endPointArr[0]) > 0 ?
                \Inc\Utils::onlyFirstCharacterIsCapital($endPointArr[0]) . "Controller" :
                $this->defaultController;
        }
        if (count($endPointArr) > 1) {
            $this->action = strlen($endPointArr[1]) > 0 ?
                $endPointArr[1] :
                $this->defaultAction;
        }
        if (count($endPointArr) > 2) {
            $this->params = array_values(array_slice($endPointArr, 2));
        }
    }

    public static function start()
    {
        if (self::$application === null) {
            self::$application = new Application();
        }
    }

    public static function getInstance()
    {
        if (self::$application === null) {
            self::start();
        }
        return self::$application;
    }
}
