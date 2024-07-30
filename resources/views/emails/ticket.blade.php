<!DOCTYPE html>
<html>
<head>
    <title>Your Event Ticket</title>
</head>
<body>
    <h1>Thank you for your purchase!</h1>
    <p>Here are the details of your ticket:</p>
    <ul>
        <li><strong>Event:</strong> {{ $ticket->event->event }}</li>
        <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($ticket->event->date)->format('d M Y') }}</li>
        <li><strong>Venue:</strong> {{ $ticket->event->venue }}</li>
    </ul>
    <p>Please find your ticket attached to this email.</p>
</body>
</html>
