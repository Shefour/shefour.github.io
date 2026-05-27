<?php
require_once 'autoload.php';

use App\Serializer;
use App\Encoder\CsvEncoder;
use App\Encoder\JsonEncoder;
use App\Encoder\YamlEncoder;

$val = $_COOKIE['c_val'] ?? '';
$in  = $_COOKIE['c_in']  ?? 'CSV';
$out = $_COOKIE['c_out'] ?? 'JSON';
$res = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $val = $_POST['data'] ?? '';
    $in  = $_POST['format_in'] ?? 'CSV';
    $out = $_POST['format_out'] ?? 'JSON';

    setcookie('c_val', $val, time() + 3600);
    setcookie('c_in', $in, time() + 3600);
    setcookie('c_out', $out, time() + 3600);

    $encoders = [
        new CsvEncoder('CSV', ","),
        new CsvEncoder('SSV', ";"),
        new CsvEncoder('TSV', "\t"),
        new JsonEncoder(),
        new YamlEncoder()
    ];

    $serializer = new Serializer($encoders);
    $res = $serializer->convert($val, $in, $out);
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Converter</title>
    <style>
        pre {
            background: #f4f4f4;
            padding: 10px;
            border: 1px solid #ccc;
            min-height: 20px;
        }
    </style>
</head>

<body>
<form method="POST">
        <textarea name="data" rows="10" cols="60"
                  placeholder="Wklej dane tutaj..."><?= htmlspecialchars($val) ?>
        </textarea><br>

    <label>Z:</label>
    <select name="format_in">
        <?php foreach(['CSV','SSV','TSV','JSON','YAML'] as $f): ?>
            <option value="<?= $f ?>" <?= $in === $f ? 'selected' : '' ?>><?= $f ?></option>
        <?php endforeach; ?>
    </select>

    <label>Na:</label>
    <select name="format_out">
        <?php foreach(['CSV','SSV','TSV','JSON','YAML'] as $f): ?>
            <option value="<?= $f ?>" <?= $out === $f ? 'selected' : '' ?>><?= $f ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Konwertuj</button>
</form>

<h3>Wynik:</h3>
<pre id="out"><?= htmlspecialchars($res) ?></pre>
<button type="button" onclick="copyResult()">Kopiuj wynik</button>

<script>
    function copyResult() {
        const text = document.getElementById('out').innerText;
        if (text) {
            navigator.clipboard.writeText(text).then(() => alert('Skopiowano!'));
        }
    }
</script>
</body>

</html>