<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Verification Code</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;">

  <table align="center" cellpadding="0" cellspacing="0" style="max-width: 500px; width: 100%; background: #fff; border-radius: 8px; padding: 30px; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
    <tr>
      <td style="font-size: 18px; font-weight: bold; color: #001844; padding-bottom: 15px;">
        Help us protect your account
      </td>
    </tr>
    <tr>
      <td style="font-size: 14px; color: #444; padding-bottom: 20px;">
        Before you finish creating your account, we need to verify your identity. Enter the following code on the verification page:
      </td>
    </tr>
    <tr>
      <td style="font-size: 28px; font-weight: bold; background: #f2f2f2; padding: 15px; border-radius: 6px; letter-spacing: 3px;">
        {{ $code }}
      </td>
    </tr>
    <tr>
      <td style="font-size: 12px; color: #666; padding-top: 20px;">
        Your verification code expires in 60 minutes.
      </td>
    </tr>
  </table>

</body>
</html>
