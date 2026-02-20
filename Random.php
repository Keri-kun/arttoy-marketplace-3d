<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>วงล้อสุ่มรางวัล</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      text-align: center;
      font-family: sans-serif;
    }
    canvas {
      margin-top: 50px;
    }
    #pointer {
      width: 0;
      height: 0;
      border-left: 20px solid transparent;
      border-right: 20px solid transparent;
      border-bottom: 30px solid #000;
      margin: auto;
      position: relative;
      top: -410px;
      z-index: 10;
    }
    button {
      margin-top: 40px;
      padding: 10px 30px;
      font-size: 18px;
    }
  </style>
</head>
<body>

<h2>🎯 หมุนวงล้อสุ่มรางวัล!</h2>
<div id="pointer"></div>
<canvas id="wheel" width="500" height="500"></canvas>
<button onclick="spin()">หมุน!</button>

<form id="formResult" action="save_result.php" method="POST" style="display: none;">
  <input type="hidden" name="result" id="inputResult">
</form>

<script>
const canvas = document.getElementById('wheel');
const ctx = canvas.getContext('2d');
const inputResult = document.getElementById("inputResult");
const formResult = document.getElementById("formResult");

const prizes = [
  "ทองคำ",
  "โทรศัพท์",
  "บัตรของขวัญ",
  "หูฟัง",
  "เสื้อยืด",
  "แก้วน้ำ",
  "คูปองลดราคา",
  "ขอบคุณที่ร่วมสนุก"
];

const colors = ["#f44336", "#4CAF50", "#2196F3", "#FF9800", "#9C27B0", "#03A9F4", "#FFC107", "#E91E63"];

let angle = 0;
let spinning = false;

function drawWheel() {
  const num = prizes.length;
  const arc = 2 * Math.PI / num;

  for (let i = 0; i < num; i++) {
    const start = angle + i * arc;
    const end = start + arc;

    // Slice
    ctx.beginPath();
    ctx.moveTo(250, 250);
    ctx.arc(250, 250, 200, start, end);
    ctx.fillStyle = colors[i % colors.length];
    ctx.fill();
    ctx.stroke();

    // Text
    ctx.save();
    ctx.translate(250, 250);
    ctx.rotate(start + arc / 2);
    ctx.textAlign = "right";
    ctx.fillStyle = "#fff";
    ctx.font = "16px sans-serif";
    ctx.fillText(prizes[i], 180, 5);
    ctx.restore();
  }
}

function spin() {
  if (spinning) return;
  spinning = true;

  const spinAngle = Math.floor(Math.random() * 360) + 360 * 5;
  const duration = 5000;
  const startTime = performance.now();

  function animate(time) {
    const progress = Math.min((time - startTime) / duration, 1);
    const eased = easeOutCubic(progress);
    const currentDeg = (spinAngle * eased) % 360;
    angle = (currentDeg * Math.PI) / 180;

    ctx.clearRect(0, 0, 500, 500);
    ctx.save();
    ctx.translate(250, 250);
    ctx.rotate(angle);
    ctx.translate(-250, -250);
    drawWheel();
    ctx.restore();

    if (progress < 1) {
      requestAnimationFrame(animate);
    } else {
      const sliceDeg = 360 / prizes.length;
      const finalDeg = (360 - (currentDeg % 360)) % 360;
      const index = Math.floor(finalDeg / sliceDeg);
      const prize = prizes[index];

      Swal.fire({
        title: '🎉 ยินดีด้วย!',
        text: `คุณได้: ${prize}`,
        icon: 'success',
        confirmButtonText: 'โอเค'
      }).then(() => {
        inputResult.value = prize;
        formResult.submit();
        spinning = false;
      });
    }
  }

  requestAnimationFrame(animate);
}

function easeOutCubic(t) {
  return 1 - Math.pow(1 - t, 3);
}

drawWheel();
</script>

</body>
</html>
