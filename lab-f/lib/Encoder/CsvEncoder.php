<?php
namespace App\Encoder;
//namespace lib\Encoder;

use App\Encoder\EncoderInterface;

class CsvEncoder implements EncoderInterface{
    private string $format;
    private string $delim;

    public function __construct(string $format, string $delim = ","){
        $this->format = $format;
        $this->delim = $delim;
    }

    public function encode(array $data): string
    {
        if (empty($data)) return "";
        $f = fopen("php://temp", "r+");
        fputcsv($f, array_keys($data[0]), $this->delim);
        foreach ($data as $row) {
            fputcsv($f, $row, $this->delim);
        }
        rewind($f);
        $res = stream_get_contents($f);
        fclose($f);
        return $res;
    }

    public function decode(string $data): array // CSV
    {
        $lines = $data
                |> trim(...)
                |> (fn($x) => str_replace("\r", "", $x))
                |> (fn($x) => explode("\n", $x));

        $headerLine = array_shift($lines);
        if (!$headerLine) return [];

        $h = str_getcsv($headerLine, $this->delim);
        $res = [];

        foreach ($lines as $l) {
            if (empty(trim($l))) continue;

            $row = str_getcsv($l, $this->delim);

            if (count($h) === count($row)) {
                $res[] = array_combine($h, $row);
            }
        }
        return $res;
    }

    public function checksupport(string $data): bool
    {
        return $data === $this->format;
    }
}