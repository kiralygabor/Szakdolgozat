<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Minijobz Verification Code</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9f9fb; margin: 0; padding: 30px 10px;">
  
  <table align="center" cellpadding="0" cellspacing="0" style="max-width: 500px; width: 100%; background-color: #ffffff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center;">
    
    <!-- Body Content -->
    <tr>
      <td style="padding: 35px 40px; text-align: left;">
        <h1 style="font-size: 22px; font-weight: bold; color: #0f172a; margin: 0 0 15px;">{{ __('emails.verify_code.title') }}</h1>
        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin: 0 0 25px;">
          {{ __('emails.verify_code.hi', ['name' => $user->first_name]) }}<br><br>
          {{ __('emails.verify_code.intro') }}
        </p>

        <!-- The Code -->
        <table align="center" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 25px;">
          <tr>
            <td align="center">
              <div style="display: inline-block; background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 15px 30px;">
                <span style="font-family: inherit; font-size: 34px; font-weight: 800; color: #007AFF; letter-spacing: 5px;">{{ $code }}</span>
              </div>
            </td>
          </tr>
        </table>

        <p style="font-size: 14px; color: #64748b; margin: 0;">
          {{ __('emails.verify_code.expiry') }}
        </p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background-color: #f8fafc; padding: 25px 30px; border-top: 1px solid #e2e8f0; text-align: left;">
        <p style="font-size: 13px; color: #94a3b8; line-height: 1.5; margin: 0 0 12px;">
          {{ __('emails.verify_code.footer_note') }}
        </p>
        <p style="font-size: 13px; color: #94a3b8; margin: 0;">
          &copy; {{ date('Y') }} Minijobz. {{ __('emails.verify_code.rights') }}
        </p>
      </td>
    </tr>
  </table>

</body>
</html>
