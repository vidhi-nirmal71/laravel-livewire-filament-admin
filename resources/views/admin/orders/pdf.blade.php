<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order {{ $record->order_number ?? $record->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#222; }
        .card { border:1px solid #e5e7eb; border-radius:8px; padding:16px; }
        .cols { display:flex; gap:16px; }
        .col { flex:1; }
        dt { font-weight:600; float:left; width:55%; }
        dd { margin:0 0 8px 0; text-align:right; }
        h2 { font-size:16px; margin-bottom:8px; text-decoration:underline; }
        .muted { color:#6b7280; }
        @page { margin:20mm; }
        .fi-section-content.p-6{
            margin-left: 25px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1 style="font-size:18px;">Order Details</h1>

        <div class="cols" style="margin-top:12px;">
            <div class="col">
                <h2>ORDER INFORMATION</h2>
                <dl>
                    <dt>Order Number</dt><dd>: {{ $record->order_number ?? '-' }}</dd>
                    <dt>Order Date</dt><dd>: {{ $record->created_at?->format('D d M, Y g:i a') ?? '-' }}</dd>
                    <dt>Quantity</dt><dd>: {{ $record->quantity ?? 0 }}</dd>
                    <dt>Shipping Charge</dt><dd>: ${{ number_format($record->shipping?->price ?? 0, 2) }}</dd>
                    <dt>Coupon</dt><dd>: ${{ number_format($record->coupon ?? 0, 2) }}</dd>
                    <dt>Total Amount</dt><dd>: ${{ number_format($record->total_amount ?? 0, 2) }}</dd>
                    <dt>Payment Method</dt><dd>: {{ $record->payment_method_label ?? ucfirst(str_replace('_',' ',$record->payment_method ?? '-')) }}</dd>
                    <dt>Payment Status</dt><dd>: {{ $record->payment_status_label ?? ucfirst($record->payment_status ?? '-') }}</dd>
                </dl>
            </div>

            <div class="col">
                <h2>SHIPPING INFORMATION</h2>
                <dl>
                    <dt>Full Name</dt><dd>: {{ $record->user?->name ?? trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?: '-' }}</dd>
                    <dt>Email</dt><dd>: {{ $record->user?->email ?? $record->email ?? '-' }}</dd>
                    <dt>Phone No.</dt><dd>: {{ $record->phone ?? '-' }}</dd>
                    <dt>Address</dt><dd>: {{ trim(($record->address1 ?? '') . ' ' . ($record->address2 ?? '')) ?: '-' }}</dd>
                    <dt>Shipping Type</dt><dd>: {{ $record->shipping?->type ?? '-' }}</dd>
                    <dt>Country</dt><dd>: {{ $record->country ?? '-' }}</dd>
                    <dt>Post Code</dt><dd>: {{ $record->post_code ?? '-' }}</dd>
                </dl>
            </div>
        </div>
    </div>
</body>
</html>
