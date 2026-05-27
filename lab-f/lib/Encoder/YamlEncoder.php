<?php
namespace App\Encoder;

class YamlEncoder implements EncoderInterface {
    public function supports(string $f): bool {
        return $f === 'YAML';
    }

    public function decode(string $d): array { //yaml
        $res = yaml_parse($d);
        return is_array($res) ? $res : [];
    }

    public function encode(array $d): string {
        return yaml_emit($d);
    }

    public function checksupport(string $data): bool
    {
        return $data === 'YAML';
    }
}