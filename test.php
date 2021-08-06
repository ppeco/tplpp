<?php

use ppeco\tplpp\Template;

require_once __DIR__."/./vendor/autoload.php";

echo new Template("
test {{return 'test';}}
");