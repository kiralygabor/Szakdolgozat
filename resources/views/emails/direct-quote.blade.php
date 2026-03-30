<!DOCTYPE html>
<html lang="{{ $locale ?? app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <title>{{ __('notifications.direct_quote.subject', ['user' => $employer->first_name], $locale) }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f9f9fb; margin: 0; padding: 30px 10px;">
  
  <table align="center" cellpadding="0" cellspacing="0" style="max-width: 500px; width: 100%; background-color: #ffffff; border-radius: 12px; border: 1px solid #e5e7eb; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); text-align: center;">
    
    <!-- Body Content -->
    <tr>
      <td style="padding: 35px 40px; text-align: left;">
        <h1 style="font-size: 22px; font-weight: bold; color: #0f172a; margin: 0 0 15px;">{{ __('notifications.direct_quote.greeting', ['name' => $notifiable->first_name], $locale) }}</h1>
        
        <p style="font-size: 15px; color: #475569; line-height: 1.6; margin: 0 0 25px;">
            {!! __('notifications.direct_quote.line1', [
                'user' => '<strong>' . $employer->first_name . '</strong>',
                'task' => '<strong>' . $task->title . '</strong>'
            ], $locale) !!}<br><br>
            {!! __('notifications.direct_quote.line2', ['price' => '<strong style="color: #2563eb;">€' . $task->price . '</strong>'], $locale) !!}
        </p>

        <!-- The Button -->
        <table align="center" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 25px;">
          <tr>
            <td align="center">
              <a href="{{ $url }}" style="display: inline-block; background-color: #007AFF; color: #ffffff; font-size: 16px; font-weight: bold; text-decoration: none; padding: 15px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                {{ __('notifications.direct_quote.action', [], $locale) }}
              </a>
            </td>
          </tr>
        </table>

        <p style="font-size: 14px; color: #64748b; margin: 0;">
          {{ __('notifications.direct_quote.line3', [], $locale) }}
        </p>
      </td>
    </tr>

    <!-- Footer -->
    <tr>
      <td style="background-color: #f8fafc; padding: 25px 30px; border-top: 1px solid #e2e8f0; text-align: left;">

        <p style="font-size: 13px; color: #94a3b8; margin: 0;">
          &copy; {{ date('Y') }} MiniJobz. {{ __('emails.reset_password.rights', [], $locale) }}
        </p>
      </td>
    </tr>
  </table>

</body>
</html>
