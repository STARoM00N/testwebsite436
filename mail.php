<?php
session_start();
if (!isset($_SESSION['userid'])) {
    header("Location: signin.php");
    exit;
}
?>

<link rel="stylesheet" href="page/fds.css">

<body onload="showContent('startup')">

    <?php
        // Verify session user ID
        if (!isset($_SESSION['userid'])) {
            header("Location: signin.php");
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
            
            // Display user data
            if ($userData) {
                echo "Welcome, " . htmlspecialchars($userData['Username']);
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
                        <!-- Messages will load here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-button" onclick="openPopup()">+</div>

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
            contentDisplay.innerHTML = ''; 

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
                                <!-- Messages will load here -->
                            </div>
                        </div>
                    `;
                    setTimeout(fetchMessageContent, 0); 
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
                    messagesContainer.innerHTML = data;
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
                    message.style.display = "";
                } else {
                    message.style.display = "none";
                }
            });
        }

        function showMailContent(mail_id) {
            fetch(`asset/get_mail_content.php?id=${mail_id}`)
                .then(response => response.text())
                .then(data => {
                    const contentDisplay = document.getElementById('content-display');
                    contentDisplay.innerHTML = data;
                })
                .catch(error => console.error('Error fetching mail content:', error));
        }

        document.getElementById("emailForm").addEventListener("submit", function(event) {
            event.preventDefault();

            const formData = new FormData(this);

            fetch('asset/send_email.php', {  
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                alert(data);
                closePopup();
                document.getElementById("emailForm").reset();
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>

<?php include_once("asset/footer.php"); ?>
