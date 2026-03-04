<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daily Task Summary</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; padding: 20px; color: #333;">

    <table align="center" cellpadding="0" cellspacing="0" style="max-width: 600px; width: 100%; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <!-- Header -->
        <tr>
            <td style="background: #1e3a8a; padding: 30px; text-align: center;">
                <h1 style="color: #fff; margin: 0; font-size: 24px;">New Tasks for You</h1>
                <p style="color: #bfdbfe; margin-top: 10px; font-size: 14px;">Daily summary from MiniJobz</p>
            </td>
        </tr>

        <!-- Content -->
        <tr>
            <td style="padding: 30px;">
                <p style="margin-bottom: 20px;">Hello {{ $user->first_name }},</p>
                <p style="margin-bottom: 30px;">We found some new tasks in the categories you track that might interest you:</p>

                @foreach($tasksByCategory as $categoryName => $tasks)
                    <h2 style="font-size: 18px; color: #1e3a8a; border-bottom: 2px solid #e5e7eb; padding-bottom: 8px; margin-bottom: 15px;">
                        {{ $categoryName }}
                    </h2>
                    
                    @foreach($tasks as $task)
                        <div style="margin-bottom: 25px; padding: 15px; border: 1px solid #f3f4f6; border-radius: 8px; background: #fafafa;">
                            <h3 style="margin: 0 0 10px 0; font-size: 16px;">
                                <a href="{{ route('tasks.show', $task->id) }}" style="color: #2563eb; text-decoration: none; font-weight: bold;">
                                    {{ $task->title }}
                                </a>
                            </h3>
                            <p style="margin: 5px 0; font-size: 14px; color: #666;">
                                <strong>Price:</strong> £{{ $task->price }} | 
                                <strong>Location:</strong> {{ $task->location }}
                            </p>
                            <p style="margin: 10px 0 0 0; font-size: 14px; line-height: 1.5; color: #4b5563;">
                                {{ Str::limit($task->description, 120) }}
                            </p>
                        </div>
                    @endforeach
                @endforeach

                <div style="text-align: center; margin-top: 40px;">
                    <a href="{{ route('tasks') }}" style="background: #2563eb; color: #fff; padding: 12px 30px; border-radius: 9999px; text-decoration: none; font-weight: bold; display: inline-block;">
                        Browse All Tasks
                    </a>
                </div>
            </td>
        </tr>

        <!-- Footer -->
        <tr>
            <td style="background: #f9fafb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; border-top: 1px solid #e5e7eb;">
                <p>You received this email because you opted in to task summaries for your tracked categories.</p>
                <p>&copy; {{ date('Y') }} MiniJobz. All rights reserved.</p>
            </td>
        </tr>
    </table>

</body>
</html>
