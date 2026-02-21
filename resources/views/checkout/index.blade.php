@extends('layouts.admin')

@section('content')

<style>
/* Background for checkout page */
.checkout-page {
    background-color: #f5f6fa;
    min-height: 100vh;
    padding: 50px 0;
}

/* Card styling */
.checkout-card {
    border-radius: 16px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    background-color: #fff;
    padding: 30px;
}

/* Titles */
.checkout-card h4 {
    font-weight: 600;
    margin-bottom: 20px;
}

/* Input styling */
.checkout-card .form-control {
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
}

/* Place order button */
.place-order-btn {
    background-color: #16a34a;
    color: #fff;
    font-weight: 600;
    border-radius: 10px;
    padding: 14px;
    transition: 0.3s;
}

.place-order-btn:hover {
    background-color: #15803d;
}

/* Order summary items */
.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.summary-item strong {
    font-size: 1rem;
}

.total-row {
    font-size: 1.2rem;
    font-weight: 700;
    margin-top: 15px;
}
</style>

<div class="checkout-page">
<div class="container">

<h2 class="mb-5 fw-bold">Checkout</h2>

@if(count($cart) > 0)

<div class="row g-4">

    <!-- Shipping Card -->
    <div class="col-lg-7">
        <div class="checkout-card">
            <h4>Shipping Details</h4>
            <form method="POST" action="#">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" placeholder="John Doe" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone Number</label>
                        <input type="text" class="form-control" placeholder="+255..." required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Delivery Address</label>
                        <textarea class="form-control" rows="3" placeholder="Street, City..." required></textarea>
                    </div>
                </div>

                <button type="submit" class="place-order-btn w-100 mt-4">
                    Place Order
                </button>
            </form>
        </div>
    </div>

    <!-- Order Summary Card -->
    <div class="col-lg-5">
        <div class="checkout-card sticky-top" style="top:20px;">
            <h4>Order Summary</h4>

            @foreach($cart as $item)
                <div class="summary-item">
                    <div>
                        <strong>{{ $item['name'] }}</strong><br>
                        <small class="text-muted">Qty: {{ $item['quantity'] }}</small>
                    </div>
                    <div>
                        {{ number_format($item['price'] * $item['quantity'],0) }} TZS
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between total-row">
                <span>Total</span>
                <span class="text-success">
                    {{ number_format($total,0) }} TZS
                </span>
            </div>
        </div>
    </div>

</div>

@else
<div class="alert alert-info mt-4">
    Your cart is empty.
</div>
@endif

</div>
</div>

@endsection
