<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .field {
            margin-bottom: 15px;
        }
        .field label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<h2>New Contact Form Submission</h2>

<div class="field">
    <label>Name:</label>
    <p>{{ $details['name'] }}</p>
</div>

<div class="field">
    <label>Email:</label>
    <p>{{ $details['email'] }}</p>
</div>

<div class="field">
    <label>Subject:</label>
    <p>{{ $details['subject'] }}</p>
</div>

<div class="field">
    <label>Message:</label>
    <p>{{ $details['message'] }}</p>
</div>
</body>
</html>
