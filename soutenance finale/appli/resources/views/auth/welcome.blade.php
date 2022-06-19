<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laravel</title>
    <link rel="stylesheet" href="css/welcome.css">
</head>

<body>
    <h1>RELEVELANCE</h1>

    <h2>Choose your status</h2>

    <h3>Who do you want to register as?</h3>


    <a href="/expert/login">
        <div class="bloc left">
            <img src="img/expert.png" alt="expert image">
            <div>As Expert</div>
        </div>
    </a>

    <a href="/admin/login">
        <div class="bloc right">
            <img src="img/admin.png" alt="admin image">
            <div>As Administrator</div>
        </div>
    </a>

    @php
       // Artisan::call('db:wipe');
       // App\CampaignLaunch::getInstance();
    @endphp


</body>

</html>
