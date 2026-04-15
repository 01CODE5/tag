<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>{{ $payload['subject'] ?? 'Barangay Request Update' }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
  <p>Hello {{ $payload['name'] ?? 'Resident' }},</p>

  <p>{{ $payload['message'] ?? '' }}</p>

  <p>
    <strong>Reference:</strong> {{ $payload['ref'] ?? '' }}<br />
    <strong>Purpose:</strong> {{ $payload['purpose'] ?? '' }}<br />
    <strong>Reason:</strong> {{ $payload['reason'] ?? '' }}<br />
    <strong>Date:</strong> {{ $payload['date'] ?? '' }}
  </p>

  <p>Thank you.<br />Barangay Admin</p>
</body>
</html>
