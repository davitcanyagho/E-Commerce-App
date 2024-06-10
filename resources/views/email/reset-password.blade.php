<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Email</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size:16px;">

    <p>Hello, {{ $formData['user']->name }}</p>

    <h1>Anda harus diminta untuk mengubah kata sandi:</h1>

    <p>Silakan klik tautan di bawah ini untuk mengatur ulang kata sandi.</p>

    <a href="{{ route('front.resetPassword',$formData['token']) }}">Klik Disini</a>

    <p>Terimakasih</p>


</body>
</html>