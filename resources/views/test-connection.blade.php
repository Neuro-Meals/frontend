<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>API Connection Test</title>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #064e3b 0%, #065f46 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: #1f2937;
        }
        .card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: center;
        }
        .logo {
            height: 60px;
            margin-bottom: 20px;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 8px;
            color: #111827;
        }
        .subtitle {
            color: #6b7280;
            margin-bottom: 30px;
            font-size: 14px;
        }
        .spinner {
            width: 56px;
            height: 56px;
            border: 5px solid #e5e7eb;
            border-top-color: #10b981;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        .status-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
        }
        .success { background: #d1fae5; color: #059669; }
        .error { background: #fee2e2; color: #dc2626; }
        .result-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        .result-text {
            color: #4b5563;
            font-size: 14px;
            line-height: 1.6;
            word-break: break-word;
        }
        .details {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 16px;
            margin-top: 20px;
            text-align: left;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            color: #374151;
        }
        .details-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        .details-row:last-child { margin-bottom: 0; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding: 12px 24px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-primary {
            background: #10b981;
            color: white;
        }
        .btn-primary:hover { background: #059669; }
        .btn-secondary {
            background: #f3f4f6;
            color: #374151;
        }
        .btn-secondary:hover { background: #e5e7eb; }
        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }
    </style>
</head>
<body>
    <div class="card" x-data="connectionTest()" x-init="runTest()">
        <img src="{{ asset('whitelogo.png') }}" alt="Logo" class="logo">
        <h1>API Connection Test</h1>
        <p class="subtitle">Testing connection to base URL: <strong>{{ config('api.base_url') }}</strong></p>

        {{-- Loading --}}
        <div x-show="state === 'loading'" x-cloak>
            <div class="spinner"></div>
            <p class="result-text">Checking connection, please wait...</p>
        </div>

        {{-- Success --}}
        <div x-show="state === 'success'" x-cloak>
            <div class="status-icon success">✓</div>
            <p class="result-title" style="color: #059669;">Connection Successful</p>
            <p class="result-text" x-text="message"></p>
            <div class="details">
                <div class="details-row"><span>Base URL:</span> <span x-text="data.base_url"></span></div>
                <div class="details-row"><span>Status Code:</span> <span x-text="data.status"></span></div>
                <div class="details-row"><span>Duration:</span> <span x-text="data.duration_ms + ' ms'"></span></div>
            </div>
            <div class="btn-group">
                <button class="btn btn-primary" @click="runTest()">Test Again</button>
                <a href="{{ route('register') }}" class="btn btn-secondary">Go to Register</a>
            </div>
        </div>

        {{-- Error --}}
        <div x-show="state === 'error'" x-cloak>
            <div class="status-icon error">✕</div>
            <p class="result-title" style="color: #dc2626;">Connection Failed</p>
            <p class="result-text" x-text="message"></p>
            <div class="details">
                <div class="details-row"><span>Base URL:</span> <span x-text="data.base_url"></span></div>
                <div class="details-row"><span>Duration:</span> <span x-text="data.duration_ms + ' ms'"></span></div>
                <div class="details-row" style="display: block; margin-top: 8px;">
                    <span>Error:</span>
                    <div style="margin-top: 6px; white-space: pre-wrap;" x-text="data.message"></div>
                </div>
            </div>
            <div class="btn-group">
                <button class="btn btn-primary" @click="runTest()">Retry</button>
                <a href="{{ route('register') }}" class="btn btn-secondary">Back to Register</a>
            </div>
        </div>
    </div>

    <script>
        function connectionTest() {
            return {
                state: 'loading',
                message: '',
                data: {},
                async runTest() {
                    this.state = 'loading';
                    this.message = '';
                    this.data = {};

                    try {
                        const response = await fetch('{{ route('test.api') }}', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const result = await response.json();
                        this.data = result;

                        if (result.success) {
                            this.state = 'success';
                            this.message = `API server responded with HTTP ${result.status} in ${result.duration_ms}ms.`;
                        } else {
                            this.state = 'error';
                            this.message = result.message || 'Unable to reach the API server.';
                        }
                    } catch (error) {
                        this.state = 'error';
                        this.data = {
                            base_url: '{{ config('api.base_url') }}',
                            duration_ms: 0,
                            message: error.message
                        };
                        this.message = 'Network error while testing connection.';
                    }
                }
            };
        }
    </script>
</body>
</html>
