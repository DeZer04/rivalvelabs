<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Preview Barcode</title>
    <style>
        body {
            font-family: monospace;
            padding: 40px;
            text-align: center;
        }
        .barcode-box {
            display: inline-block;
            padding: 16px;
            border: 2px solid #444;
            font-size: 22px;
            margin-top: 30px;
        }
        .button {
            margin-top: 30px;
            padding: 10px 18px;
            font-size: 16px;
            text-decoration: none;
            background-color: #3490dc;
            color: white;
            border-radius: 6px;
        }
        .button:hover {
            background-color: #2779bd;
        }
    </style>
</head>
<body>

    <h2>Hasil Generate Barcode</h2>

    <div class="barcode-box">
        | {{ $barcodeText }} |
    </div>

    <div>
        <a href="{{ route('barcode.create') }}" class="button">Buat Barcode Lain</a>
    </div>

</body>
</html>
