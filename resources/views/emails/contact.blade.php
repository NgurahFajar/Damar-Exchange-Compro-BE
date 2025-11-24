<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
        }
        .header {
            background-color: #DC5233;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .value {
            background: white;
            padding: 10px;
            border-radius: 4px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2 style="margin: 0;">New Contact Form Message</h2>
    </div>
    <div class="content">
        <div class="field">
            <p style="font-weight: bold; color: #555;">Name:</p>
            <p class="value">{{ $data['name'] }}</p>
        </div>
        <div class="field">
            <p style="font-weight: bold; color: #555;">Email:</p>
            <p class="value">{{ $data['email'] }}</p>
        </div>
        <div class="field">
            <p style="font-weight: bold; color: #555;">Message:</p>
            <p class="value" style="white-space: pre-wrap;">{{ $data['message'] }}</p>
        </div>
    </div>
</div>
</body>
</html>
