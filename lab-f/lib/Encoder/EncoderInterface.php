<?php
namespace App\Encoder;
//namespace lib\Encoder;

interface EncoderInterface
{
    public function encode(array $data): string;
    public function decode(string $data): array;
    public function checksupport(string $data): bool;
}