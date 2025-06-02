<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ლაივ ჩათი - Firebase</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
        }
        
        .chat-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #00a4bd, #007a8c);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(0, 164, 189, 0.4);
            transition: all 0.3s ease;
            z-index: 1000;
        }
        
        .chat-icon:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(0, 164, 189, 0.6);
        }
        
        .chat-icon i {
            color: white;
            font-size: 24px;
        }
        
        .chat-modal {
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 380px;
            height: 500px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(20px);
            transition: all 0.3s ease;
            z-index: 999;
            display: flex;
            flex-direction: column;
        }
        
        .chat-modal.open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        
        .chat-header {
            background: linear-gradient(135deg, #00a4bd, #007a8c);
            color: white;
            padding: 15px 20px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .chat-title {
            font-size: 16px;
            font-weight: bold;
        }
        
        .chat-controls {
            display: flex;
            gap: 10px;
        }
        
        .control-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        
        .control-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .control-btn i {
            color: white;
            font-size: 12px;
        }
        
        .chat-status {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #28a745;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        .status-text {
            font-size: 12px;
            color: #666;
        }
        
        .chat-messages {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background: #f8f9fa;
        }
        
        .message {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
        }
        
        .message.user-message {
            align-items: flex-end;
        }
        
        .message.admin-message {
            align-items: flex-start;
        }
        
        .message-content {
            max-width: 80%;
            padding: 12px 16px;
            border-radius: 18px;
            position: relative;
        }
        
        .user-message .message-content {
            background: #00a4bd;
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .admin-message .message-content {
            background: white;
            color: #333;
            border: 1px solid #e9ecef;
            border-bottom-left-radius: 5px;
        }
        
        .message-content p {
            margin: 0;
            line-height: 1.4;
        }
        
        .message-time {
            font-size: 10px;
            opacity: 0.7;
            margin-top: 5px;
            display: block;
        }
        
        .chat-input-container {
            padding: 15px 20px;
            border-top: 1px solid #e9ecef;
            background: white;
            border-radius: 0 0 15px 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .chat-input {
            flex: 1;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            padding: 10px 15px;
            outline: none;
            font-size: 14px;
        }
        
        .chat-input:focus {
            border-color: #00a4bd;
            box-shadow: 0 0 0 0.2rem rgba(0, 164, 189, 0.25);
        }
        
        .send-btn {
            background: #00a4bd;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .send-btn:hover {
            background: #008ca0;
            transform: scale(1.05);
        }
        
        .send-btn i {
            color: white;
            font-size: 14px;
        }
        
        .admin-joined {
            background: #d4edda;
            color: #155724;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 12px;
            margin: 10px 0;
            text-align: center;
            border: 1px solid #c3e6cb;
        }
        
        .connection-status {
            padding: 8px 15px;
            text-align: center;
            font-size: 12px;
            border-radius: 20px;
            margin: 10px 0;
        }
        
        .connection-status.connected {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .connection-status.disconnected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <!-- Chat Icon -->
    <div class="chat-icon" id="chatIcon">
        <i class="bi bi-chat-dots-fill"></i>
    </div>

    <!-- Chat Modal -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <div class="chat-title">ტექნიკური მხარდაჭერა</div>
            <div class="chat-controls">
                <button class="control-btn" id="chatMinimize">
                    <i class="bi bi-dash"></i>
                </button>
                <button class="control-btn" id="chatClose">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        </div>
        
        <div class="chat-status">
            <div class="status-dot" id="statusDot"></div>
            <span class="status-text" id="statusText">დაკავშირება...</span>
        </div>
        
        <div class="chat-messages" id="chatMessages">
            <div class="message admin-message">
                <div class="message-content">
                    <p>გამარჯობა! როგორ შეგვიძლია დაგეხმაროთ?</p>
                    <span class="message-time" id="initialTime"></span>
                </div>
            </div>
        </div>
        
        <div class="chat-input-container">
            <input type="text" class="chat-input" id="chatInput" placeholder="დაწერეთ შეტყობინება...">
            <button class="send-btn" id="sendBtn">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

    <!-- Firebase SDKs -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/9.23.0/firebase-app-compat.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/firebase/9.23.0/firebase-database-compat.min.js"></script>

    <script>
        // Firebase Configuration - აქ ჩაწერეთ თქვენი Firebase config
        const firebaseConfig = {
            apiKey: "YOUR_API_KEY",
            authDomain: "YOUR_PROJECT.firebaseapp.com",
            databaseURL: "https://YOUR_PROJECT-default-rtdb.firebaseio.com/",
            projectId: "YOUR_PROJECT",
            storageBucket: "YOUR_PROJECT.appspot.com",
            messagingSenderId: "123456789",
            appId: "YOUR_APP_ID"
        };

        // Firebase ინიციალიზაცია
        firebase.initializeApp(firebaseConfig);
        const database = firebase.database();
        
        // Chat State
        let chatSessionId = null;
        let isConnected = false;
        let currentUser = {
            id: 'user_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9),
            name: 'მომხმარებელი'
        };
        
        // DOM Elements
        const chatIcon = document.getElementById('chatIcon');
        const chatModal = document.getElementById('chatModal');
        const chatMinimize = document.getElementById('chatMinimize');
        const chatClose = document.getElementById('chatClose');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const chatMessages = document.getElementById('chatMessages');
        const statusText = document.getElementById('statusText');
        const statusDot = document.getElementById('statusDot');
        const initialTime = document.getElementById('initialTime');
        
        // Set initial time
        const now = new Date();
        initialTime.textContent = formatTime(now);
        
        // Connection monitoring
        const connectedRef = database.ref('.info/connected');
        connectedRef.on('value', (snapshot) => {
            isConnected = snapshot.val();
            updateConnectionStatus();
        });
        
        function updateConnectionStatus() {
            if (isConnected) {
                statusText.textContent = 'ონლაინ';
                statusDot.style.background = '#28a745';
                
                // Remove disconnected message if exists
                const disconnectedMsg = document.querySelector('.connection-status.disconnected');
                if (disconnectedMsg) {
                    disconnectedMsg.remove();
                }
            } else {
                statusText.textContent = 'კავშირი გაწყდა';
                statusDot.style.background = '#dc3545';
                
                // Add disconnected message
                const disconnectedMsg = document.createElement('div');
                disconnectedMsg.className = 'connection-status disconnected';
                disconnectedMsg.textContent = 'კავშირი Firebase-თან გაწყდა';
                chatMessages.appendChild(disconnectedMsg);
                scrollToBottom();
            }
        }
        
        // Create or get chat session
        function initializeChat() {
            if (chatSessionId) return;
            
            chatSessionId = 'chat_' + currentUser.id;
            
            // Create session in database
            database.ref(`sessions/${chatSessionId}`).set({
                userId: currentUser.id,
                userName: currentUser.name,
                startTime: firebase.database.ServerValue.TIMESTAMP,
                status: 'active',
                lastActivity: firebase.database.ServerValue.TIMESTAMP
            });
            
            // Listen for new messages
            listenForMessages();
            
            // Listen for admin status
            listenForAdminStatus();
        }
        
        // Listen for messages
        function listenForMessages() {
            const messagesRef = database.ref(`messages/${chatSessionId}`);
            messagesRef.on('child_added', (snapshot) => {
                const message = snapshot.val();
                if (message.senderId !== currentUser.id) {
                    displayMessage(message, false);
                }
            });
        }
        
        // Listen for admin status
        function listenForAdminStatus() {
            const adminRef = database.ref(`admin/status`);
            adminRef.on('value', (snapshot) => {
                const adminStatus = snapshot.val();
                if (adminStatus && adminStatus.online) {
                    if (statusText.textContent !== 'ადმინი ონლაინ') {
                        statusText.textContent = 'ადმინი ონლაინ';
                        statusDot.style.background = '#007bff';
                        showAdminJoinedMessage();
                    }
                } else if (isConnected) {
                    statusText.textContent = 'ბოტი ონლაინ';
                    statusDot.style.background = '#28a745';
                }
            });
        }
        
        // Send message
        function sendMessage() {
            const message = chatInput.value.trim();
            if (!message || !isConnected) return;
            
            const messageData = {
                senderId: currentUser.id,
                senderName: currentUser.name,
                message: message,
                timestamp: firebase.database.ServerValue.TIMESTAMP,
                type: 'user'
            };
            
            // Save to database
            database.ref(`messages/${chatSessionId}`).push(messageData);
            
            // Display message locally
            displayMessage({
                ...messageData,
                timestamp: Date.now()
            }, true);
            
            // Clear input
            chatInput.value = '';
            
            // Update session activity
            updateSessionActivity();
            
            // Send auto-reply if admin not online
            setTimeout(() => {
                sendAutoReply(message);
            }, 1000);
        }
        
        // Display message
        function displayMessage(messageData, isUser) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user-message' : 'admin-message'}`;
            
            const time = formatTime(new Date(messageData.timestamp));
            
            messageDiv.innerHTML = `
                <div class="message-content">
                    <p>${messageData.message}</p>
                    <span class="message-time">${time}</span>
                </div>
            `;
            
            chatMessages.appendChild(messageDiv);
            scrollToBottom();
        }
        
        // Auto-reply system
        function sendAutoReply(userMessage) {
            const adminStatus = database.ref(`admin/status`);
            adminStatus.once('value', (snapshot) => {
                const admin = snapshot.val();
                if (!admin || !admin.online) {
                    const reply = getAutoReply(userMessage);
                    
                    const botMessage = {
                        senderId: 'bot',
                        senderName: 'ბოტი',
                        message: reply,
                        timestamp: firebase.database.ServerValue.TIMESTAMP,
                        type: 'bot'
                    };
                    
                    setTimeout(() => {
                        database.ref(`messages/${chatSessionId}`).push(botMessage);
                    }, 500 + Math.random() * 2000);
                }
            });
        }
        
        // Auto-reply responses
        function getAutoReply(userMessage) {
            const responses = {
                'გამარჯობა': 'გამარჯობა! როგორ შეგვიძლია დაგეხმაროთ?',
                'დახმარება': 'რა სახის დახმარება გჭირდებათ?',
                'მისამართი': 'ჩვენი მისამართია: ხაშური, ბორჯომის ქუჩა №2',
                'ტელეფონი': 'დაგვიკავშირდით: 511 555 888',
                'სამუშაო საათები': 'ვმუშაობთ ორშ-შაბ 10:00-18:00',
                'მადლობა': 'მადლობას გიხდით! სხვა რაიმე დაგჭირდებათ?'
            };
            
            const lowerMessage = userMessage.toLowerCase();
            
            for (const [key, response] of Object.entries(responses)) {
                if (lowerMessage.includes(key.toLowerCase())) {
                    return response;
                }
            }
            
            return 'მადლობა შეკითხვისთვის! ჩვენი ოპერატორი მალე დაგიკავშირდებათ.';
        }
        
        // Update session activity
        function updateSessionActivity() {
            if (chatSessionId) {
                database.ref(`sessions/${chatSessionId}/lastActivity`).set(firebase.database.ServerValue.TIMESTAMP);
            }
        }
        
        // Show admin joined message
        function showAdminJoinedMessage() {
            const adminJoinedDiv = document.createElement('div');
            adminJoinedDiv.className = 'admin-joined';
            adminJoinedDiv.textContent = 'ადმინისტრატორი შემოუერთდა ჩათს';
            chatMessages.appendChild(adminJoinedDiv);
            scrollToBottom();
            
            setTimeout(() => {
                adminJoinedDiv.remove();
            }, 5000);
        }
        
        // Utility functions
        function formatTime(date) {
            const hours = date.getHours().toString().padStart(2, '0');
            const minutes = date.getMinutes().toString().padStart(2, '0');
            return `${hours}:${minutes}`;
        }
        
        function scrollToBottom() {
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Event Listeners
        chatIcon.addEventListener('click', () => {
            chatModal.classList.add('open');
            chatInput.focus();
            initializeChat();
        });
        
        chatClose.addEventListener('click', () => {
            chatModal.classList.remove('open');
        });
        
        chatMinimize.addEventListener('click', () => {
            chatModal.classList.remove('open');
        });
        
        sendBtn.addEventListener('click', sendMessage);
        
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
        
        // Auto-open chat after 3 seconds
        setTimeout(() => {
            if (!chatModal.classList.contains('open')) {
                chatIcon.style.animation = 'pulse 1s infinite';
            }
        }, 3000);
        
        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (chatSessionId) {
                database.ref(`sessions/${chatSessionId}/status`).set('offline');
            }
        });
    </script>
</body>
</html>