<?php
namespace App;

class Serializer {
    private array $encoders;

    public function __construct(array $encoders) {
        $this->encoders = $encoders;
    }

    public function convert(string $data, string $from, string $to): string {
        $inEnc = $this->getEncoder($from);
        $outEnc = $this->getEncoder($to);

        $decoded = $inEnc->decode($data);

        return $outEnc->encode($decoded);
    }

    private function getEncoder(string $format) {
        foreach ($this->encoders as $e) {
            if ($e->checksupport($format)) return $e;
        }
        return null;
    }
}