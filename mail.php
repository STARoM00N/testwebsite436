<?php
session_start(); // เริ่มต้น session ที่นี่

include_once("asset/header.php");
include_once("asset/nav_login.php");
?>

<link rel="stylesheet" href="page/fds.css">

<body onload="showContent('startup')">

    <?php
        // ตรวจสอบว่า session userid ถูกตั้งค่าแล้วหรือไม่
        if (!isset($_SESSION['userid'])) {
            header("Location: page/signin.php"); // หากยังไม่ได้ล็อกอิน ให้เปลี่ยนเส้นทางไปยังหน้า signin.php
            exit;
        }

        include_once('config/Database.php');
        include_once('class/UserLogin.php');

        $connectDB = new Database();
        $db = $connectDB->getConnection();

        $user = new UserLogin($db);

        if (isset($_SESSION['userid'])) {
            $userid = $_SESSION['userid'];
            $userData = $user->userData($userid);
            
            // แสดงข้อมูลผู้ใช้
            if ($userData) {
                echo "Welcome, " . htmlspecialchars($userData['Username']); // ตัวอย่างการแสดงชื่อผู้ใช้
            } else {
                echo "User not found.";
            }
        }
    ?>
    
    <div class="container">
        <div class="sidebar">
            <ul>
                <li onclick="showContent('message')">
                    <img src="image/mail.png" alt="Message Box"> Message Box
                </li>
                <li onclick="showContent('chat')">
                    <img src="image/chat.png" alt="Chat"> Chat
                </li>
                <li onclick="showContent('document')">
                    <img src="image/file.png" alt="Document"> Document
                </li>
                <li onclick="showContent('draft')">
                    <img src="image/mail.png" alt="Draft Email"> Draft Email
                </li>
            </ul>
        </div>
        <div class="main-content">
            <div id="content-display">
                <div class="message-box">
                    <div class="message-box-header">
                        <h2 class="message-box-title">Message Box</h2>
                        <input type="text" id="search-input" onkeyup="filterMessages()" placeholder="Search by email..." />
                    </div>
                    <div id="messages-container">
                        <!-- เนื้อหาของกล่องข้อความจะแสดงที่นี่ -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ปุ่มสีเขียวที่มุมขวาล่าง -->
    <div class="add-button" onclick="openPopup()">+</div>

    <!-- Popup Form -->
    <div id="popupForm" class="popup-form">
        <div class="popup-content">
            <span class="close-button" onclick="closePopup()">&times;</span>
            <h2>Send Email</h2>
            <form id="emailForm" action="asset/send_email.php" method="POST">
                <label for="to">To:</label>
                <input type="email" id="to" name="to" required>

                <label for="subject">Subject:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="message">Message:</label>
                <textarea id="message" name="message" required></textarea>

                <button type="submit">Send</button>
            </form>
        </div>
    </div>

    <script>
        function showContent(type) {
            const contentDisplay = document.getElementById('content-display');
            contentDisplay.innerHTML = ''; // ล้างเนื้อหาเก่าออกก่อนโหลดเนื้อหาใหม่

            switch (type) {
                case 'startup':
                    contentDisplay.innerHTML = '<img src="image/logo.png" alt="Logo">';
                    break;
                case 'message':
                    contentDisplay.innerHTML = `
                        <div class="message-box">
                            <div class="message-box-header">
                                <h2 class="message-box-title">Message Box</h2>
                                <input type="text" id="search-input" onkeyup="filterMessages()" placeholder="Search by email..." />
                            </div>
                            <div id="messages-container">
                                <!-- เนื้อหาของกล่องข้อความจะแสดงที่นี่ -->
                            </div>
                        </div>
                    `;
                    setTimeout(fetchMessageContent, 0); // รอให้ messages-container โหลดก่อนเรียก fetchMessageContent
                    break;
                case 'chat':
                    contentDisplay.innerHTML = '<h2>Chat Content</h2>';
                    break;
                case 'document':
                    contentDisplay.innerHTML = '<h2>Document Content</h2>';
                    break;
                case 'draft':
                    contentDisplay.innerHTML = '<h2>Draft Email Content</h2>';
                    break;
                default:
                    contentDisplay.innerHTML = '<h2>Welcome to the main content area!</h2>';
            }
        }

        function fetchMessageContent() {
            const messagesContainer = document.getElementById('messages-container');
            
            if (!messagesContainer) {
                console.error("messages-container not found");
                return;
            }

            fetch('asset/fetch_message.php')
                .then(response => response.text())
                .then(data => {
                    console.log("Data received:", data); // ตรวจสอบข้อมูลที่ได้รับใน console
                    messagesContainer.innerHTML = data; // ใส่ข้อมูลที่ดึงมาใน messages-container
                })
                .catch(error => console.error('Error fetching message content:', error));
        }

        function openPopup() {
            document.getElementById('popupForm').style.display = 'flex';
        }

        function closePopup() {
            document.getElementById('popupForm').style.display = 'none';
        }

        function deleteMail(mail_id) {
            if (confirm("Are you sure you want to delete this mail?")) {
                fetch(`asset/delete_mail.php?id=${mail_id}`, { method: 'GET' })
                    .then(response => response.text())
                    .then(data => {
                        if (data === 'success') {
                            alert('Mail deleted successfully');
                            // ลบ mail item ออกจาก DOM โดยไม่ต้องรีเฟรชหน้า
                            const mailItem = document.getElementById(`mail-item-${mail_id}`);
                            if (mailItem) {
                                mailItem.remove();
                            }
                        } else {
                            alert('Failed to delete mail');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        function filterMessages() {
            const input = document.getElementById('search-input');
            const filter = input.value.toLowerCase();
            const messages = document.querySelectorAll('.message-item');

            messages.forEach(message => {
                const email = message.querySelector('.message-content h3').innerText.toLowerCase();
                if (email.includes(filter)) {
                    message.style.display = ""; // แสดงเมลที่ตรงกับการค้นหา
                } else {
                    message.style.display = "none"; // ซ่อนเมลที่ไม่ตรงกับการค้นหา
                }
            });
        }
        function showMailContent(mail_id) {
            fetch(`asset/get_mail_content.php?id=${mail_id}`)
                .then(response => response.text())
                .then(data => {
                    const contentDisplay = document.getElementById('content-display');
                    contentDisplay.innerHTML = data; // แสดงเนื้อหาของอีเมลใน content-display
                })
                .catch(error => console.error('Error fetching mail content:', error));
        }

        document.getElementById("emailForm").addEventListener("submit", function(event) {
            event.preventDefault(); // ป้องกันการส่งฟอร์มแบบปกติ

            // ดึงข้อมูลจากแต่ละช่องกรอก
            const to = document.getElementById("to").value.trim();
            const subject = document.getElementById("subject").value.trim();
            const message = document.getElementById("message").value.trim();

            // ตรวจสอบว่าแต่ละช่องมีการกรอกข้อมูลหรือไม่
            if (!to) {
                alert("Please enter the recipient's email.");
                return;
            }
            if (!subject) {
                alert("Please enter the subject.");
                return;
            }
            if (!message) {
                alert("Please enter the message.");
                return;
            }

            // หากข้อมูลครบแล้ว ส่งฟอร์ม
            const formData = new FormData(this);

            fetch('asset/send_email.php', {  
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.includes("Error")) { // ถ้ามีข้อความ Error
                    alert(data); // แสดงข้อความข้อผิดพลาด
                } else {
                    alert(data); // แสดงข้อความสำเร็จ
                    closePopup(); // ปิด Popup หลังจากส่งข้อความสำเร็จ
                    document.getElementById("emailForm").reset(); // รีเซ็ตฟอร์มหลังจากส่งเสร็จ
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>

</body>

<?php include_once("asset/footer.php"); ?> 
