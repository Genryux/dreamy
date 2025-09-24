<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst($role) }} Invitation - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .button:hover {
            background: #0056b3;
        }
        .info-box {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ ucfirst($role) }} Invitation</h1>
        <p>{{ config('app.name') }}</p>
    </div>
    
    <div class="content">
        <h2>Hello {{ $user->first_name }} {{ $user->last_name }}!</h2>
        
        <p>You have been invited to join <strong>{{ config('app.name') }}</strong> as a <strong>{{ ucfirst($role) }}</strong>.</p>
        
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
            <p>As a teacher, you will be able to:</p>
            <ul>
                <li>Manage your assigned sections and classes</li>
                <li>Record student grades and attendance</li>
                <li>View student information and progress</li>
                <li>Access teaching resources and materials</li>
            </ul>
        @elseif($role === 'head_teacher')
            <p>As a head teacher, you will be able to:</p>
            <ul>
                <li>Create and manage sections</li>
                <li>Assign subjects to sections</li>
                <li>Assign students to sections</li>
                <li>Manage teacher assignments</li>
                <li>View section reports and analytics</li>
            </ul>
        @elseif($role === 'registrar')
            <p>As a registrar, you will be able to:</p>
            <ul>
                <li>Manage student enrollment and records</li>
                <li>Process applications and admissions</li>
                <li>Generate reports and transcripts</li>
                <li>Manage academic terms and schedules</li>
            </ul>
        @endif
        
        <p><strong>Important:</strong> This invitation will expire in 7 days. Please complete your registration as soon as possible.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('user.register', $token) }}" class="button">Complete Registration</a>
        </div>
        
        <p>If the button doesn't work, you can copy and paste this link into your browser:</p>
        <p style="word-break: break-all; background: #f0f0f0; padding: 10px; border-radius: 5px;">
            {{ route('user.register', $token) }}
        </p>
        
        <p>If you have any questions or need assistance, please contact your administrator.</p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from {{ config('app.name') }}. Please do not reply to this email.</p>
        <p>If you did not expect this invitation, you can safely ignore this email.</p>
    </div>
</body>
</html>
