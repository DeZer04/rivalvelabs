<!DOCTYPE html>
<html>
<head>
    <style>
        body { text-align: center; font-family: sans-serif; margin-top: 100px; }
        img { width: 300px; }
    </style>
</head>
<body>
    <h2>Barcode: {{ $code }}</h2>
    <img src="https://barcode.tec-it.com/barcode.ashx?data={{ $code }}&code=Code128&translate-esc=true" alt="Barcode" />
</body>
</html>
