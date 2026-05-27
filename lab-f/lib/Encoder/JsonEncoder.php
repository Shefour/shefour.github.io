<?php
namespace App\Encoder;

class JsonEncoder implements EncoderInterface {
    public function supports(string $f): bool {
        return $f === 'JSON';
    }

    public function decode(string $d): array { //JSON
        $res = json_decode($d, true);
        return is_array($res) ? $res : [];
    }

    public function encode(array $d): string {
        return json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function checksupport(string $data): bool
    {
        return $data === 'JSON';
    }
}