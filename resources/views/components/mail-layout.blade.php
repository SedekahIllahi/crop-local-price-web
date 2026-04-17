<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email | {{ $prefs_composer['title'] }}</title>

    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #38bdf8, #0ea5e9);
            padding: 24px;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: bold;
        }

        .content {
            padding: 32px;
            font-size: 16px;
            line-height: 1.6;
        }

        .btn-container {
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            font-weight: bold;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s ease-in-out;
        }

        .btn-verify {
            background: #38bdf8;
        }

        .btn-verify:hover {
            background: #0ea5e9;
        }

        .btn-reset {
            background: #f77e53;
        }

        .btn-reset:hover {
            background: #e85c36;
        }

        .footer {
            color: #6c757d;
            font-size: 14px;
            text-align: center;
            padding: 16px;
            background: #f8f9fa;
            border-top: 1px solid #e9ecef;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>

    @stack('styles')
</head>

<body>
    <div class="container">
        <div class="email-container">
                {{ $slot }}

            <div class="footer">
                <p>© {{ date('Y') }} {{ $prefs_composer['title'] }}</p>
                <p>{!! $prefs_composer['copyright'] !!} {!! $prefs_composer['credits'] !!}. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

@stack('scripts')

</html>
