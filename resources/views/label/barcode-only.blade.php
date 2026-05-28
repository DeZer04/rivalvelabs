<!DOCTYPE html>
<html>
<head>
    <style>
        @page {
            size: 141.73pt 56.69pt; /* 5x2 cm dalam point */
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            width: 141.73pt;
            height: 56.69pt;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: sans-serif;
            font-size: 8pt;
        }
        img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <img src="{{ $barcodeImage }}" alt="Barcode">
    <div>{{ $code }}</div>
</body>
</html>
