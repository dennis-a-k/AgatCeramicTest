<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Новый заказ</title>
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
                background-color: #788da3;
                color: #fff;
            }

            .header h1 {
                font-family: 'Poppins', sans-serif;
                margin: 0;
                font-size: 24px;
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
                font-size: 20px;
            }

            .info-item {
                margin: 10px 0;
                color: #6e7f89;
            }

            .items-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
            }

            .items-table th,
            .items-table td {
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
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Новый заказ на сайте AgatCeramic</h1>
            </div>
            <div class="content">
                <div class="order-info">
                    <h2>Заказ №{{ $order->order }}</h2>
                    <div class="info-item"><strong>Имя:</strong> {{ $order->name }}</div>
                    <div class="info-item"><strong>Email:</strong> {{ $order->email }}</div>
                    <div class="info-item"><strong>Телефон:</strong> {{ $order->phone }}</div>
                    <div class="info-item"><strong>Адрес:</strong> {{ $order->address }}</div>
                    @if ($order->comment)
                        <div class="info-item"><strong>Комментарий:</strong> {{ $order->comment }}</div>
                    @endif
                </div>
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
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_article }}</td>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ number_format($item->price, 2, ',', ' ') }} ₽</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ $item->product_unit }}</td>
                                <td>{{ number_format($item->subtotal, 2, ',', ' ') }} ₽</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="total">
                    Итого: {{ number_format($order->total_amount, 2, ',', ' ') }} ₽
                </div>
            </div>
        </div>
    </body>

</html>
