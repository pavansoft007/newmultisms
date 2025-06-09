<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

INFO - 2025-06-09 05:24:53 --> Config Class Initialized
INFO - 2025-06-09 05:24:53 --> Hooks Class Initialized
DEBUG - 2025-06-09 05:24:53 --> UTF-8 Support Enabled
INFO - 2025-06-09 05:24:53 --> Utf8 Class Initialized
INFO - 2025-06-09 05:24:53 --> URI Class Initialized
DEBUG - 2025-06-09 05:24:53 --> No URI present. Default controller set.
INFO - 2025-06-09 05:24:53 --> Router Class Initialized
INFO - 2025-06-09 05:24:53 --> Output Class Initialized
INFO - 2025-06-09 05:24:53 --> Security Class Initialized
DEBUG - 2025-06-09 05:24:53 --> Global POST, GET and COOKIE data sanitized
INFO - 2025-06-09 05:24:53 --> CSRF cookie sent
INFO - 2025-06-09 05:24:53 --> Input Class Initialized
INFO - 2025-06-09 05:24:53 --> Language Class Initialized
INFO - 2025-06-09 05:24:53 --> Loader Class Initialized
INFO - 2025-06-09 05:24:53 --> Helper loaded: url_helper
INFO - 2025-06-09 05:24:53 --> Helper loaded: file_helper
INFO - 2025-06-09 05:24:53 --> Helper loaded: form_helper
INFO - 2025-06-09 05:24:53 --> Helper loaded: security_helper
INFO - 2025-06-09 05:24:53 --> Helper loaded: directory_helper
INFO - 2025-06-09 05:24:53 --> Helper loaded: general_helper
INFO - 2025-06-09 05:24:53 --> Database Driver Class Initialized
ERROR - 2025-06-09 05:24:53 --> Query error: Table 'multismsdb.rm_sessions' doesn't exist in engine - Invalid query: SELECT 1
FROM `rm_sessions`
WHERE `id` = '4r48uqlsf38buanidpumsebbv8i5j2g0'
ERROR - 2025-06-09 05:24:53 --> Query error: Table 'multismsdb.rm_sessions' doesn't exist in engine - Invalid query: SELECT `data`
FROM `rm_sessions`
WHERE `id` = 'hokbducjoojt8gjn3c0m3j6bnalm2oll'
INFO - 2025-06-09 05:24:53 --> Session: Class initialized using 'database' driver.
INFO - 2025-06-09 05:24:53 --> Language file loaded: language/english/pagination_lang.php
INFO - 2025-06-09 05:24:53 --> Pagination Class Initialized
INFO - 2025-06-09 05:24:53 --> XML-RPC Class Initialized
INFO - 2025-06-09 05:24:53 --> Form Validation Class Initialized
INFO - 2025-06-09 05:24:53 --> Upload Class Initialized
INFO - 2025-06-09 05:24:53 --> MY_Model class loaded
INFO - 2025-06-09 05:24:53 --> Model "Application_model" initialized
INFO - 2025-06-09 05:24:53 --> Controller Class Initialized
ERROR - 2025-06-09 05:24:53 --> Query error: Table 'multismsdb.global_settings' doesn't exist in engine - Invalid query: SELECT *
FROM `global_settings`
WHERE `id` = 1
ERROR - 2025-06-09 05:24:53 --> Severity: error --> Exception: Call to a member function row_array() on bool C:\xampp\htdocs\newmultisms\application\core\MY_Controller.php 19
ERROR - 2025-06-09 05:24:53 --> Query error: Table 'multismsdb.rm_sessions' doesn't exist in engine - Invalid query: INSERT INTO `rm_sessions` (`id`, `ip_address`, `timestamp`, `data`) VALUES ('hokbducjoojt8gjn3c0m3j6bnalm2oll', '::1', 1749439493, '__ci_last_regenerate|i:1749439493;')
ERROR - 2025-06-09 05:24:53 --> Severity: Warning --> session_write_close(): Failed to write session data using user defined save handler. (session.save_path: \xampp\tmp, handler: CI_SessionWrapper::write) Unknown 0
