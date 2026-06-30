<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Franchise Inquiry</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 20px; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 8px; }
        h1 { color: #2c3e50; font-size: 22px; border-bottom: 2px solid #3498db; padding-bottom: 10px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #555; }
        .value { margin-top: 4px; }
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #eee; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h1>New Franchise Inquiry</h1>
        <p>A new franchise inquiry has been submitted. Here are the details:</p>

        <div class="field">
            <div class="label">Name</div>
            <div class="value">{{ $franchise->name }}</div>
        </div>

        <div class="field">
            <div class="label">Email</div>
            <div class="value">{{ $franchise->email }}</div>
        </div>

        <div class="field">
            <div class="label">Phone Number</div>
            <div class="value">{{ $franchise->phone_number }}</div>
        </div>

        <div class="field">
            <div class="label">Location</div>
            <div class="value">{{ $franchise->location }}</div>
        </div>

        <div class="field">
            <div class="label">Message</div>
            <div class="value">{{ $franchise->message }}</div>
        </div>

        <div class="footer">
            This email was sent automatically from the Snowy Village website.
        </div>
    </div>
</body>
</html>
