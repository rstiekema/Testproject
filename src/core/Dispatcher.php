<?php
/**
 * User: Rutger
 * Date: 7-11-13
 * Time: 10:21
 */

namespace core;


class Dispatcher {
    /**
     * @param $DB
     * @param array $routing
     */
    public function __construct($DB, $routing = array())
    {
        $this->DB      = $DB;
        $this->routing = $routing;
    }


    /**
     * Dispatch the request using the routing configuration. The first part in the
     * request url is the controller, the 2nd part is the action (controller method),
     * the parts after that are the parameters.
     *
     * @param $url
     * @return bool
     */
    public function dispatch($url = null)
    {
        if ($url === null) {
            $urlData = $this->parseURL($_SERVER['REQUEST_URI']);
        } else {
            $urlData = $this->parseURL($url);
        }

        $controller = ucfirst($urlData['Controller']).'Controller';
        $action     = 'action'.ucfirst($urlData['action']);
        $params     = $urlData['params'];
        $url        = $urlData['url'];

        if (!empty($urlData['Controller']) && class_exists($controller, true)) {
            $this->runController($controller, $action, $params, $url);
            return true;
        }

        foreach ($this->routing as $routeRegex => $routeData) {
            if (preg_match($routeRegex, $urlData['URL'])) {
                if (isset($routeData['Controller'])) $controller = ucfirst($routeData['Controller']).'Controller';
                if (isset($routeData['Action']))     $action     = 'action'.ucfirst($routeData['Action']);

                $this->runController($controller, $action, $params, $url);
                return true;
            }
        }

        return false;
    }


    /**
     * Run the controller and action. If the action method does not exist, the default
     * actionDefault method will be called.
     *
     * @param $controllerName
     * @param $action
     * @param $params
     * @param $url
     * @return bool
     */
    private function runController($controllerName, $action, $params, $url)
    {
        $controller         = new $controllerName($this->DB);
        $controller->url    = $url;
        $controller->action = $action;
        $controller->params = $params;

        if (method_exists($controller, $action)) {
            $controller->$action();
            return true;
        }

        $controller->actionDefault();
        return true;
    }


    /**
     * Parse a URL into a controller part, action part and parameters part
     *
     * @param $url
     * @return array
     */
    public function parseURL($url)
    {
        $url        = trim($url, '/');
        $urlParts   = explode('/', $url);
        $controller = array_shift($urlParts);
        $action     = array_shift($urlParts);

        return array(
            'url'        => $url,
            'controller' => $controller,
            'action'     => $action,
            'params'     => $urlParts
        );
    }
} 