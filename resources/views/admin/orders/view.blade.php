{{-- resources/views/admin/orders/view.blade.php --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<x-filament-panels::page class="p-4">

    {{-- Reference screenshot: sandbox:/mnt/data/0a0f73ca-fbf9-426e-b532-4c3f7fbce0a4.png --}}

    <div class="card">
        {{-- Header --}}

        <div class="card-header bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
            <h5 class="text-xl font-semibold">Order</h5>

            <a class="btn btn-sm btn-primary shadow-sm ml-auto flex items-center gap-2 genereate-pdf-btn"
                onclick="downloadPDF({{ $record->id }})">
                <i class="fas fa-download fa-sm text-white-50"></i> Generate PDF
            </a>

        </div>


        {{-- Body --}}
        <div class="card-body p-0">

            {{-- Top summary table (one-row like reference) --}}
            <div class="overflow-x-auto -mx-6 pt-4">
                <table class="table table-striped table-hover mb-0 w-full text-sm"
                    style="width: 100% !important; table-layout: fixed !important;">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="pl-2">S.N.</th>
                            <th>Order No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="text-center">Quantity</th>
                            <th>Charge</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-right pr-2">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr class="table-row">
                            <td class="pl-2">1</td>
                            <td class="text-muted">{{ $record->order_number ?? '-' }}</td>
                            <td>{{ $record->user?->name ?? trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?: '-' }}
                            </td>
                            <td class="text-muted">{{ $record->user?->email ?? $record->email ?? '-' }}</td>
                            <td class="text-center">{{ $record->quantity ?? 0 }}</td>
                            <td class="text-muted">${{ number_format($record->shipping?->price ?? 0, 2) }}</td>
                            <td class="font-semibold">${{ number_format($record->total_amount ?? 0, 2) }}</td>
                            <td>
                                @if($record->status === 'new')
                                    <span class="badge badge-primary rounded-full px-3 py-1">new</span>
                                @elseif($record->status === 'process')
                                    <span class="badge badge-warning rounded-full px-3 py-1">process</span>
                                @elseif($record->status === 'delivered')
                                    <span class="badge badge-success rounded-full px-3 py-1">delivered</span>
                                @else
                                    <span
                                        class="badge badge-danger rounded-full px-3 py-1">{{ $record->status ?? 'cancelled' }}</span>
                                @endif
                            </td>

                            <td class="text-right pr-2">
                                <div class="flex items-center justify-end gap-3">

                                    <a href="{{ url('/admin/orders/' . $record->id . '/edit') }}"
                                        class="text-blue-600 hover:text-blue-800 transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                        class="delete-order text-red-600 hover:text-red-800 transition"
                                        data-id="{{ $record->id }}" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>


                        </tr>
                    </tbody>
                </table>
            </div>

            {{-- Two columns: Order info + Shipping info --}}
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Order Information --}}
                    <div class="bg-light p-6" style="background:#ECECEC !important;">
                        <h4 class="text-center pb-4 mb-4" style="text-decoration:underline; font-weight:bold;">ORDER
                            INFORMATION</h4>

                        <table class="table table-borderless text-muted w-full">
                            <tr>
                                <td class="w-44 font-medium text-gray-700">Order Number</td>
                                <td>: {{ $record->order_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Order Date</td>
                                <td>:
                                    {{ $record->created_at?->format('D d M, Y') ? $record->created_at->format('D d M, Y \a\t g:i a') : 'Date not available' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Quantity</td>
                                <td>: {{ $record->quantity ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Order Status</td>
                                <td>: {{ ucfirst($record->status ?? '-') }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Shipping Charge</td>
                                <td>: ${{ number_format($record->shipping?->price ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Coupon</td>
                                <td>: ${{ number_format($record->coupon ?? 0, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Total Amount</td>
                                <td>: <strong>${{ number_format($record->total_amount ?? 0, 2) }}</strong></td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Payment Method</td>
                                <td>:
                                    {{ ($record->payment_method === 'cod' ? 'Cash on Delivery' : ucfirst(str_replace('_', ' ', $record->payment_method ?? '-'))) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Payment Status</td>
                                <td>: {{ ucfirst($record->payment_status ?? '-') }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Shipping Information --}}
                    <div class="bg-light p-6" style="background:#ECECEC !important;">
                        <h4 class="text-center pb-4 mb-4" style="text-decoration:underline; font-weight:bold;">SHIPPING
                            INFORMATION</h4>

                        <table class="table table-borderless text-muted w-full">
                            <tr>
                                <td class="w-44 font-medium text-gray-700">Full Name</td>
                                <td>:
                                    {{ $record->user?->name ?? trim(($record->first_name ?? '') . ' ' . ($record->last_name ?? '')) ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Email</td>
                                <td>: {{ $record->user?->email ?? $record->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Phone No.</td>
                                <td>: {{ $record->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Address</td>
                                <td>:
                                    {{ trim(($record->address1 ?? '') . ($record->address2 ? ', ' . $record->address2 : '')) ?: '-' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Country</td>
                                <td>: {{ $record->country ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="font-medium text-gray-700">Post Code</td>
                                <td>: {{ $record->post_code ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Download JS (stays on same page) --}}
    <script>
        function downloadPDF(id) {
            fetch(`/admin/order/${id}/pdf`)
                .then(r => {
                    if (!r.ok) throw new Error('Network response not ok');
                    return r.blob();
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    // prefer order number if present
                    const orderNumber = `{{ $record->order_number ?? '' }}` || id;
                    a.href = url;
                    a.download = `order-${orderNumber}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    URL.revokeObjectURL(url);
                })
                .catch(e => {
                    console.error(e);
                    alert('Could not download PDF. Check console for details.');
                });
        }
    </script>


    {{-- small CSS tweaks to match reference --}}
    <style>
        .table-responsive {
            overflow-x: auto;
        }

        .bg-light {
            background-color: #ECECEC !important;
        }

        .table-borderless td {
            border: none !important;
            padding: 6px 0 !important;
        }

        .badge {
            font-size: 11px;
            padding: 4px 10px;
        }

        .btn-primary {
            background-color: #5a8dee !important;
            border-color: #5a8dee !important;
            color: #fff !important;
        }

        .card-header.bg-white.border-b.border-gray-200.px-6.py-4.flex.justify-between.items-center {
            display: flex;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .genereate-pdf-btn {
            cursor: pointer;
        }

        .card-header.bg-white.border-b.border-gray-200.px-6.py-4.flex.justify-between.items-center {
            margin-bottom: 10px;
        }
    </style>
</x-filament-panels::page>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.body.addEventListener('click', function (e) {
            const btn = e.target.closest('.delete-order');
            if (!btn) return;

            if (!confirm('Are you sure you want to delete this order?')) return;

            const id = btn.dataset.id;
            fetch(`/admin/orders/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            })
                .then(async res => {
                    if (!res.ok) {
                        const text = await res.text();
                        throw new Error(text || 'Server error');
                    }
                    return res.json().catch(() => ({}));
                })
                .then(data => {
                    // show toast or alert
                    alert(data.message || 'Order deleted successfully');
                    // remove row or redirect
                    const row = btn.closest('tr');
                    if (row) row.remove();
                    // or: window.location.href = '/admin/orders';
                })
                .catch(err => {
                    console.error(err);
                    alert('Could not delete order. Check console.');
                });
        });
    });
</script>