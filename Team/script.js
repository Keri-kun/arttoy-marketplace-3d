// ตัวอย่างฟังก์ชันสำหรับการ scroll smooth ไปยังส่วนต่างๆ ของเว็บไซต์
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
