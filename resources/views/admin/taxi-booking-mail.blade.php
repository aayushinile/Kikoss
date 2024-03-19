<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Taxi Booking Confirmation with Kikos Tours Oahu</title>
    <style>

    </style>
</head>

<body>

    <body>
        <div class="email-template" style="background: #EAEDF7; padding: 10px;">
            <table align="center" cellpadding="0" cellspacing="0" width="600"
                style="background: #ffffff;font-family:Calibri, sans-serif; margin: 0 auto; background-size: 100%; ">
                <tr>
                    <td
                        style="font-family:tahoma, geneva, sans-serif; padding:10px;background:url({{ assets('assets/admin-images/email-head.png') }}), #fff ; padding: 10px; background-size:100%; background-position:bottom center; background-repeat: no-repeat;">
                        <table align="left" width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td
                                        style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px; padding: 10px;">
                                        <a href="{{ URL::to('/') }}" title="{{ config('constant.siteTitle') }}">
                                            <img alt="" src="{{ assets('assets/admin-images/logo.svg') }}"
                                                height="80">
                                        </a>
                                    </td>
                                    <td
                                        style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px;background:rgb(61 161 227 / 8%); padding: 10px; width: 50%; text-align: right;">
                                        <a style="padding: 15px 30px;display: inline-block;background: #3da1e3;border-radius: 89px;color: #fff;white-space: nowrap;text-decoration: none;font-weight: 600;font-size: 13px;box-shadow: 0 0 30px hsl(203.86deg 74.77% 56.47% / 29%);"
                                            href="https://fareharbor.com/embeds/book/kikostoursoahu/items/?amp;u=e3e7d395-4925-40cf-a890-309f15f685d9&amp;from-ssl=yes&amp;ga=UA-197809685-1,1871970958.1701157046;&amp;ga4t=G-GK3MKD1BQY,__;&amp;g4=yes&amp;cp=no&amp;csp=no&amp;back=https://kikostoursoahu.com/&amp;language=en-gb&amp;full-items=yes"
                                            target="_blank">BOOK NOW</a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="font-family:tahoma, geneva, sans-serif; padding:10px;background:url({{ assets('assets/admin-images/email-bg.png') }}), #fff ; padding: 10px; background-size:100%; background-position:bottom center; background-repeat: no-repeat;"
                        colspan="2">
                        <table align="left" width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px;"><b>Dear
                                                {{ $mailData['name'] }},</b></p>
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                            Your Taxi Booking Confirmation with Kikos</p>
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                            We hope this message finds you well and ready for a smooth and convenient
                                            ride with Kikos Tours Oahu. We're thrilled to inform you that your taxi
                                            booking
                                            has been successfully confirmed!</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px; text-align: justify;">
                                            Here are the details of your upcoming ride:</p>
                                        <ul style="padding:0 0 0 20px; margin: 0; list-style-position:outside ;">
                                            <li
                                                style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                                Booking Reference Number: {{ $mailData['booking_id'] }}</li>
                                            <li
                                                style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                                Pickup Location: {{ $mailData['pickup_address'] }}</li>
                                            <li
                                                style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                                Drop-off Location: {{ $mailData['drop_address'] }}</li>

                                            <li
                                                style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                                Date and Time: {{ $mailData['date_time'] }}</li>
                                            <li
                                                style="color:#1F191C;font-size:16px;line-height: 20px;text-align: justify;">
                                                Driver Details: {{ $mailData['driver_details'] }}</li>
                                        </ul>
                                    </td>
                                </tr>

                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px; text-align: justify;">
                                            Your comfort and safety are our top priorities, and we assure you that our
                                            experienced driver will ensure a pleasant journey for you.</p>
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px; text-align: justify;">
                                            If you have any specific requirements or need to make changes to your
                                            booking, please reach out to our customer support team at
                                            <a style="color: #0563C1;text-decoration:underline"
                                            href="mailto:klinekristi@hotmail.com">klinekristi@hotmail.com</a>
                                            or call us at (808)206-2205 as soon as possible.</p>
                                        <p style="color:#1F191C;font-size:16px;line-height: 20px; text-align: justify;">
                                            We appreciate your trust in Kikos Tours Oahu for your transportation needs. As
                                            always, our goal is to provide you with a reliable and convenient service.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p
                                            style="color:#1F191C;font-size:16px;line-height: 20px; font-weight: bold; margin:0; padding:0;  text-align: justify;">
                                            Thank you for choosing Kikos Tours Oahu! We look forward to serving you.</p>
                                        <p
                                            style="color:#1F191C;font-size:16px;line-height: 20px; font-weight: bold; margin:0; padding:0;  text-align: justify;">
                                            Safe travels!</p>
                                        <p
                                            style="color:#1F191C;font-size:16px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            Kikos Tours Oahu</p>
                                        <p
                                            style="color:#1F191C;font-size:16px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            klinekristi@hotmail.com</p>
                                        <p
                                            style="color:#1F191C;font-size:16px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            (808)206-2205</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <table align="center" cellpadding="0" cellspacing="0" width="600"
                style="background: #3da1e3;font-family:Calibri, sans-serif; margin: 0 auto; ">
                <tr>
                    <td
                        style="font-family:tahoma, geneva, sans-serif; padding:20px 0;background-size:100%; background-position:bottom center; background-repeat: no-repeat;">
                        <table align="left" width="100%" cellpadding="0" cellspacing="0">
                            <tbody>
                                <tr>
                                    <td
                                        style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px; padding: 10px;">
                                        <div class=""
                                            style="color:#fff;font-size:16px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            Copyright © 2024 Kikos Tours Oahu - All Rights Reserved.</div>
                                    </td>
                                    <td
                                        style="font-family:tahoma, geneva, sans-serif;color:#29054a;font-size:12px; padding:10px;background:rgb(61 161 227 / 8%); padding: 10px; width:30%; text-align: right;">
                                        <table align="right" cellpadding="0" cellspacing="0">
                                            <tbody>
                                                <tr>
                                                    <td valign="top" width="32" style="vertical-align: top;">
                                                        <a href="https://www.facebook.com/kikostoursoahu/"
                                                            style="text-decoration: none;" rel="external"
                                                            target="_blank">
                                                            <img width="32" alt="Facebook"
                                                                src="{{ assets('assets/admin-images/fb.png') }}" />
                                                        </a>
                                                    </td>
                                                    <td valign="top" width="8"
                                                        style="padding: 0px 8px 0 0; vertical-align: top; font-size: 1px;">
                                                    </td>


                                                    <td valign="top" width="18" style="vertical-align: top;">
                                                        <a href="https://www.instagram.com/kikostoursoahu/"
                                                            style="text-decoration: none;" rel="external"
                                                            target="_blank">
                                                            <img width="32" alt="Instagram"
                                                                src="{{ assets('assets/admin-images/Instagram.png') }}" />
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>

</html>
