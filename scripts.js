// เพิ่มการโต้ตอบ เช่น การคลิกเพื่อแสดงข้อมูลเพิ่มเติมในภายหลัง
console.log("หน้าเสนอสมาชิกทีมกำลังทำงาน");

function goToMemberPage(memberPage) {
    window.location.href = memberPage;
}

function goToIndex() {
    window.location.href = 'index.php';
}