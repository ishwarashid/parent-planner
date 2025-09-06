<!DOCTYPE html>
<html>

<head>
    <title>Profile Approved</title>
</head>

<body>
    <h2>Congratulations, {{ $professional->user->name }}!</h2>
    <p>We're pleased to inform you that your profile for "{{ $professional->business_name }}" has been approved and is
        now live on our platform.</p>
    <p>If you have not already, you can subscribe to a plan to ensure your profile is listed and visible to potential
        clients.</p>
    <p>You can view your dashboard here: <a href="{{ route('professional.professional.dashboard') }}">Go to Dashboard</a></p>
    <p>Thank you for joining our community!</p>
</body>

</html>
