@extends('layout.master', ['title' => 'Thank You'])

@section('content')
<div class="container py-5 text-center">
    <div class="card shadow-sm mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h2 class="mb-3 text-success">Thank You for Your Order!</h2>
            <p class="mb-4">Your order #<strong>{{ $order->id }}</strong> has been placed successfully.</p>
            <h5>Order Summary:</h5>
            <ul class="list-group mb-4">
                @foreach($order->items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->product->name ?? 'Product' }} x {{ $item->quantity }}
                        <span>₹{{ number_format($item->price * $item->quantity, 2) }}</span>
                    </li>
                @endforeach
            </ul>
            <h5 class="mb-4">Total: ₹{{ number_format($order->total_amount, 2) }}</h5>

            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">Shop More</a>
                <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-lg">View Orders</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection