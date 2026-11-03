<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Confirmed</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9f9f9; color: #333; line-height: 1.6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #1A1A1A; color: #ffffff; padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 500; letter-spacing: 1px; }
        .content { padding: 30px 20px; }
        .content h2 { font-size: 20px; margin-top: 0; }
        .order-details { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .order-details th, .order-details td { text-align: left; padding: 10px; border-bottom: 1px solid #eee; font-size: 14px; }
        .order-details th { background-color: #f5f5f5; font-weight: bold; }
        .totals { margin-top: 20px; text-align: right; font-size: 16px; }
        .totals strong { font-size: 18px; color: #000; }
        .footer { background-color: #f5f5f5; text-align: center; padding: 20px; font-size: 12px; color: #777; }
        .btn { display: inline-block; background-color: #1A1A1A; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 25px; font-size: 14px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>G. Collection</h1>
        </div>
        <div class="content">
            <h2>Thank you for your order, {{ $order->customer_name }}!</h2>
            <p>Your order has been confirmed. We're getting it ready for you.</p>
            
            <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
            <p><strong>Payment Method:</strong> {{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</p>

            <table class="order-details">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rs. {{ number_format($item->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <p>Subtotal: Rs. {{ number_format(($order->subtotal_paisa ?? 0) / 100) }}</p>
                <p>Shipping: {{ ($order->shipping_paisa ?? 0) > 0 ? 'Rs. '.number_format($order->shipping_paisa / 100) : 'Free' }}</p>
                <p>Total: <strong>Rs. {{ number_format($order->total) }}</strong></p>
            </div>

            <p style="margin-top: 30px;">If you have any questions, reply to this email or contact us on WhatsApp.</p>
            <div style="text-align: center;">
                <a href="{{ url('/') }}" class="btn">Visit Our Store</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} G. Collection. All rights reserved.<br>
            Karachi, Pakistan
        </div>
    </div>
</body>
</html>