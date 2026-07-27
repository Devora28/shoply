<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Your Shoply sign-in code</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap');

        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }

        @media only screen and (max-width: 480px) {
            .main-card { padding: 32px 20px !important; }
            .otp-cell { width: 48px !important; height: 60px !important; }
            .otp-digit { font-size: 26px !important; }
            .heading { font-size: 24px !important; }
            .logo-text { font-size: 20px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #fafaf9; font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

<table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #fafaf9;">
    <tr>
        <td align="center" style="padding: 50px 20px 60px;">

            <!-- Container -->
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px;">

                <!-- Logo -->
                <tr>
                    <td align="center" style="padding-bottom: 36px;">
                        <table role="presentation" cellpadding="0" cellspacing="0">
                            <tr>
                                <td width="48" height="48" style="width: 48px; height: 48px; background-color: #0d9488; border-radius: 16px; text-align: center; vertical-align: middle;">
                                    <span style="font-size: 24px; line-height: 1;">&#128722;</span>
                                </td>
                                <td style="padding-left: 14px;">
                                    <span style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 24px; font-weight: 800; color: #1c1917; letter-spacing: -0.5px;" class="logo-text">Shoply</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Hero / Intro -->
                <tr>
                    <td align="center" style="padding-bottom: 28px;">
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="center" style="padding: 0 8px 8px;">
                                    <span style="display: inline-block; width: 64px; height: 64px; background-color: #ccfbf1; border-radius: 50%; text-align: center; vertical-align: middle; line-height: 64px; font-size: 28px;">&#128274;</span>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <h1 style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 800; color: #1c1917; line-height: 1.15; letter-spacing: -0.6px; margin: 0 0 8px;" class="heading">
                                        Sign-in code
                                    </h1>
                                    <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 15px; line-height: 1.6; color: #57534e; margin: 0;">
                                        Enter this code to securely access your Shoply account
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Card: OTP -->
                <tr>
                    <td>
                        <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ffffff; border-radius: 24px; border: 1px solid #e7e5e4; box-shadow: 0 2px 12px rgba(0,0,0,0.03), 0 8px 28px -12px rgba(0,0,0,0.06);" class="main-card">
                            <tr>
                                <td style="padding: 44px 36px 40px;" class="main-card">

                                    <!-- OTP Box -->
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f0fdfa; border-radius: 20px; border: 1.5px solid #ccfbf1;">
                                        <tr>
                                            <td align="center" style="padding: 32px 20px;">

                                                <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #5eead4; margin: 0 0 20px;">
                                                    Your Code
                                                </p>

                                                <!-- Digits Row -->
                                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                                                    <tr>
                                                        @foreach(str_split($otp) as $digit)
                                                            @if(!$loop->first)
                                                                <td width="10" style="width: 10px;">&nbsp;</td>
                                                            @endif
                                                            <td width="62" height="76" style="width: 62px; height: 76px; background-color: #ffffff; border: 2px solid #ccfbf1; border-radius: 16px; text-align: center; vertical-align: middle; box-shadow: 0 4px 12px rgba(13, 148, 136, 0.1);" class="otp-cell">
                                                                <span style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 34px; font-weight: 800; color: #0f766e; line-height: 1;" class="otp-digit">{{ $digit }}</span>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                    <!-- Divider Line -->
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td style="padding: 28px 0 24px;">
                                                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td style="border-top: 1px solid #f5f5f4; height: 1px; line-height: 1px; font-size: 1px;">&nbsp;</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                    <!-- Info Section -->
                                    <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                        <tr>
                                            <td>
                                                <table role="presentation" cellpadding="0" cellspacing="0" width="100%">
                                                    <tr>
                                                        <td width="20" style="width: 20px; vertical-align: top; padding-top: 2px;">
                                                            <span style="font-size: 16px;">&#9200;</span>
                                                        </td>
                                                        <td>
                                                            <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #78716c; margin: 0 0 16px;">
                                                                This code expires in <strong style="color: #1c1917; font-weight: 700;">10 minutes</strong>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="20" style="width: 20px; vertical-align: top; padding-top: 2px;">
                                                            <span style="font-size: 16px;">&#128737;</span>
                                                        </td>
                                                        <td>
                                                            <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.5; color: #78716c; margin: 0;">
                                                                Never share this code. Shoply will never ask for it.
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td align="center" style="padding-top: 36px;">
                        <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 13px; line-height: 1.7; color: #a8a29e; margin: 0 0 6px;">
                            Didn't request this? <span style="color: #78716c;">Just ignore this email.</span>
                        </p>
                        <p style="font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 12px; color: #d6d3d1; margin: 18px 0 0;">
                            &copy; 2024 Shoply. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>

        </td>
    </tr>
</table>

</body>
</html>
