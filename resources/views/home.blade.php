@extends('layout.master', ['title' => "Dashboard"])

@section('head')
    <link rel="stylesheet" href="vendor/css/dashboard.css?v=0.04" class="template-customizer-core-css" />
@endsection

@section('content')
<h2><b>Welcome to Livewire Products</b></h2>
<div class="max-w-7xl mx-auto p-6">
    <livewire:product-list />
</div>

@endsection

@section('script')
<script>
// let stripe = Stripe("{{ env('STRIPE_KEY') }}");
</script>
@endsection