<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barcode Generator & Decoder</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #e0e7ff;
            --success: #10b981;
            --success-hover: #059669;
            --error: #ef4444;
            --warning: #f59e0b;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --border-dark: #d1d5db;
            --bg: #f9fafb;
            --card-bg: #ffffff;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 10px;
            --transition: all 0.2s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--text);
            background-color: var(--bg);
            padding: 20px;
        }

        .main-container {
            display: flex;
            gap: 24px;
            max-width: 1280px;
            margin: 0 auto;
        }

        .container {
            flex: 1;
            background-color: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            padding: 32px;
            transition: var(--transition);
        }

        .container:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        h2 {
            text-align: center;
            margin-bottom: 28px;
            font-weight: 600;
            color: var(--text);
            font-size: 24px;
            position: relative;
            padding-bottom: 12px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: var(--primary);
            border-radius: 3px;
        }

        .form-group {
            margin-bottom: 22px;
            position: relative;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            font-size: 14px;
            color: var(--text);
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: var(--transition);
            background-color: var(--card-bg);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            user-select: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
            transform: translateY(-1px);
        }

        .btn-warning {
            background-color: var(--warning);
            color: white;
        }

        .btn-warning:hover {
            background-color: #e67e22;
            transform: translateY(-1px);
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .error-message {
            color: var(--error);
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .error-message svg {
            flex-shrink: 0;
        }

        .error-list {
            background-color: rgba(239, 68, 68, 0.1);
            border-left: 3px solid var(--error);
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 24px;
        }

        .error-list ul {
            list-style-position: inside;
        }

        .error-list li {
            margin-bottom: 4px;
            color: var(--error);
        }

        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .barcode-preview {
            margin-top: 40px;
            padding: 24px;
            border-radius: var(--radius);
            background-color: var(--bg);
            text-align: center;
            border: 1px dashed var(--border-dark);
            transition: var(--transition);
        }

        .barcode-preview:hover {
            border-color: var(--primary);
            background-color: var(--primary-light);
        }

        .barcode-container {
            display: inline-block;
            padding: 16px;
            background-color: white;
            border-radius: var(--radius);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border);
        }

        .barcode-text {
            font-family: 'consolas', monospace;
            font-size: 30px;
            letter-spacing: 2px;
            margin: 8px 0;
            color: var(--text);
            font-weight: 400;
            padding: 10px 20px;
            background-color: #f8f8f8;
            border-radius: 4px;
        }

        .actions {
            margin-top: 20px;
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .input-group {
            display: flex;
            gap: 12px;
        }

        .input-group .form-group {
            flex: 1;
        }

        .reverse-search-section {
            margin-top: 0;
        }

        .decoded-info {
            background-color: var(--card-bg);
            padding: 16px;
            border-radius: var(--radius);
            margin-top: 16px;
            border: 1px solid var(--border);
        }

        .decoded-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        .decoded-label {
            font-weight: 500;
            color: var(--text-light);
        }

        .card-header {
            background: var(--primary);
            padding: 18px 24px;
            border-radius: var(--radius) var(--radius) 0 0;
            color: white;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header i {
            font-size: 20px;
        }

        .card-header h3 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .tooltip {
            position: relative;
            display: inline-block;
            margin-left: 6px;
            cursor: pointer;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 200px;
            background-color: var(--text);
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            font-weight: normal;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 38px;
            color: var(--text-light);
            cursor: pointer;
        }

        .input-icon:hover {
            color: var(--primary);
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
                visibility: visible;
            }

            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 5cm;
                height: 2cm;
            }
        }

        @media (max-width: 1024px) {
            .main-container {
                flex-direction: column;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding: 24px;
            }

            .input-group {
                flex-direction: column;
                gap: 20px;
            }

            .actions {
                flex-direction: column;
            }

            .decoded-grid {
                grid-template-columns: 1fr;
                gap: 8px;
            }
        }

        /* Animation for form errors */
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .shake {
            animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
        }

        /* Floating label effect */
        .floating-label-group {
            position: relative;
            margin-bottom: 22px;
        }

        .floating-label {
            position: absolute;
            pointer-events: none;
            left: 12px;
            top: 12px;
            transition: var(--transition);
            padding: 0 4px;
            background: var(--card-bg);
            color: var(--text-light);
            font-size: 14px;
        }

        .form-control:focus ~ .floating-label,
        .form-control:not(:placeholder-shown) ~ .floating-label {
            top: -10px;
            left: 10px;
            font-size: 12px;
            color: var(--primary);
            font-weight: 500;
        }

        /* Barcode scanner animation */
        .scanner-animation {
            position: relative;
            overflow: hidden;
        }

        .scanner-animation::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, rgba(79, 70, 229, 0.8), transparent);
            animation: scan 2s linear infinite;
        }

        @keyframes scan {
            0% { transform: translateY(0); }
            100% { transform: translateY(100vh); }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128+Text&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main-container">
        <!-- Generate Section (Left) -->
        <div class="container" style="margin-right: 10px;">
            <h2><i class="fas fa-barcode"></i> Generate Barcode</h2>

            @if ($errors->any())
                <div class="error-list">
                    <ul>
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Generate Barcode Form -->
            <form method="POST" action="{{ route('barcode.generate') }}" id="generate-form">
                @csrf
                <input type="hidden" name="nomor_pesanan" id="nomor_pesanan_hidden">

                <div class="form-group">
                    <label for="buyer_id">Buyer <span class="tooltip"><i class="fas fa-info-circle"></i>
                            <span class="tooltiptext">Select the buyer associated with this order</span>
                        </span></label>
                    <select name="buyer_id" id="buyer_id" class="form-control" required>
                        <option value="">-- Select Buyer --</option>
                        @foreach ($buyers as $id => $nama)
                            <option value="{{ $id }}" {{ old('buyer_id') == $id ? 'selected' : '' }}>
                                {{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesanan_id">Order <span class="tooltip"><i class="fas fa-info-circle"></i>
                            <span class="tooltiptext">Select the specific order for this barcode</span>
                        </span></label>
                    <select name="pesanan_id" id="pesanan_id" class="form-control" required>
                        <option value="">-- Select Order --</option>
                    </select>
                    <div id="pesanan-loading" class="error-message" style="display: none; color: var(--primary);">
                        <svg class="loading" style="border-top-color: var(--primary);"></svg>
                        Loading orders...
                    </div>
                </div>

                <div class="form-group">
                    <label for="item_variant_id">Item Variant <span class="tooltip"><i class="fas fa-info-circle"></i>
                            <span class="tooltiptext">Select the specific item variant for this barcode</span>
                        </span></label>
                    <select name="item_variant_id" id="item_variant_id" class="form-control" required>
                        <option value="">-- Select Item Variant --</option>
                    </select>
                    <div id="variant-loading" class="error-message" style="display: none; color: var(--primary);">
                        <svg class="loading" style="border-top-color: var(--primary);"></svg>
                        Loading variants...
                    </div>
                </div>

                <div class="input-group">
                    <div class="form-group">
                        <label for="supplier_code">Production Line Code <span class="tooltip"><i class="fas fa-info-circle"></i>
                                <span class="tooltiptext">Enter a single letter (A-Z) identifying the production line</span>
                            </span></label>
                        <input type="text" name="supplier_code" id="supplier_code" class="form-control"
                            maxlength="1" required value="{{ old('supplier_code') }}" placeholder="A-Z"
                            pattern="[A-Za-z]">
                        <i class="fas fa-keyboard input-icon" onclick="focusAndOpenKeyboard('supplier_code')"></i>
                        <div class="error-message" id="supplier-error" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            Must be a single letter (A-Z)
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="nomor_container">Container Number <span class="tooltip"><i class="fas fa-info-circle"></i>
                                <span class="tooltiptext">Enter a 3-character container identifier</span>
                            </span></label>
                        <input type="text" name="nomor_container" id="nomor_container" class="form-control"
                            maxlength="3" required value="{{ old('nomor_container') }}" placeholder="3 characters">
                        <i class="fas fa-keyboard input-icon" onclick="focusAndOpenKeyboard('nomor_container')"></i>
                        <div class="error-message" id="container-error" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="8" x2="12" y2="12"></line>
                                <line x1="12" y1="16" x2="12.01" y2="16"></line>
                            </svg>
                            Must be exactly 3 characters
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-block" id="generate-btn">
                    <i class="fas fa-barcode"></i> Generate Barcode
                </button>
            </form>

            @if (session('barcodeText'))
                <div class="barcode-preview" id="print-area">
                    <div class="barcode-container">
                        <div class="barcode-text">{{ session('barcodeText') }}</div>
                    </div>
                    <div class="actions">
                        <button class="btn btn-success" onclick="printBarcode()">
                            <i class="fas fa-print"></i> Print Barcode
                        </button>
                        <button class="btn btn-primary" id="copy-barcode-btn" onclick="copyBarcodeText()">
                            <i class="far fa-copy"></i> Copy Text
                        </button>
                        <button class="btn btn-warning" onclick="generateNewBarcode()">
                            <i class="fas fa-sync-alt"></i> New Barcode
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Decode Section (Right) -->
        <div class="container" style="margin-left: 10px;">
            <h2><i class="fas fa-search"></i> Decode Barcode</h2>

            <!-- Reverse Search Section (Separate Form) -->
            <div class="reverse-search-section">
                <form method="POST" action="{{ route('barcode.decode') }}" id="decode-form">
                    @csrf
                    <div class="form-group">
                        <label for="barcode_input">Enter Barcode <span class="tooltip"><i class="fas fa-info-circle"></i>
                                <span class="tooltiptext">Paste or scan a barcode to decode its information</span>
                            </span></label>
                        <div class="scanner-animation">
                            <input type="text" name="barcode_input" id="barcode_input" class="form-control"
                                placeholder="Enter barcode to decode" required>
                        </div>
                        <i class="fas fa-barcode input-icon" onclick="startBarcodeScanner()"></i>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Decode Barcode
                    </button>
                </form>
            </div>

            @if (session('decodedData'))
                <div class="barcode-preview" style="margin-top: 32px;">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <h3>Decoded Barcode Information</h3>
                    </div>
                    @if (session('decodedData')['is_valid'])
                        <div class="decoded-info" style="padding: 28px 24px 20px 24px;">
                            <div class="decoded-grid" style="grid-template-columns: 1fr 1fr;">
                                <!-- Row 1: Item (variant) -->
                                <div style="grid-column: 1 / span 2;">
                                    <div class="decoded-label">Item Variant</div>
                                    <div style="font-weight: 600; color: var(--primary); font-size: 15px;">
                                        {{ session('decodedData')['variant']->nama_variant ?? 'Not found' }}
                                    </div>
                                </div>
                                <!-- Row 2: Original Barcode | Buyer -->
                                <div>
                                    <div class="decoded-label">Original Barcode</div>
                                    <div class="barcode-text" style="font-size: 15px; color: var(--text); margin-top: 2px;">
                                        {{ session('decodedData')['original_barcode'] }}
                                    </div>
                                </div>
                                <div>
                                    <div class="decoded-label">Buyer</div>
                                    <div style="font-size: 15px;">
                                        {{ session('decodedData')['buyer']->nama_buyer ?? '-' }}
                                    </div>
                                </div>
                                <!-- Row 3: Order | Container -->
                                <div>
                                    <div class="decoded-label">Order</div>
                                    <div style="font-size: 15px;">
                                        <span style="font-weight: 600;">#{{ session('decodedData')['order_sequence'] }}</span>
                                        @if (session('decodedData')['pesanan']->nomor_pesanan)
                                            <span style="color: var(--text-light); margin-left: 8px;">
                                                {{ session('decodedData')['pesanan']->nomor_pesanan }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <div class="decoded-label">Container Number</div>
                                    <div style="font-family: monospace; font-weight: 600; font-size: 15px;">
                                        {{ session('decodedData')['container_number'] }}
                                    </div>
                                </div>
                                <!-- Row 4: Supplier Code (full width) -->
                                <div style="grid-column: 1 / span 2;">
                                    <div class="decoded-label">Production Line Code</div>
                                    <div style="font-family: monospace; font-size: 15px;">
                                        {{ session('decodedData')['supplier_code'] }}
                                        @if (session('decodedData')['supplier'])
                                            <span style="color: var(--text-light); margin-left: 8px;">
                                                ({{ session('decodedData')['supplier']->nama_supplier }})
                                            </span>
                                        @else
                                            <span style="color: var(--error); margin-left: 8px;">
                                                (Production Line not found)
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div style="padding: 28px 24px;">
                            <div class="error-message"
                                style="display: flex; align-items: flex-start; background: #fef2f2; border-left: 4px solid var(--error); border-radius: 6px; padding: 16px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" style="color: var(--error); margin-right: 12px;"
                                    viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="12"></line>
                                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                                </svg>
                                <div>
                                    <div style="font-weight: 600; color: var(--error); font-size: 16px;">Invalid
                                        Barcode</div>
                                    <div style="color: var(--error); font-size: 14px; margin-top: 4px;">
                                        {{ session('decodedData')['error'] ?? 'Could not decode barcode' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <script>
        function printBarcode() {
            const barcodeText = "{{ session('barcodeText') ?? '' }}";
            if (!barcodeText) {
                showAlert('error', 'No barcode available to print');
                return;
            }

            const printWindow = window.open('', '', 'width=800,height=500');

            // Ukuran dalam piksel untuk 5x2 cm pada 96 DPI
            const labelWidth = 189; // 5 cm = 189 piksel
            const labelHeight = 76; // 2 cm = 76 piksel
            const margin = 5; // Margin kecil
            const gap = 10; // Jarak antara dua barcode

            printWindow.document.write(`
            <html>
            <head>
            <title>Print Barcode Text</title>
            <style>
                @page {
                    size: ${labelWidth + margin*2}px ${(labelHeight * 2) + (gap) + (margin * 2)}px;
                    margin: ${margin}px;
                }
                body {
                    margin: 0;
                    padding: 0;
                    background-color: white;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                    align-items: center;
                    height: 100vh;
                    position: relative;
                }
                .label {
                    width: ${labelWidth}px;
                    height: ${labelHeight}px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    box-sizing: border-box;
                }
                .barcode-text {
                    font-family: 'Consolas', monospace;
                    font-size: 16px;
                    font-weight: bold;
                    letter-spacing: 1.5px;
                    text-align: center;
                    padding: 5px;
                    width: 100%;
                }
                .cut-line {
                    position: absolute;
                    left: ${margin}px;
                    right: ${margin}px;
                    top: 50%;
                    height: 0;
                    border-top: 1px dashed #000;
                    pointer-events: none;
                }
                .cut-line::before,
                .cut-line::after {
                    position: absolute;
                    top: -10px;
                    font-size: 12px;
                    color: #999;
                }
                .cut-line::before {
                    left: 5px;
                }
                .cut-line::after {
                    right: 5px;
                }
            </style>
            </head>
            <body>
                <div class="label">
                    <div class="barcode-text">${barcodeText}</div>
                </div>
                <div class="cut-line"></div>
                <div class="label">
                    <div class="barcode-text">${barcodeText}</div>
                </div>
                <script>
                    setTimeout(() => {
                        window.print();
                        window.onafterprint = function() {
                            window.close();
                        };
                    }, 100);
                <\/script>
            </body>
            </html>
            `);
            printWindow.document.close();
        }

        function copyBarcodeText() {
            const barcodeText = "{{ session('barcodeText') ?? '' }}";
            if (!barcodeText) {
                showAlert('error', 'No barcode available to copy');
                return;
            }

            // Use the modern Clipboard API if available
            if (navigator.clipboard) {
                navigator.clipboard.writeText(barcodeText).then(() => {
                    const btn = document.getElementById('copy-barcode-btn');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    btn.style.backgroundColor = 'var(--success)';
                    setTimeout(() => {
                        btn.innerHTML = originalText;
                        btn.style.backgroundColor = 'var(--primary)';
                    }, 2000);
                }).catch(err => {
                    showAlert('error', 'Failed to copy: ' + err);
                });
            } else {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = barcodeText;
                textarea.style.position = 'fixed';
                document.body.appendChild(textarea);
                textarea.select();

                try {
                    const successful = document.execCommand('copy');
                    if (successful) {
                        const btn = document.getElementById('copy-barcode-btn');
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                        btn.style.backgroundColor = 'var(--success)';
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.style.backgroundColor = 'var(--primary)';
                        }, 2000);
                    } else {
                        showAlert('error', 'Failed to copy barcode text');
                    }
                } catch (err) {
                    showAlert('error', 'Error copying barcode text: ' + err);
                } finally {
                    document.body.removeChild(textarea);
                }
            }
        }

        function generateNewBarcode() {
            // Reset the form and scroll to top
            document.getElementById('generate-form').reset();
            document.getElementById('pesanan_id').innerHTML = '<option value="">-- Select Order --</option>';
            document.getElementById('item_variant_id').innerHTML = '<option value="">-- Select Item Variant --</option>';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function showAlert(type, message) {
            // Create alert element
            const alert = document.createElement('div');
            alert.style.position = 'fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.padding = '16px';
            alert.style.borderRadius = '8px';
            alert.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            alert.style.color = 'white';
            alert.style.display = 'flex';
            alert.style.alignItems = 'center';
            alert.style.gap = '10px';
            alert.style.zIndex = '1000';
            alert.style.transition = 'all 0.3s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';

            if (type === 'error') {
                alert.style.backgroundColor = 'var(--error)';
                alert.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
            } else {
                alert.style.backgroundColor = 'var(--success)';
                alert.innerHTML = `<i class="fas fa-check-circle"></i> ${message}`;
            }

            document.body.appendChild(alert);

            // Animate in
            setTimeout(() => {
                alert.style.opacity = '1';
                alert.style.transform = 'translateY(0)';
            }, 10);

            // Animate out after 3 seconds
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-20px)';
                setTimeout(() => {
                    document.body.removeChild(alert);
                }, 300);
            }, 3000);
        }

        function startBarcodeScanner() {
            // This is a placeholder for actual barcode scanner integration
            // In a real implementation, this would interface with a barcode scanner API
            showAlert('info', 'Barcode scanner activated. Scan a barcode now.');
            document.getElementById('barcode_input').focus();
        }

        function focusAndOpenKeyboard(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                element.focus();
                // For mobile devices, we need to ensure the virtual keyboard opens
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    setTimeout(() => {
                        element.click();
                    }, 100);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const buyerSelect = document.getElementById('buyer_id');
            const pesananSelect = document.getElementById('pesanan_id');
            const itemVariantSelect = document.getElementById('item_variant_id');
            const pesananLoading = document.getElementById('pesanan-loading');
            const variantLoading = document.getElementById('variant-loading');
            const supplierCode = document.getElementById('supplier_code');
            const containerNumber = document.getElementById('nomor_container');
            const supplierError = document.getElementById('supplier-error');
            const containerError = document.getElementById('container-error');
            const generateBtn = document.getElementById('generate-btn');
            const nomorPesananHidden = document.getElementById('nomor_pesanan_hidden');
            const generateForm = document.getElementById('generate-form');
            const decodeForm = document.getElementById('decode-form');

            // Form validation
            supplierCode.addEventListener('input', function() {
                const value = this.value.toUpperCase();
                this.value = value;

                if (value && !/^[A-Z]$/.test(value)) {
                    supplierError.style.display = 'flex';
                } else {
                    supplierError.style.display = 'none';
                }
            });

            containerNumber.addEventListener('input', function() {
                if (this.value.length > 0 && this.value.length !== 3) {
                    containerError.style.display = 'flex';
                } else {
                    containerError.style.display = 'none';
                }
            });

            // Dynamic dropdown loading
            buyerSelect.addEventListener('change', function() {
                const buyerId = this.value;
                if (!buyerId) {
                    pesananSelect.innerHTML = '<option value="">-- Select Order --</option>';
                    itemVariantSelect.innerHTML = '<option value="">-- Select Item Variant --</option>';
                    return;
                }

                pesananSelect.innerHTML = '<option value="">Loading...</option>';
                pesananSelect.disabled = true;
                pesananLoading.style.display = 'flex';

                fetch(`/barcode/pesanan/${buyerId}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load orders');
                        return res.json();
                    })
                    .then(data => {
                        let options = '<option value="">-- Select Order --</option>';
                        data.forEach(p => {
                            options += `<option value="${p.id}" data-nomor="${p.nomor_pesanan}">${p.nomor_pesanan}</option>`;
                        });
                        pesananSelect.innerHTML = options;
                        pesananSelect.disabled = false;
                    })
                    .catch(error => {
                        pesananSelect.innerHTML = '<option value="">Error loading orders</option>';
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        pesananLoading.style.display = 'none';
                    });
            });

            pesananSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const nomorPesanan = selectedOption.text;

                // Set hidden field with nomor_pesanan
                nomorPesananHidden.value = nomorPesanan;

                if (!selectedOption.value) {
                    itemVariantSelect.innerHTML = '<option value="">-- Select Item Variant --</option>';
                    return;
                }

                itemVariantSelect.innerHTML = '<option value="">Loading...</option>';
                itemVariantSelect.disabled = true;
                variantLoading.style.display = 'flex';

                // Send the order ID (value) instead of the order number (text)
                fetch(`/barcode/item-variant/${this.value}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load variants');
                        return res.json();
                    })
                    .then(data => {
                        let options = '<option value="">-- Select Item Variant --</option>';
                        data.forEach(v => {
                            options += `<option value="${v.id}">${v.nama_variant}</option>`;
                        });
                        itemVariantSelect.innerHTML = options;
                        itemVariantSelect.disabled = false;
                    })
                    .catch(error => {
                        itemVariantSelect.innerHTML = '<option value="">Error loading variants</option>';
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        variantLoading.style.display = 'none';
                    });
            });

            // Form submission validation
            generateForm.addEventListener('submit', function(e) {
                let isValid = true;

                // Validate supplier code
                if (!/^[A-Za-z]$/.test(supplierCode.value)) {
                    supplierError.style.display = 'flex';
                    supplierCode.classList.add('shake');
                    isValid = false;
                } else {
                    supplierError.style.display = 'none';
                    supplierCode.classList.remove('shake');
                }

                // Validate container number
                if (containerNumber.value.length !== 3) {
                    containerError.style.display = 'flex';
                    containerNumber.classList.add('shake');
                    isValid = false;
                } else {
                    containerError.style.display = 'none';
                    containerNumber.classList.remove('shake');
                }

                if (!isValid) {
                    e.preventDefault();
                    setTimeout(() => {
                        supplierCode.classList.remove('shake');
                        containerNumber.classList.remove('shake');
                    }, 500);
                }
            });

            // Auto-uppercase for supplier code
            supplierCode.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Auto-uppercase for container number
            containerNumber.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Focus barcode input on decode form load if there's no data
            @if (!session('decodedData'))
                document.getElementById('barcode_input').focus();
            @endif

            // If there's a barcode to print (from session), scroll to it
            @if (session('barcodeText'))
                setTimeout(() => {
                    const barcodePreview = document.querySelector('.barcode-preview');
                    if (barcodePreview) {
                        barcodePreview.scrollIntoView({ behavior: 'smooth' });
                    }
                }, 300);
            @endif
        });
    </script>
</body>
</html>
