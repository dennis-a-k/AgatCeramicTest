<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Подтверждение заказа</title>
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f2f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
            background-color: #f2f6f9;
        }
        .header .logo {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .header img {
            width: 60px;
            height: auto;
            margin-right: 10px;
        }
        .header p {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.75rem;
            color: #000;
            margin: 0;
        }
        .header span {
            color: #8a8a8a;
        }
        .content {
            padding: 20px;
        }
        .order-info {
            margin-bottom: 20px;
        }
        .order-info h2 {
            font-family: 'Poppins', sans-serif;
            color: #000;
            font-size: 24px;
            text-align: center;
        }
        .info-item {
            margin: 10px 0;
            color: #6e7f89;
        }
        .thank-you-text {
            text-align: center;
            margin: 20px 0;
            line-height: 24px;
            color: #6e7f89;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .items-table th, .items-table td {
            border: 1px solid #ebebeb;
            padding: 10px;
            text-align: left;
        }
        .items-table th {
            background-color: #f2f6f9;
            font-family: 'Poppins', sans-serif;
            color: #000;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            color: #c50101;
            margin-top: 20px;
        }
        .btn_cmpted {
            text-align: center;
            margin: 30px 0;
        }
        .btn_cmpted .shop-btn {
            width: 210px;
            background: #788da3;
            height: 50px;
            line-height: 50px;
            text-align: center;
            display: inline-block;
            color: #fff;
            font-weight: 500;
            border-radius: 5px;
            font-size: 16px;
            text-decoration: none;
        }
        .btn_cmpted .shop-btn:hover {
            background-color: #6e7f89;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="{{ asset('images/logo.phg') }}" alt="AgatCeramic Logo" />
                <p>Agat<span>Ceramic</span></p>
            </div>
        </div>
        <div class="content">
            <div class="order-info">
                <h2>Заказ №{{ $order->order }}</h2>
                <div class="info-item"><strong>Имя:</strong> {{ $order->name }}</div>
                <div class="info-item"><strong>Email:</strong> {{ $order->email }}</div>
                <div class="info-item"><strong>Телефон:</strong> {{ $order->phone }}</div>
                <div class="info-item"><strong>Адрес:</strong> {{ $order->address }}</div>
                @if($order->comment)
                <div class="info-item"><strong>Комментарий:</strong> {{ $order->comment }}</div>
                @endif
            </div>
            <p class="thank-you-text">
                Спасибо за заказ в нашем магазине. В ближайшее время с Вами свяжутся по московскому времени с 10:00 до 18:00.
            </p>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Артикул</th>
                        <th>Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Единица</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product_article }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ number_format($item->price, 2) }} ₽</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ $item->product_unit }}</td>
                        <td>{{ number_format($item->subtotal, 2) }} ₽</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="total">
                Итого: {{ number_format($order->total_amount, 2) }} ₽
            </div>
            <div class="btn_cmpted">
                <a href="{{ config('app.frontend_url') }}" class="shop-btn" title="Продолжить покупки">
                    Продолжить покупки
                </a>
            </div>
        </div>
    </div>
</body>
</html>
