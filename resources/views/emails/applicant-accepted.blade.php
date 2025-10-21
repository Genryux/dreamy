<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status Update</title>
    <style>
        body { 
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif; 
            color: #1A3165; 
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container { 
            max-width: 580px; 
            margin: 0 auto; 
            padding: 32px 16px;
        }
        .card { 
            background: #ffffff;
            border: 1px solid #e5e7eb; 
            border-radius: 8px; 
            padding: 48px 40px;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        }
        .header {
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid #f1f5f9;
        }
        .brand {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }
        .logo {
            width: 32px;
            height: 32px;
            background: #1A3165;
            border-radius: 6px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        .brand-name {
            color: #1A3165;
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }
        h1 { 
            color: #1A3165;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }
        .subtitle {
            color: #64748b;
            font-size: 14px;
            margin: 0;
            font-weight: 400;
        }
        .content {
            margin: 32px 0;
        }
        .greeting {
            font-size: 16px;
            color: #1A3165;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message {
            font-size: 15px;
            line-height: 1.6;
            color: #475569;
            margin-bottom: 32px;
        }
        .cta-section {
            margin: 32px 0;
        }
        .btn { 
            display: inline-block; 
            background: #199BCF; 
            color: #ffffff !important; 
            padding: 12px 24px; 
            border-radius: 6px; 
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            border: none;
            transition: background-color 0.2s ease;
        }
        .btn:hover {
            background: #1580b3;
        }
        .footer {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #f1f5f9;
        }
        .muted { 
            color: #64748b; 
            font-size: 13px; 
            line-height: 1.5;
            margin: 8px 0;
        }
        .link {
            color: #199BCF;
            text-decoration: none;
        }
        .link:hover {
            text-decoration: underline;
        }
        .status-badge {
            display: inline-block;
            background: #f0f9ff;
            color: #199BCF;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h1>{{ $title }}</h1>
                <p class="subtitle">Application Status Update</p>
            </div>
            
            <div class="content">
                <p class="greeting">Hi {{ $applicantName }},</p>
                <p class="message">{!! nl2br(e($bodyText)) !!}</p>
            </div>
            
            <div class="cta-section">
                @if(!empty($loginUrl))
                    <a class="btn" href="{{ $loginUrl }}" target="_blank" rel="noopener">Log in to your account</a>
                @endif
            </div>
            
            <div class="footer">
                @if(!empty($loginUrl))
                    <p class="muted">
                        If the button doesn't work, copy and paste this link into your browser:<br>
                        <a href="{{ $loginUrl }}" class="link">{{ $loginUrl }}</a>
                    </p>
                @endif
                <p class="muted">Thank you for your interest in our institution.</p>
            </div>
        </div>
    </div>
</body>
</html>


