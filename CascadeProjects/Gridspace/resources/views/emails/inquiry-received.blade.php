<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Inquiry - Gridspace</title>
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
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }
        .field-value {
            background: white;
            padding: 10px;
            border-radius: 5px;
            border-left: 4px solid #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #777;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏢 New Inquiry Received</h1>
        <p>A potential customer has shown interest in one of your workspace listings</p>
    </div>

    <div class="content">
        <h2>Inquiry Details</h2>
        
        <div class="field">
            <div class="field-label">Listing:</div>
            <div class="field-value">
                <strong>{{ $inquiry->listing->name }}</strong><br>
                <small>Category: {{ $inquiry->listing->category->name }}</small>
            </div>
        </div>

        <div class="field">
            <div class="field-label">Customer Name:</div>
            <div class="field-value">{{ $inquiry->name }}</div>
        </div>

        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">{{ $inquiry->email }}</div>
        </div>

        <div class="field">
            <div class="field-label">Phone:</div>
            <div class="field-value">{{ $inquiry->phone }}</div>
        </div>

        <div class="field">
            <div class="field-label">Message:</div>
            <div class="field-value">{{ nl2br($inquiry->message) }}</div>
        </div>

        @if($inquiry->newsletter_opt_in)
            <div class="field">
                <div class="field-label">Newsletter:</div>
                <div class="field-value">✅ Customer opted in to receive updates</div>
            </div>
        @endif

        <div class="field">
            <div class="field-label">IP Address:</div>
            <div class="field-value">{{ $inquiry->ip_address }}</div>
        </div>

        <div class="field">
            <div class="field-label">Received:</div>
            <div class="field-value">{{ $inquiry->created_at->format('M j, Y \a\t g:i A') }}</div>
        </div>
    </div>

    <div class="footer">
        <p>This inquiry was sent through the Gridspace platform.</p>
        <p>Please respond to the customer promptly to convert this lead.</p>
        <a href="{{ config('app.url') }}" class="btn">View in Gridspace Admin</a>
    </div>
</body>
</html>
