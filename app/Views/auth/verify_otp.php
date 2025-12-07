<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .container { background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 400px; text-align: center; }
        h2 { color: #333; margin-bottom: 20px; }
        .alert { padding: 10px; margin-bottom: 15px; border-radius: 4px; }
        .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        input[type="text"], input[type="submit"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 4px; box-sizing: border-box; }
        input[type="text"] { border: 1px solid #ccc; font-size: 1.2em; text-align: center; letter-spacing: 5px; }
        input[type="submit"] { background-color: #007bff; color: white; border: none; cursor: pointer; font-weight: bold; }
        input[type="submit"]:hover { background-color: #0056b3; }
        p { margin-top: 20px; font-size: 0.9em; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🔒 Two-Factor Verification</h2>
        
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <p>A 6-digit verification code has been sent to your registered email address. Please check your inbox (and spam folder) and enter the code below.</p>
        
        <form action="<?= base_url('verify-otp') ?>" method="post">
            <?= csrf_field() ?>
            
            <label for="otp_code" style="display: block; text-align: left; margin-bottom: 5px;">Verification Code:</label>
            <input type="text" id="otp_code" name="otp_code" placeholder="Enter the 6-digit code" maxlength="6" required autofocus>
            
            <input type="submit" value="Verify and Log In">
        </form>

        <p>The code is valid for 5 minutes.</p>
    </div>
</body>
</html>