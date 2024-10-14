<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ผู้จัดทำ</title>
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #ffff; /* สีเทาอ่อน */
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #FF9933	; /* สีส้มอ่อน */
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 20px; /* ระยะห่างจากส่วนเนื้อหา */
        }

        nav {
            margin: 0;
            padding: 0;
        }

        nav a {
            text-decoration: none;
            color: #000; /* สีของลิงค์เป็นสีดำ */
            margin: 0 15px;
            font-weight: bold;
        }

        nav a:hover {
            color: #fff; /* เปลี่ยนเป็นสีขาวเมื่อชี้ */
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto; /* แก้ไขให้ตรงกลาง */
            text-align: center;
        }

        h1 {
            color: #111;
            margin-bottom: 30px;
        }

        h3 {
            color: #111;
            margin-bottom: 30px;
        }

        .swiper-container {
            width: 100%;
            height: 350px; /* เพิ่มความสูง */
            padding-top: 50px;
            padding-bottom: 70px; /* เพิ่มพื้นที่สำหรับจุดสไลด์ */
            display: flex;
            flex-direction: column; /* ทำให้ลูกของ swiper-container เรียงตามแนวตั้ง */
            justify-content: center; /* จัดตำแหน่งลูกให้อยู่กลาง */
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .swiper-slide:hover {
            transform: scale(1.05);
        }

        .swiper-slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .author-info {
            margin-top: 20px;
            background-color: #ff8f00; /* พื้นหลังเป็นสีขาว */
            padding: 20px; /* เพิ่มพื้นที่ด้านใน */
            border-radius: 10px; /* มุมกรอบกลม */
            box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* เงาเบา */
        }

        .author-info h2 {
            margin: 10px 0 5px 0;
            color: #fff;
        }

        .author-info p {
            color: #777;
            max-width: 800px;
            margin: 0 auto;
        }

        /* Swiper Navigation Buttons */
        .swiper-button-next,
        .swiper-button-prev {
            color: orange; /* เปลี่ยนสีเป็นสีส้ม */
        }

        /* Swiper Pagination Bullets */
        .swiper-pagination-bullet {
            background: #FFA500; /* สีส้มมาตรฐาน */
            opacity: 1; /* ทำให้จุดสไลด์ชัดเจน */
            width: 8px; /* ลดความกว้าง */
            height: 8px; /* ลดความสูง */
            margin: 0 5px; /* ระยะห่างระหว่างจุด */
        }

        .swiper-pagination-bullet-active {
            background: #FF8C00; /* สีส้มเข้มสำหรับจุดที่ active */
        }

        /* จัดตำแหน่งจุดสไลด์ให้อยู่ด้านล่าง */
        .swiper-pagination {
            position: fixed; /* ทำให้ตำแหน่งคงที่ */
            bottom: 10px; /* ระยะห่างจากด้านล่าง */
            left: 50%; /* จัดกลางตามแนวนอน */
            transform: translateX(-50%); /* ปรับตำแหน่งให้อยู่กลาง */
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .swiper-slide {
                width: 250px;
                height: 250px;
            }
        }

        @media (max-width: 768px) {
            .swiper-slide {
                width: 200px;
                height: 200px;
            }
        }

        @media (max-width: 480px) {
            .swiper-slide {
                width: 150px;
                height: 150px;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="index.html">Home</a> <!-- ลิงค์ไปยังหน้าแรก -->
        </nav>
    </header>

    <div class="container">
        <h1>ผู้จัดทำ</h1>
        <!-- Swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="1.jpg" alt="ผู้จัดทำ 1" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="2.jpg" alt="ผู้จัดทำ 2" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="6.jpg" alt="ผู้จัดทำ 3" loading="lazy">
                </div>
                <div class="swiper-slide">
                    <img src="4.jpg" alt="ผู้จัดทำ 4" loading="lazy">
                </div>
                <!-- สามารถเพิ่มสไลด์เพิ่มเติมได้ที่นี่ -->
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>

        <div class="author-info">
    <h2>นาย ศุภโชค ตูวิเชียร</h2>
    <p style="color: #fff;">เป็นหนึ่งในผู้จัดทำเว็บไซด์นี้ ช่องทางการติดต่อตามนี้เลย</p>
    <h3>
        <a href="https://www.facebook.com/P.PonKung"target="black" style="color: #fff; text-decoration: none;">
            Facebook <i class="fab fa-facebook"></i>
        </a>
    </h3>
    <h3>
        <a href="https://www.instagram.com/supachaok_/?hl=en"target="black" style="color: #fff; text-decoration: none;">
            Instagram <i class="fab fa-instagram"></i>
        </a>
    </h3>
    <h3>
    <p style="color: #fff;">
    E-mail <i class="fas fa-envelope"></i> > <span style="text-transform: lowercase;">supachaok.too@mail.pbru.ac.th</span>
</p>
    </h3>
</div>

    <!-- Swiper JS -->
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
        var swiper = new Swiper('.swiper-container', {
          slidesPerView: 3,
          spaceBetween: 30,
          loop: true,
          pagination: {
            el: '.swiper-pagination',
            clickable: true,
          },
          navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
          },
          breakpoints: {
            1024: {
              slidesPerView: 3,
              spaceBetween: 30,
            },
            768: {
              slidesPerView: 2,
              spaceBetween: 20,
            },
            480: {
              slidesPerView: 1,
              spaceBetween: 10,
            },
          },
        });
    </script>
</body>
</html>
