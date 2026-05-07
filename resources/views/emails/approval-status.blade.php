<!DOCTYPE html>
<html>
<head>
    <title>Approval Update</title>
</head>
<body style="font-family: Arial; background:#f4f4f4; padding:20px;">

    <div style="background:#fff; padding:20px; border-radius:8px;">
        <h2>Approval Request Update</h2>

        <p><strong>Title:</strong> {{ $approvalRequest->title }}</p>
        <p><strong>Status:</strong> {{ ucfirst($approvalRequest->status) }}</p>

        <hr>

        <p>Thank you,<br>Process Approval System</p>
    </div>

</body>
</html>