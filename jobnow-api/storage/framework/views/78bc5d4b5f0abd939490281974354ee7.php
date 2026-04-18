<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify your email - JobyNow</title>
    <style>
        body {
            background-color: #050505;
            color: #f1f5f9;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 40px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #111111;
            border: 2px solid #222222;
            padding: 40px;
            box-shadow: 4px 4px 0 0 #222222;
        }
        h1 {
            color: #ffffff;
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 0;
            margin-bottom: 20px;
            border-bottom: 2px solid #222222;
            padding-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 20px;
        }
        .code-box {
            background-color: #050505;
            border: 2px solid #00E5FF;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .code {
            color: #00E5FF;
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            font-family: 'Courier New', Courier, monospace;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #64748b;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>SECURE VERIFICATION</h1>
        <p>Welcome to JobyNow. To complete your registration as a candidate, please use the 6-digit confirmation code below:</p>
        
        <div class="code-box">
            <span class="code"><?php echo e($code); ?></span>
        </div>
        
        <p>Return to the JobyNow platform and enter this code to activate your workspace. This code is valid for immediate application.</p>
        
        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> JobyNow. System Generated.
        </div>
    </div>
</body>
</html>
<?php /**PATH E:\MY_PROJECT\Job-V3\jobnow-api\resources\views/emails/verify-candidat.blade.php ENDPATH**/ ?>