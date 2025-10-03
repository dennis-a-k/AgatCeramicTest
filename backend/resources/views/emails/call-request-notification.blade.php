<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Новая заявка перезвонить клиенту</title>
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
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <h1>Новая заявка перезвонить клиенту на сайте AgatCeramic</h1>
            </div>
            <div class="content">
                <div class="order-info">
                    <h2>Заявка №{{ $callRequest->id }}</h2>
                    <div class="info-item"><strong>Имя:</strong> {{ $callRequest->name }}</div>
                    <div class="info-item"><strong>Телефон:</strong> {{ $callRequest->phone }}</div>
                    <div class="info-item"><strong>Дата:</strong> {{ $callRequest->created_at->format('d.m.Y H:i') }}</div>
                </div>
            </div>
        </div>
    </body>

</html>
