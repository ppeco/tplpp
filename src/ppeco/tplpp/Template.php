<?php


namespace ppeco\tplpp;


use JetBrains\PhpStorm\Language;
use Stringable;

class Template implements Stringable {
    public array $values = [];

    public function __construct(
        private string $input
    ) {}

    public function addValues(array $values): self {
        $this->values += $values;
        return $this;
    }

    public function addValue(string $name, mixed $value): self {
        $this->values[$name] = $value;
        return $this;
    }

    public function compile(): string{
        while(preg_match('/{{([^.]+?)}}/', $this->input, $output,
            PREG_OFFSET_CAPTURE)){
            $this->input = substr($this->input, 0, $output[0][1])
                .exec($this, $output[1][0])
                .substr($this->input, $output[0][1]+strlen($output[0][0]), strlen($this->input));
        }

        return $this->input;
    }

    public function __toString(): string {
        return $this->compile();
    }

    public static function fromFile(#[Language("file-reference")] string $path): self {
        return new self(file_get_contents($path));
    }
}

function exec(Template $template, string $code): mixed {
    if(str_contains($code, ";")) {
        $org_code = $code;
        $code = "\$code = function(): mixed {";
        foreach($template->values as $name => $value)
            $code .= "\$$name = '".str_replace("'", "\\'", $value)."';";

        $code .= "$org_code}; return \$code();";
    }else {
        foreach($template->values as $name => $value)
            eval("\$$name = '".str_replace("'", "\\'", $value)."';");

        $code = "return $code;";
    }

    unset($template);
    return eval("unset(\$code); $code");
}