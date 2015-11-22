<?php

/** Define the root-relative path. */
define('DIR', '/slimvc/public/');

/** Set the default language. */
define('DEFAULT_LOCALE', 'vi');

/** Set timezone. */
date_default_timezone_set('Asia/Ho_Chi_Minh');

/** Set prefix for sessions. */
define('SESSION_PREFIX', 'smvc_');

/** Start sessions. */
\Helpers\Session::init();
