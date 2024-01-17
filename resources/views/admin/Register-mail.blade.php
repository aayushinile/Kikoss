<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kikos Tours Oahu</title>
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
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px;"><b>Dear
                                                {{ $mailData['name'] }},</b></p>
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                            Greetings and a warm welcome to kikos Tour! We are thrilled to have you on
                                            board as a valued member of our travel community.</p>
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                            Thank you for choosing us as your go-to platform for booking tours and
                                            embarking on exciting journeys. Your trust means the world to us, and we are
                                            committed to making your travel experiences truly exceptional.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px; text-align: justify;">
                                            Here are a few things you can expect from your [Your Tour Portal Name]
                                            membership:</p>
                                        <ul style="padding:0 0 0 20px; margin: 0; list-style-position:outside ;">
                                            <li
                                                style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                                Diverse Selection: Explore a wide range of carefully curated tours,
                                                spanning breathtaking landscapes, cultural wonders, and thrilling
                                                adventures. We strive to provide options that cater to every type of
                                                traveler.</li>
                                            <li
                                                style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                                User-Friendly Platform: Our website is designed with your convenience in
                                                mind. Navigate effortlessly, discover new destinations, and book your
                                                dream tours with just a few clicks.</li>
                                            <li
                                                style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                                Personalized Recommendations: Tailored suggestions based on your
                                                preferences ensure that you find the perfect itinerary for your
                                                interests and travel style.</li>

                                            <li
                                                style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                                Secure Booking: Your security is our priority. Rest assured that all
                                                transactions on our platform are encrypted and secure, providing you
                                                with a worry-free booking experience.</li>
                                            <li
                                                style="color:#1F191C;font-size:13px;line-height: 20px;text-align: justify;">
                                                Exclusive Offers: As a token of appreciation for choosing [Your Tour
                                                Portal Name], be on the lookout for exclusive discounts and special
                                                promotions. We believe in making your travels not only memorable but
                                                also affordable.</li>
                                        </ul>
                                    </td>
                                </tr>

                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px; text-align: justify;">
                                            To get started, simply log in to your account on our website and begin your
                                            journey of discovery. Should you have any questions or need assistance at
                                            any point, our dedicated customer support team is here to help. Feel free to
                                            reach out via email at [your support email] or give us a call at [your
                                            support phone number].</p>
                                        <p style="color:#1F191C;font-size:13px;line-height: 20px; text-align: justify;">
                                            Once again, welcome to the [Your Tour Portal Name] family. We can't wait to
                                            be a part of your travel adventures!</p>
                                    </td>
                                </tr>

                                <tr>
                                    <td valign="top" style="vertical-align: top;">
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: bold; margin:0; padding:0;  text-align: justify;">
                                            Happy travels!</p>
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: bold; margin:0; padding:0;  text-align: justify;">
                                            Best regards,</p>
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            [Your Name]</p>
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            [Your Title/Position]</p>
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            [Your Tour Portal Name]</p>
                                        <p
                                            style="color:#1F191C;font-size:13px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
                                            [Contact Information]</p>
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
                                            style="color:#fff;font-size:13px;line-height: 20px; font-weight: 400; margin:0; padding:0;  text-align: justify;">
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
