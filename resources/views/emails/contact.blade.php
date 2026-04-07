<!doctype html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Contact Enquiry | Abhishek Jha</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            font-family: 'Inter', sans-serif;
            color: #333333;
        }

        a {
            color: #0f9d58;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline !important;
        }

        table {
            border-collapse: collapse;
        }

        .container {
            max-width: 650px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #000000;
            padding: 25px 0;
            text-align: center;
        }

        .header img {
            width: 150px;
            height: auto;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #0f9d58;
            margin: 20px 0 10px 0;
            text-align: center;
        }

        .details {
            font-size: 16px;
            color: #555555;
            line-height: 26px;
            margin: 10px 0;
            padding-left: 10px;
        }

        .details strong {
            color: #0f9d58;
            width: 80px;
            display: inline-block;
        }

        .section {
            border-top: 1px solid #e0e0e0;
            padding-top: 18px;
            margin-top: 18px;
        }

        .cta-button {
            display: inline-block;
            margin: 25px auto 30px auto;
            padding: 12px 26px;
            background-color: #0f9d58;
            color: #ffffff !important;
            border-radius: 6px;
            font-weight: 600;
            text-decoration: none;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .cta-button:hover {
            background-color: #0b7a44;
        }

        .footer {
            background-color: #000000;
            text-align: center;
            padding: 20px;
            font-size: 13px;
            color: #f8f8f8;
            border-top: 1px solid #e0e0e0;
        }

        @media only screen and (max-width: 600px) {
            h1 {
                font-size: 20px;
            }

            .details {
                font-size: 15px;
                line-height: 24px;
            }

            .header img {
                width: 130px;
            }

            .container {
                margin: 15px 10px;
            }

            .cta-button {
                padding: 10px 20px;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <table width="100%" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 35px 0;">
                <table class="container" width="100%" cellpadding="0" cellspacing="0">

                    <!-- Header / Logo -->
                    <tr>
                        <td class="header" style="background-color:#080808; text-align:center; padding:25px 0; font-family: 'Inter', sans-serif; font-weight:700; color:#ffffff;">
                            @if(file_exists(public_path('backend/assets/img/abhishek-potfolio_black_logo.webp')))
                                <img src="{{ asset('backend/assets/img/abhishek-potfolio_black_logo.webp') }}" 
                                    alt="Abhishek Jha" 
                                    title="Abhishek Jha" 
                                    style="width:150px; height:auto; display:block; margin:0 auto;">
                            @else
                                <span style="font-size:24px; color:#ffffff;">
                                    Abhishek Jha
                                </span>
                            @endif
                        </td>
                    </tr>

                    <!-- Title -->
                    <tr>
                        <td style="padding: 25px 30px;">
                            <h1>Contact Enquiry</h1>

                            <!-- Contact Details Section -->
                            <div class="section">
                                <p class="details">👤 <strong>Name:</strong> {{ $mailData['name'] ?? 'N/A' }}</p>
                                <p class="details">📧 <strong>Email:</strong> {{ $mailData['email'] ?? 'N/A' }}</p>
                                @if(!empty($mailData['phone']))
                                    <p class="details">📞 <strong>Phone:</strong> {{ $mailData['phone'] }}</p>
                                @endif
                                <p class="details">📝 <strong>Subject:</strong> {{ $mailData['subject'] ?? 'N/A' }}</p>
                                <p class="details">💬 <strong>Message:</strong> {{ $mailData['message'] ?? 'N/A' }}</p>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center;">
                                <a href="{{ route('frontend.portfolio') }}" class="cta-button">Visit Portfolio</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td class="footer">
                            <b>
                                <a href="{{ route('frontend.home') }}" style="color:#0f9d58;">
                                    Abhipriya AgroCare PVT. LTD.
                                </a>
                                All rights reserved.
                            </b>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
