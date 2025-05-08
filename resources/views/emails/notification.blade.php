<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Уведомление</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .notification {
            background: #e0f7fa;
            border: 1px solid #4dd0e1;
            border-radius: 5px;
            padding: 20px;
            max-width: 400px;
            margin: 50px auto;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            text-align: center;
        }
        .notification h1 {
            color: #00796b;
            margin-bottom: 10px;
        }
        .notification p {
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="notification">
    <h1>{{ $user->email}}</h1>
    <p>{{  $user->name }}</p>
</div>
</body>
</html>






