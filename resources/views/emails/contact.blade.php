<!DOCTYPE html>
<html>
<head>
    <title>Contact Form Submission</title>
</head>
<body>
    <h2>New Contact Form Submission</h2>
    
    <p><strong>Name:</strong> {{ $name }}</p>
    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Subject:</strong> {{ $subject }}</p>
    
    <h3>Message:</h3>
    <p>{{ $messageContent }}</p>
</body>
</html>