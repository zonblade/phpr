<?php

namespace phpr\apps {
    class name
    {
        public $GLOBALS;
        private $apps;

        function __construct($apps)
        {
            $this->apps = $apps;
            if (array_key_exists($apps, $GLOBALS['installed_apps'])) {
                $this->dir = $GLOBALS['installed_apps'];
            }
        }

        function app($path)
        {
            return $this->dir[$this->apps] . "$path/view.php" ?? false;
        }

        function path($path)
        {
            return $this->dir[$this->apps] . $path ?? false;
        }
    }
}

namespace phpr\apps\run {
    function App($app_to_run, $path)
    {
        if (array_key_exists($app_to_run, $GLOBALS['installed_apps'])) {
            return $GLOBALS['installed_apps'][$app_to_run] . "$path/view.php" ?? false;
        } else {
            return false;
        }
    }

    function AppPath($app_to_run, $path)
    {
        if (array_key_exists($app_to_run, $GLOBALS['installed_apps'])) {
            return $GLOBALS['installed_apps'][$app_to_run] . $path ?? false;
        } else {
            return false;
        }
    }

    function URI($uri)
    {
        return urlfolder() . $uri;
    }
    function URI_GLOB($uri)
    {
        return globalurl() . $uri;
    }
}
