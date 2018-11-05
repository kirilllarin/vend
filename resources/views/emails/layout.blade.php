<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .wrapper {
            background: #e9ebee;
            height: 100%;
        }

        .content {
            max-width: 600px;
            background: #fff;
            margin: 30px auto;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            color: #345;
        }

        .header,
        .footer {
            text-align: center;
            padding: 30px;
        }

        .avatar {
            border-radius: 20px;
            width: 40px;
            margin: 0 auto 10px auto;
            display: inline-block;
        }

        .text {
            margin: 15px 0;
            text-align: center;
            padding: 30px;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 15px 20px;
            background: #77a4f3;
            border-radius: 3px;
            color: #FFF;
            font-weight: bold;
        }

        .muted {
            color: #99C;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        VEV
    </div>
    <div class="content">
        @yield('content')
    </div>
    <div class="footer">
        Vev
    </div>
</div>
</body>
</html>
