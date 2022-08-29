<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title></title>
    <style>
        h1, h2, h3, h4 {
            text-align: center;
            font-family: Helvetica, Arial, sans-serif;
        }

        p {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 15px;
        }

        td {
            padding: 0;
            text-align: center;
            vertical-align: middle;
        }

        .email-form table, tbody {
            display: table;
            border-collapse: collapse;
        }

        .email-form tbody {
            width: 100%;
        }

        .email-form {
            display: table;
            border-collapse: collapse;
            border-style: hidden;
            box-shadow: 0 0 0 3px #ef8374;
            border-radius: 5px;
        }

        .email-wrapper {
            width: 100%;
            height: 100%;
            background: #ffffff;
        }

        .email-wrapper > tbody {
            margin: auto;
        }

        .email-header {
            display: block;
            padding: 40px;
            font-size: 28px;
        }

        .email-body {
            display: block;
            padding: 30px 20px;
            text-align: start;
        }

        .email-footer {
            display: block;
            padding: 10px;
        }

        .footer-end {
            display: block;
            padding: 30px;
        }

        .header-start {
            display: block;
            padding: 30px;
        }

        .header-section {
            background: #ef8374;
            color: #ffffff;
        }

        .body-section {
            padding: 40px;
        }

        .footer-section {
            margin: auto;
        }
    </style>

    {{ $styles }}
</head>
<body>
<table class="email-wrapper" role="presentation">
    <tbody>

    <tr class="header-start">
        <td>
            <div></div>
        </td>
    </tr>

    <tr>
        <td align="center">
            <table class="email-form">
                <tr>
                    <td>
                        <table width="500" class="header-section">
                            <tr>
                                <td class="email-header">
                                    {{ $header }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="500" class="body-section">
                            <tr>
                                <td class="email-body">
                                    {{ $slot }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="500" class="footer-section">
                            <tr>
                                <td class="email-footer">
                                    {{ $footer }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>

    <tr class="footer-end">
        <td>
            <div></div>
        </td>
    </tr>

    </tbody>
</table>
</body>
</html>
