<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <title>{{ __('emails.reset_password.title', [], $locale) }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9f9fb; margin: 0; padding: 30px 10px;">
  
  <table align="center" cellpadding="0" cellspacing="0" style="max-width: 500px; width: 100%; background-color: #ffffff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center;">
    
    <!-- Body Content -->
    <tr>
      <td style="padding: 35px 40px; text-align: left;">
        <h1 style="font-size: 22px; font-weight: bold; color: #0f172a; margin: 0 0 15px;">{{ __('emails.reset_password.title', [], $locale) }}</h1>
        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin: 0 0 25px;">
          {{ __('emails.reset_password.hi', ['name' => $user->first_name], $locale) }}<br><br>
          {{ __('emails.reset_password.intro', [], $locale) }}
        </p>

        <!-- The Button -->
        <table align="center" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 25px;">
          <tr>
            <td align="center">
              <a href="{{ $url }}" style="display: inline-block; background-color: #007AFF; color: #ffffff; font-size: 16px; font-weight: bold; text-decoration: none; padding: 15px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                {{ __('emails.reset_password.action', [], $locale) }}
              </a>
            </td>
          </tr>
        </table>

        <p style="font-size: 14px; color: #64748b; margin: 0;">
          {{ __('emails.reset_password.expiry', ['count' => $count], $locale) }}
        </p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background-color: #f8fafc; padding: 25px 30px; border-top: 1px solid #e2e8f0; text-align: left;">
        <p style="font-size: 13px; color: #94a3b8; line-height: 1.5; margin: 0 0 12px;">
          {{ __('emails.reset_password.footer_note', [], $locale) }}
        </p>
        <p style="font-size: 13px; color: #94a3b8; margin: 0;">
          &copy; {{ date('Y') }} Minijobz. {{ __('emails.reset_password.rights', [], $locale) }}
        </p>
      </td>
    </tr>
  </table>

</body>
</html>
