<?php
/**
 * User: Rutger
 * Date: 7-11-13
 * Time: 10:19
 */

namespace core;


class ErrorHandler {
    private $errors = array();


    public function registerHandlers() {
        register_shutdown_function(array($this, 'handleShutdown'));
        set_error_handler(array($this, 'handleError'));
    }


    public function getErrorString() {
        return join("\n", $this->errors);
    }


    public function handleError($errNo, $errStr, $errFile, $errLine) {
        if (!(error_reporting() & $errNo)) {
            // This error code is not included in error_reporting
            return;
        }

        switch ($errNo) {
            case E_USER_ERROR:
                $errMessage  = "<b>Fatal error</b> [$errNo] '$errStr' \n";
                $errMessage .= "on line $errLine in file $errFile";

                $view = new View('error.fatal.php');
                $view->assign('errorMessage', $errMessage);
                $view->renderView();
                exit();
                break;

            case E_USER_WARNING:
                $this->errors[] = "<strong>Warning:</strong> [$errNo] $errStr on line $errLine in file $errFile";
                break;

            case E_USER_NOTICE:
                $this->errors[] = "<strong>Notice:</strong> [$errNo] $errStr on line $errLine in file $errFile";
                break;

            default:
                $this->errors[] = "<strong>Unknown error:</strong> [$errNo] $errStr on line $errLine in file $errFile";
                break;
        }

        // Don't execute PHP internal error handler
        return true;
    }


    public function handleShutdown() {
        $error = error_get_last();

        if (!empty($error) && $error['type'] == 1) {
            ob_clean();

            $view = new View('error.fatal.php');
            $view->assign('errorMessage', $error['message']);
            $view->renderView();
        }
    }
}