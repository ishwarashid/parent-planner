<!DOCTYPE html>
<html>

<head>
    <title>Profile Update</title>
</head>

<body>
    <h2>Hello, {{ $professional->user->name }},</h2>
    <p>Thank you for submitting your profile for "{{ $professional->business_name }}".</p>
    <p>After a review, we have determined that your application in its current state does not meet our platform's
        guidelines. This is often due to incomplete information or services offered that do not align with our
        categories.</p>
    <p>You are welcome to update your profile information and resubmit it for another review. Please log in to your
        dashboard to make changes.</p>
    <p>If you have any questions, please contact our support team.</p>
</body>

</html>
