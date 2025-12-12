<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Восстановление пароля</title>
        <style>
            body {
                font-family: 'Nunito', sans-serif;
                background-color: #f2f6f9;
                margin: 0;
                padding: 0;
            }

            .container {
                width: 100%;
                max-width: 600px;
                margin: 0 auto;
                background-color: #fff;
                padding: 20px;
            }

            .header,
            .footer {
                text-align: center;
                padding: 20px 10px;
                background-color: #f2f6f9;
            }

            .header .logo {
                display: table;
            }

            .header .logo img {
                display: table-cell;
                vertical-align: middle;
                width: 60px;
                height: auto;
                margin-right: 10px;
            }

            .header .logo p {
                display: table-cell;
                vertical-align: middle;
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
                text-align: center;
            }

            .content h2 {
                font-family: 'Poppins', sans-serif;
                color: #000;
                font-size: 24px;
            }

            .content p {
                color: #6e7f89;
                line-height: 24px;
            }

            .content a {
                text-decoration: none;
                color: #fff;
            }

            .btn {
                display: inline-block;
                background: #788da3;
                color: #fff;
                padding: 15px 30px;
                text-decoration: none;
                border-radius: 5px;
                font-weight: 500;
                margin-top: 20px;
            }

            .btn:hover {
                background-color: #6e7f89;
            }

            .footer a {
                text-decoration: none;
                color: #788da3;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="header">
                <div class="logo">
                    <img src="{{ $message->embed(public_path('images/logo.jpg')) }}" alt="AgatCeramic Logo" />
                    <p>Agat<span>Ceramic</span></p>
                </div>
            </div>
            <div class="content">
                <h2>Восстановление пароля</h2>
                <p>Здравствуйте, {{ $user->name }}!</p>
                <p>Вы запросили восстановление пароля для вашей учетной записи.</p>
                <p>Для сброса пароля нажмите на кнопку ниже:</p>
                <a href="{{ config('app.crm_url') }}/reset-password?token={{ $token }}&email={{ urlencode($user->email) }}" class="btn">Сбросить пароль</a>
                <p>Если вы не запрашивали восстановление пароля, просто игнорируйте это письмо.</p>
                <p>Ссылка действительна в течение 60 минут.</p>
            </div>
            <div class="footer">
                <p>Если у вас возникли вопросы, свяжитесь с нами:</p>
                <p>Email: <a href="mailto:zakaz@agatceramic.ru">zakaz@agatceramic.ru</a></p>
            </div>
        </div>
    </body>

</html>
