<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>แนะนำตัวเอง</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Chonburi&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="tha.css">
</head>

<body>
<header>
    <h1 style="font-family: 'Chonburi', sans-serif; text-align:center;">Introduce Myself</h1>
</header>

<section id="about">
    <h2 style="text-align:center;">About Me</h2>
    <img src="tata.jpg" alt="รูปภาพของฉัน" class="profile-img">
    <h2 class="styled-header">Karittha Hanrat</h2>

    <p style="background-color: #000080; font-family: 'Chonburi', sans-serif; text-align: center; color: #fff; padding: 20px;">
    Nickname Tha <br>
    Birthday 26 April 2004 <br>
    Born in Phetchaburi, Thailand<br>
    Studied at Phetchaburi Rajabhat University<br>
    Year 3<br>
    Web and Multimedia<br>
    <a href="http://www.it.pbru.ac.th/" target="_blank" style="color: #fff; text-decoration: none;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
    Faculty of Information Technology
</a>
    
</p>

</section>

<center>
    <p class="contact-block">ช่องทางการติดต่อ</p>
</center>

<div class="social-links">
    <a href="https://www.facebook.com/thar.hanrat?mibextid=ZbWKwL" target="_blank" class="facebook">
        Facebook <i class="fab fa-facebook"></i>
    </a>

    <a href="https://www.instagram.com/karitthaz__/?hl=en" target="_blank" class="instagram">
        Instagram <i class="fab fa-instagram"></i>
    </a>
</div>

<p class="phone-number">เบอร์โทร: 098-960-6034</p>
<p class="email-block">G-mail : Karittha.han@mail.pbru.ac.th</p>

<style>
    /* Profile Image Style */
    .profile-img {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 100%;
        border: 5px solid #FFB2D0;
        box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.5);
        display: block;
        margin: 20px auto;
        transition: transform 1s ease;
    }

    .profile-img:hover {
        transform: scale(1.1);
    }

    /* Styled Header */
    .styled-header {
        text-align: center;
        font-size: 30px;
        font-family: 'Chonburi', sans-serif;
        color: #00008B;
        font-weight: bold;
        margin: 20px 0;
    }

    /* Contact Block */
    .contact-block {
        background-color: #488AC7;
        color: white;
        padding: 20px;
        width: 20%;
        margin: 20px auto;
        border-radius: 50px;
    }

    /* Social Links Style */
    .social-links {
        text-align: center;
        margin: 20px 0;
    }

    .social-links a {
        color: #fff;
        text-decoration: none;
        padding: 10px 20px;
        border-radius: 20px;
        font-size: 18px;
        display: inline-block;
        margin: 0 10px;
    }

    .social-links a i {
        margin-left: 10px;
    }

    .facebook {
        background-color: #2554C7;
    }

    .facebook:hover {
        background-color: #365492;
    }

    .instagram {
        background-color: #E1306C;
    }

    .instagram:hover {
        background-color: #C13584;
    }

    /* Phone Number Style */
    .phone-number {
        margin-top: 5px;
        font-size: 20px;
        background-color: #52D017;
        color: white;
        padding: 10px;
        border-radius: 40px;
        text-align: center;
        display: block;
        width: fit-content;
        margin: 10px auto;
    }

    /* Email Block */
    .email-block {
        background-color: #8B0000;
        color: white;
        padding: 20px;
        width: fit-content;
        margin: 20px auto;
        border-radius: 40px;
        font-size: 20px;
    }
</style>

</body>
</html>
