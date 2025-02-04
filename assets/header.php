<!DOCTYPE html>
<html lang="ua">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- FONT -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:ital,wght@0,100..900;1,100..900&family=Zilla+Slab:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link href="https://use.fontawesome.com/releases/v6.7.0/css/all.css" rel="stylesheet">

    <!-- MAP -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <!-- JS -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <title>КП БТІ</title>
</head>
<body>

<!-- REGISTRATION MODAL -->
<div class="modal" id="registrationModal">
    <div class="modal-content">
        <span class="close"><i class="fa-solid fa-xmark"></i></span>
        <div class="modal_title">Реєстрація</div>
        <form id="registrationForm">        
            <div class="form-group">
                <input type="text" name="surname" class="input" placeholder="Прізвище">
            </div>

            <div class="form-group">
                <input type="text" name="name" class="input" placeholder="Ім'я">
            </div>

            <div class="form-group">
                <input type="text" name="patronymic" class="input" placeholder="По батькові">
            </div>

            <div class="form-group">
                <input type="password" name="password" class="input password" placeholder="Пароль">
            </div>
            
            <button type="submit" class="btn">Зареєструватися</button>
        </form>
        <div class="modal_item" id="authorizationAccount">Вже маю акаунт</div>
    </div>
</div>

<!-- AUTHORIZATION MODAL -->
<div class="modal" id="authorizationModal">
    <div class="modal-content">
        <span class="authorizationСlose"><i class="fa-solid fa-xmark"></i></span>
        <div class="modal_title">Авторизація</div>
        <form id="authorizationForm">        
            <div class="form-group">
                <input type="text" name="surname" class="input" placeholder="Прізвище">
            </div>

            <div class="form-group">
                <input type="text" name="name" class="input" placeholder="Ім'я">
            </div>

            <div class="form-group">
                <input type="text" name="patronymic" class="input" placeholder="По батькові">
            </div>

            <div class="form-group">
                <input type="password" name="password" class="input password" placeholder="Пароль">
            </div>
            
            <button type="submit" class="btn">Увійти</button>
        </form>
    </div>
</div>