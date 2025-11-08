<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Account Created - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #199BCF 0%, #1A3165 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
            color: #333333;
        }
        .content h2 {
            color: #1A3165;
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            font-weight: 600;
        }
        .content p {
            margin: 15px 0;
            color: #555555;
            font-size: 15px;
        }
        .credentials-box {
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
        }
        .credentials-box h3 {
            color: #1A3165;
            margin-top: 0;
            margin-bottom: 15px;
            font-size: 16px;
            font-weight: 600;
        }
        .credential-row {
            margin: 12px 0;
            display: flex;
            align-items: center;
        }
        .credential-label {
            font-weight: 600;
            color: #555555;
            min-width: 80px;
            font-size: 14px;
        }
        .credential-value {
            color: #1A3165;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-break: break-all;
        }
        .password-value {
            color: #199BCF;
            font-family: 'Courier New', monospace;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .warning-box h3 {
            color: #856404;
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .warning-box h3::before {
            content: "⚠️";
            margin-right: 8px;
            font-size: 18px;
        }
        .warning-box p {
            color: #856404;
            margin: 8px 0;
            font-size: 14px;
            font-weight: 500;
        }
        .warning-box ul {
            margin: 10px 0;
            padding-left: 20px;
            color: #856404;
        }
        .warning-box li {
            margin: 5px 0;
            font-size: 14px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            background: linear-gradient(135deg, #199BCF 0%, #1A3165 100%);
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 15px;
            margin: 8px;
            transition: opacity 0.3s ease;
        }
        .button:hover {
            opacity: 0.9;
        }
        .button-secondary {
            background: #6c757d;
        }
        .footer {
            text-align: center;
            padding: 25px 30px;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 5px 0;
        }
        .info-text {
            color: #6c757d;
            font-size: 14px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Student Account Created</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->first_name }} {{ $user->last_name }}!</h2>
            
            <p>Your student account has been successfully created on <strong>{{ config('app.name') }}</strong>. You can now access your student portal using the credentials below.</p>
            
            <div class="credentials-box">
                <h3>Your Account Credentials</h3>
                <div class="credential-row">
                    <span class="credential-label">Email:</span>
                    <span class="credential-value">{{ $user->email }}</span>
                </div>
                <div class="credential-row">
                    <span class="credential-label">Password:</span>
                    <span class="credential-value password-value">{{ $password }}</span>
                </div>
            </div>

            <div class="warning-box">
                <h3>IMPORTANT SECURITY WARNING</h3>
                <p><strong>You MUST change your password immediately after your first login.</strong></p>
                <ul>
                    <li>This is a temporary password generated by the system</li>
                    <li>For your account security, change it to a strong, unique password</li>
                    <li>Do not share your password with anyone</li>
                    <li>If you suspect any unauthorized access, contact the administrator immediately</li>
                </ul>
            </div>

            <div class="button-container">
                <a href="{{ $loginUrl }}" class="button">Login to Portal</a>
                <a href="{{ $resetPasswordUrl }}" class="button button-secondary">Change Password</a>
            </div>

            <p class="info-text">If you have any questions or need assistance, please contact your school administrator.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message from {{ config('app.name') }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>

