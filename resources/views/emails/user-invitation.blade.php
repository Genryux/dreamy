<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($role) }} Invitation - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #ffffff;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #1A3165;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(26, 49, 101, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #199BCF 0%, #1A3165 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #199BCF, #C8A165, #199BCF);
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            background: #ffffff;
            padding: 40px 30px;
            color: #333;
        }
        .content h2 {
            color: #1A3165;
            margin-top: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #199BCF 0%, #1A3165 100%);
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 8px;
            margin: 25px 0;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(25, 155, 207, 0.3);
        }
        .button:hover {
            background: linear-gradient(135deg, #1A3165 0%, #199BCF 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(25, 155, 207, 0.4);
        }
        .info-box {
            background: linear-gradient(135deg, rgba(25, 155, 207, 0.1) 0%, rgba(200, 161, 101, 0.1) 100%);
            border-left: 4px solid #199BCF;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-box h3 {
            color: #1A3165;
            margin-top: 0;
            font-size: 18px;
            font-weight: 600;
        }
        .info-box ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .info-box li {
            margin: 8px 0;
            color: #333;
        }
        .info-box strong {
            color: #1A3165;
        }
        .role-features {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid rgba(25, 155, 207, 0.2);
        }
        .role-features ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .role-features li {
            margin: 8px 0;
            color: #333;
        }
        .link-box {
            background: #f0f0f0;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .link-box p {
            word-break: break-all;
            margin: 0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            color: #666;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 25px 30px;
            background: #f8f9fa;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
        }
        .footer p {
            margin: 5px 0;
        }
        .highlight {
            color: #199BCF;
            font-weight: 600;
        }
        .accent {
            color: #C8A165;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>{{ ucfirst($role) }} Invitation</h1>
            <p>{{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->first_name }} {{ $user->last_name }}!</h2>
            
            <p>You have been invited to join <span class="highlight">{{ config('app.name') }}</span> as a <span class="accent">{{ ucfirst($role) }}</span>.</p>
            
            <div class="info-box">
                <h3>Invitation Details:</h3>
                <ul>
                    <li><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</li>
                    <li><strong>Email:</strong> {{ $user->email }}</li>
                    <li><strong>Role:</strong> {{ ucfirst($role) }}</li>
                    <li><strong>Invited by:</strong> {{ $user->invitedBy->first_name ?? 'Administrator' }} {{ $user->invitedBy->last_name ?? '' }}</li>
                </ul>
            </div>
            
            @if($role === 'teacher')
                <div class="role-features">
                    <p><strong>As a teacher, you will be able to:</strong></p>
                    <ul>
                        <li>Manage your assigned sections and classes</li>
                        <li>Evaluate student academic status</li>
                        <li>View student information and progress</li>
                    </ul>
                </div>
            @elseif($role === 'head_teacher')
                <div class="role-features">
                    <p><strong>As a head teacher, you will be able to:</strong></p>
                    <ul>
                        <li>Create and manage sections</li>
                        <li>Assign subjects to sections</li>
                        <li>Assign students to sections</li>
                        <li>Manage teacher assignments</li>
                    </ul>
                </div>
            @elseif($role === 'registrar')
                <div class="role-features">
                    <p><strong>As a registrar, you will be able to:</strong></p>
                    <ul>
                        <li>Manage student enrollment and records</li>
                        <li>Process applications and admissions</li>
                        <li>Generate reports and transcripts</li>
                        <li>Manage academic terms and schedules</li>
                    </ul>
                </div>
            @endif
            
            <p><strong class="accent">Important:</strong> This invitation will expire in 7 days. Please complete your registration as soon as possible.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('user.register', $token) }}" class="button">Complete Registration</a>
            </div>
            
            <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
            <div class="link-box">
                <p>{{ route('user.register', $token) }}</p>
            </div>
            
            <p>If you have any questions or need assistance, please contact your administrator.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</p>
            <p>If you did not expect this invitation, you can safely ignore this email.</p>
        </div>
    </div>
</body>
</html>
