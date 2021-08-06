# tplpp 
```shell
composer require ppeco/tplpp
```

Library for templates

## Example
```injectablephp
use ppeco\tplpp\Template;

// From string
$template = new Template("Hello, {\$variable.'!'}");

//..or from file
$template = Template::fromFile("./example.tpl");

echo $template->addValue("variable", "World"); // Output: Hello, World!

//Alternative
echo $template->addValues([
    "variable" => "World"
]);
```