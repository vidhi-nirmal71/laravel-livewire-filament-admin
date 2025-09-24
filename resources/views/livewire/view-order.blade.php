@extends('layout.master', ['title' => 'Order Details'])

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Order #{{ $order->id }} Details</h2>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h5>Order Items:</h5>
            <ul class="list-group mb-3">
                @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->product->name ?? 'Product' }} x {{ $item->quantity }}
                        <span>₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <h5>Total: ₹{{ number_format($order->total_amount, 2) }}</h5>
        </div>
    </div>

    <a href="{{ route('profile') }}" class="btn btn-outline-secondary">Back to Orders</a>
</div>
@endsection

@section('script')
@endsection