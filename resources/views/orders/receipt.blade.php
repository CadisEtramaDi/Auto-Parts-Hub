<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Receipt #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
        }
        .receipt-header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .order-details {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
        }
        .totals {
            text-align: right;
            margin-top: 20px;
        }
        .totals table {
            width: auto;
            margin-left: auto;
        }
        .totals td {
            padding: 5px 20px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 11px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-header">
        <h1>Auto Parts Hub</h1>
        <p>Your Trusted Auto Parts Store</p>
        <p>Order Receipt #{{ $order->id }}</p>
        <p>Date: {{ $order->created_at->format('M d, Y h:i A') }}</p>
    </div>

    <div class="customer-info">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> {{ $order->name }}</p>
        <p><strong>Phone:</strong> {{ $order->phone }}</p>
        <p><strong>Preferred Pickup:</strong> {{ $order->pickup_date->format('M d, Y') }} at {{ date('g:i A', strtotime($order->pickup_time)) }}</p>
        @if($order->special_requests)
        <p><strong>Special Instructions:</strong> {{ $order->special_requests }}</p>
        @endif
        <p><strong>Placed By:</strong> {{ $order->user->name }}</p>
    </div>

    <div class="order-details">
        <h3>Order Items</h3>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th style="text-align: right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td style="text-align: right">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="totals">
        <table>
            <tr>
                <td><strong>Subtotal:</strong></td>
                <td>${{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount > 0)
            <tr>
                <td><strong>Discount:</strong></td>
                <td>-${{ number_format($order->discount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td><strong>Tax:</strong></td>
                <td>${{ number_format($order->tax, 2) }}</td>
            </tr>
            <tr style="font-size: 14px;">
                <td><strong>Total:</strong></td>
                <td><strong>${{ number_format($order->total, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Thank you for shopping at Auto Parts Hub!</p>
        <p>For any questions, please contact us at support@autopartshub.com</p>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer;">
            Print Receipt
        </button>
    </div>
</body>
</html> 