<?php
//导入配置文件
require 'config.php';

require 'sources/lib/function.core.php';
require 'sources/lib/function.article.php';
require 'sources/lib/function.template.php';
require 'init.php';

Dispatcher::getInstance()->run();