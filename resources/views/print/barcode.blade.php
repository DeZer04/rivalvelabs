<!DOCTYPE html>
<html>
<head>
    <title>Cetak Barcode</title>
    <style>
        @media print {
            @page {
                size: 50mm 20mm;
                margin: 0;
            }

            body {
                margin: 0;
                padding: 0;
            }

            .label {
                width: 50mm;
                height: 20mm;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                font-family: Arial, sans-serif;
                font-size: 8pt;
            }

            .barcode {
                width: 90%;
                height: auto;
            }

            .text {
                margin-top: 2mm;
                text-align: center;
                font-size: 7pt;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="label">
        <img class="barcode" src="https://barcode.tec-it.com/barcode.ashx?data={{ $code }}&code=Code128&translate-esc=true" alt="Barcode">
    </div>
</body>
</html>
