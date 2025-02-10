<?php
    $pageTitle = "Наші послуги";
    require "assets/header.php";
    require "assets/intro-short.php";
?>

<!-- SECTION -->
<div class="section gray">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title">Для оформлення замовлень</div>
        </div>
        <div class="inner_section gray">
            <div class="section_item">
                <div data-aos="fade-up" data-aos-duration="1000">
                    <p class="section_content">Для оформлення замовлень при собі необхідно мати: документ, що посвідчує особу (паспорт громадянина України); документ, що підтверджує право власності на об'єкт нерухомого майна (за наявності); запит нотаріуса у випадку необхідності (напр., вступ в спадщину).</p>
                </div>
            </div>
            <div data-aos="fade-up" data-aos-duration="1000"><img src="img/services.jpg" width="370" height="250" alt=""></div>
        </div>
    </div>
</div>

<!-- PERSON -->
<div class="section">
    <div class="container">
        <div data-aos="fade-up" data-aos-duration="1000">
            <div class="section_title title_center">Для отримання інформації</div>
        </div>
        <div class="inner_section">
            <a href="individual.php" class="card_link" data-aos="fade-up" data-aos-duration="1000">
                <div class="card card_person">
                    <div class="card-circle circle-person">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div class="card_title">Я фізична особа</div>
                    <div class="card_text">Я фізична особа, тобто громадянин, який має права та обов’язки, визначені законом. У мене є ім’я, я можу укладати договори, нести відповідальність за свої дії та захищати свої інтереси в суді. Я відповідаю за зобов’язання всім своїм майном.</div>
                </div>
            </a>

            <a href="legal.php" class="card_link" data-aos="fade-up" data-aos-duration="1000">
                <div class="card card_person">
                    <div class="card-circle circle-person">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div class="card_title">Я юридична особа</div>
                    <div class="card_text">Я юридична особа, тобто маю власне ім’я, печатку, рахунок у банку і можу укладати договори. У мене є права та обов’язки, визначені законом. Я можу виступати в суді, маю окреме майно та відповідаю за свої дії тільки цим майном.</div>
                </div>
            </a>
        </div>
    </div>
</div>

<?php
    require "assets/footer.php";
?>