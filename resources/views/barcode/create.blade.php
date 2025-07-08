<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Barcode</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --success: #10b981;
            --success-hover: #059669;
            --error: #ef4444;
            --text: #1f2937;
            --text-light: #6b7280;
            --border: #e5e7eb;
            --bg: #f9fafb;
            --card-bg: #ffffff;
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
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .container {
            flex: 1;
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 32px;
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
            font-weight: 600;
            color: var(--text);
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 20px;
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
            padding: 10px 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
            background-color: var(--card-bg);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.1s;
            border: none;
            user-select: none;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
        }

        .btn-primary:active {
            transform: translateY(1px);
        }

        .btn-success {
            background-color: var(--success);
            color: white;
        }

        .btn-success:hover {
            background-color: var(--success-hover);
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
            border-radius: 8px;
            background-color: var(--bg);
            text-align: center;
        }

        .barcode-container {
            display: inline-block;
            padding: 16px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .barcode-text {
            font-family: monospace;
            font-size: 12px;
            letter-spacing: 2px;
            margin-top: 8px;
            color: var(--text-light);
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
            border-radius: 8px;
            margin-top: 16px;
        }

        .decoded-grid {
            display: grid;
            grid-template-columns: 120px 1fr;
            gap: 12px;
            margin-bottom: 12px;
        }

        .decoded-label {
            font-weight: 500;
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
                padding: 20px;
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
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Generate Section (Left) -->
        <div class="container" style="margin-right: 10px;">
            <h2>Generate Barcode</h2>

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
            <form method="POST" action="{{ route('barcode.generate') }}">
                @csrf

                <div class="form-group">
                    <label for="buyer_id">Buyer</label>
                    <select name="buyer_id" id="buyer_id" class="form-control" required>
                        <option value="">-- Select Buyer --</option>
                        @foreach ($buyers as $id => $nama)
                            <option value="{{ $id }}" {{ old('buyer_id') == $id ? 'selected' : '' }}>
                                {{ $nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="pesanan_id">Order</label>
                    <select name="pesanan_id" id="pesanan_id" class="form-control" required>
                        <option value="">-- Select Order --</option>
                    </select>
                    <div id="pesanan-loading" class="error-message" style="display: none;">
                        <svg class="loading" style="border-top-color: var(--primary);"></svg>
                        Loading orders...
                    </div>
                </div>

                <div class="form-group">
                    <label for="item_variant_id">Item Variant</label>
                    <select name="item_variant_id" id="item_variant_id" class="form-control" required>
                        <option value="">-- Select Item Variant --</option>
                    </select>
                    <div id="variant-loading" class="error-message" style="display: none;">
                        <svg class="loading" style="border-top-color: var(--primary);"></svg>
                        Loading variants...
                    </div>
                </div>

                <div class="input-group">
                    <div class="form-group">
                        <label for="supplier_code">Supplier Code</label>
                        <input type="text" name="supplier_code" id="supplier_code" class="form-control"
                            maxlength="1" required value="{{ old('supplier_code') }}" placeholder="A-Z"
                            pattern="[A-Za-z]">
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
                        <label for="nomor_container">Container Number</label>
                        <input type="text" name="nomor_container" id="nomor_container" class="form-control"
                            maxlength="3" required value="{{ old('nomor_container') }}" placeholder="3 characters">
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
                    Generate Barcode
                </button>
            </form>

            @if (session('barcodeText'))
                <div class="barcode-preview" id="print-area">
                    <div class="barcode-container">
                        <img src="{{ route('barcode.image', ['text' => urlencode(session('barcodeText'))]) }}"
                            alt="Barcode" style="max-width: 5cm; height: 1.2cm;">
                        <div class="barcode-text">{{ session('barcodeText') }}</div>
                    </div>
                    <div class="actions">
                        <button class="btn btn-success" onclick="printBarcode()">
                            Print Barcode (5x2cm)
                        </button>
                        <button class="btn btn-primary" onclick="copyBarcodeText()">
                            Copy Barcode Text
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Decode Section (Right) -->
        <div class="container" style="margin-left: 10px;">
            <h2>Decode Barcode</h2>

            <!-- Reverse Search Section (Separate Form) -->
            <div class="reverse-search-section">
                <form method="POST" action="{{ route('barcode.decode') }}">
                    @csrf
                    <div class="form-group">
                        <label for="barcode_input">Enter Barcode</label>
                        <input type="text" name="barcode_input" id="barcode_input" class="form-control"
                            placeholder="Enter barcode to decode" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        Decode Barcode
                    </button>
                </form>
            </div>

            @if (session('decodedData'))
                <div class="barcode-preview" style="margin-top: 32px;">
                    <div style="background: var(--primary); padding: 18px 0; border-radius: 8px 8px 0 0;">
                        <h3 style="color: #fff; font-size: 20px; font-weight: 600; margin: 0;">
                            Decoded Barcode Information
                        </h3>
                    </div>
                    @if (session('decodedData')['is_valid'])
                        <div class="decoded-info" style="padding: 28px 24px 20px 24px;">
                            <div class="decoded-grid" style="grid-template-columns: 160px 1fr; gap: 18px;">
                                <div>
                                    <div class="decoded-label">Original Barcode</div>
                                    <div class="barcode-text"
                                        style="font-size: 15px; color: var(--text); margin-top: 2px;">
                                        {{ session('decodedData')['original_barcode'] }}
                                    </div>
                                </div>
                                <div>
                                    <div class="decoded-label">Item Variant</div>
                                    <div style="font-weight: 600; color: var(--primary); font-size: 15px;">
                                        {{ session('decodedData')['variant']->nama_variant ?? 'Not found' }}
                                    </div>
                                </div>
                                @if (session('decodedData')['buyer'])
                                    <div>
                                        <div class="decoded-label">Buyer</div>
                                        <div style="font-size: 15px;">
                                            {{ session('decodedData')['buyer']->nama_buyer }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="decoded-label">Order</div>
                                        <div style="font-size: 15px;">
                                            <span
                                                style="font-weight: 600;">#{{ session('decodedData')['order_sequence'] }}</span>
                                            @if (session('decodedData')['pesanan']->nomor_pesanan)
                                                <span style="color: var(--text-light); margin-left: 8px;">
                                                    {{ session('decodedData')['pesanan']->nomor_pesanan }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <div class="decoded-label">Supplier Code</div>
                                    <div style="font-family: monospace; font-size: 15px;">
                                        {{ session('decodedData')['supplier_code'] }}
                                        @if (session('decodedData')['supplier'])
                                            <span style="color: var(--text-light); margin-left: 8px;">
                                                ({{ session('decodedData')['supplier']->nama_supplier }})
                                            </span>
                                        @else
                                            <span style="color: var(--error); margin-left: 8px;">
                                                (Supplier not found)
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
            @if(session('barcodeText'))
            const barcodeText = "{{ session('barcodeText') }}";
            const printWindow = window.open('', '', 'width=1200,height=600');

            printWindow.document.write(`
                <html>
                <head>
                <title>Print Barcode</title>
                <style>
                    @page {
                    size: 5cm 2cm;
                    margin: 0;
                    }
                    body {
                    margin: 0;
                    padding: 0;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    align-items: center;
                    height: 100%;
                    font-family: monospace;
                    font-size: 14px;
                    letter-spacing: 2px;
                    }
                    .barcode-text {
                    text-align: center;
                    padding: 5px 0;
                    }
                    .cutting-line {
                    border-top: 1px dashed #000;
                    margin: 2px 0;
                    text-align: center;
                    font-size: 8px;
                    color: #999;
                    width: 100%;
                    }
                </style>
                </head>
                <body>
                <div class="barcode-text">${barcodeText}</div>
                <div class="cutting-line"></div>
                <div class="barcode-text">${barcodeText}</div>
                </body>
                </html>
            `);
            printWindow.document.close();

            setTimeout(() => {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            }, 300);
            @else
            alert('No barcode available to print');
            @endif
        }

        function copyBarcodeText() {
            @if (session('barcodeText'))
                const barcodeText = "{{ session('barcodeText') }}";
                navigator.clipboard.writeText(barcodeText).then(() => {
                    const btn = document.querySelector('.btn-primary');
                    const originalText = btn.textContent;
                    btn.textContent = 'Copied!';
                    setTimeout(() => {
                        btn.textContent = originalText;
                    }, 2000);
                });
            @else
                alert('No barcode available to copy');
            @endif
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
                            options += `<option value="${p.id}">${p.nomor_pesanan}</option>`;
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
                const pesananId = this.value;
                if (!pesananId) {
                    itemVariantSelect.innerHTML = '<option value="">-- Select Item Variant --</option>';
                    return;
                }

                itemVariantSelect.innerHTML = '<option value="">Loading...</option>';
                itemVariantSelect.disabled = true;
                variantLoading.style.display = 'flex';

                fetch(`/barcode/item-variant/${pesananId}`)
                    .then(res => {
                        if (!res.ok) throw new Error('Failed to load variants');
                        return res.json();
                    })
                    .then(data => {
                        let options = '<option value="">-- Select Item Variant --</option>';
                        data.forEach(i => {
                            options += `<option value="${i.id}">${i.nama_variant}</option>`;
                        });
                        itemVariantSelect.innerHTML = options;
                        itemVariantSelect.disabled = false;
                    })
                    .catch(error => {
                        itemVariantSelect.innerHTML =
                        '<option value="">Error loading variants</option>';
                        console.error('Error:', error);
                    })
                    .finally(() => {
                        variantLoading.style.display = 'none';
                    });
            });

            // Prevent form submission if validation fails
            document.querySelector('form[action="{{ route('barcode.generate') }}"]').addEventListener('submit',
                function(e) {
                    let isValid = true;

                    // Validate supplier code
                    if (!/^[A-Za-z]$/.test(supplierCode.value)) {
                        supplierError.style.display = 'flex';
                        isValid = false;
                    }

                    // Validate container number
                    if (containerNumber.value.length !== 3) {
                        containerError.style.display = 'flex';
                        isValid = false;
                    }

                    if (!isValid) {
                        e.preventDefault();
                        generateBtn.style.transform = 'translateX(0)';
                        setTimeout(() => {
                            generateBtn.style.transform = 'translateX(5px)';
                            setTimeout(() => {
                                generateBtn.style.transform = 'translateX(-5px)';
                                setTimeout(() => {
                                    generateBtn.style.transform = 'translateX(0)';
                                }, 50);
                            }, 50);
                        }, 50);
                    }
                });


        });
    </script>
</body>

</html>
