<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ადმინპანელი - ლაივ ჩათი</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        
        .admin-header {
            background: linear-gradient(135deg, #1a365d, #2d5a87);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .admin-title {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .status-badge {
            background-color: #28a745;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .status-dot {
            width: 8px;
            height: 8px;
            background-color: white;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 50% { opacity: 1; }
            51%, 100% { opacity: 0.5; }
        }
        
        .main-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            height: calc(100vh - 80px);
            gap: 20px;
            padding: 20px;
        }
        
        .chat-sessions {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 20px;
            overflow-y: auto;
        }
        
        .sessions-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e9ecef;
        }
        
        .session-item {
            padding: 15px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .session-item:hover {
            border-color: #00a4bd;
            background-color: #f8f9fa;
        }
        
        .session-item.active {
            border-color: #00a4bd;
            background-color: #e3f2fd;
        }
        
        .session-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .session-details h6 {
            margin: 0;
            color: #333;
            font-size: 14px;
        }
        
        .session-details p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        
        .new-message-indicator {
            width: 10px;
            height: 10px;
            background-color: #e74c3c;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
            animation: pulse 1s infinite;
        }
        
        .chat-window {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
        }
        
        .chat-window-header {
            padding: 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .customer-info h5 {
            margin: 0;
            color: #333;
        }
        
        .customer-info p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        .chat-actions {
            display: flex;
            gap: 10px;
        }
        
        .action-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: #00a4bd;
            color: white;
        }
        
        .btn-success {
            background-color: #28a745;
            color: white;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        
        .messages-container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            background-color: #f8f9fa;
        }
        
        .admin-message-item {
            display: flex;
            margin-bottom: 15px;
            max-width: 70%;
        }
        
        .admin-message-item.user {
            margin-left: auto;
        }
        
        .admin-message-item.admin {
            margin-right: auto;
        }
        
        .message-bubble {
            padding: 12px 16px;
            border-radius: 15px;
            position: relative;
        }
        
        .admin-message-item.user .message-bubble {
            background-color: #00a4bd;
            color: white;
            border-bottom-right-radius: 5px;
        }
        
        .admin-message-item.admin .message-bubble {
            background-color: #e9ecef;
            color: #333;
            border-bottom-left-radius: 5px;
        }
        
        .message-text {
            margin: 0;
            line-height: 1.4;
            font-size: 14px;
        }
        
        .message-timestamp {
            display: block;
            font-size: 10px;
            margin-top: 5px;
            opacity: 0.7;
            text-align: right;
        }
        
        .admin-input-area {
            padding: 20px;
            border-top: 1px solid #e9ecef;
            background-color: white;
        }
        
        .input-container {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .message-input {
            flex: 1;
            padding: 12px 15px;
            border: 1px solid #dee2e6;
            border-radius: 20px;
            outline: none;
            font-size: 14px;
            resize: vertical;
            min-height: 40px;
            max-height: 120px;
        }
        
        .message-input:focus {
            border-color: #00a4bd;
            box-shadow: 0 0 0 0.2rem rgba(0, 164, 189, 0.25);
        }
        
        .send-button {
            background-color: #00a4bd;
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .send-button:hover {
            background-color: #008ca0;
            transform: scale(1.05);
        }
        
        .send-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .empty-state {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100%;
            color: #666;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .typing-indicator {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            max-width: 70%;
        }
        
        .typing-dots {
            display: flex;
            gap: 3px;
        }
        
        .typing-dots .dot {
            width: 6px;
            height: 6px;
            background-color: #666;
            border-radius: 50%;
            animation: typing 1.4s infinite ease-in-out;
        }
        
        .typing-dots .dot:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots .dot:nth-child(2) { animation-delay: -0.16s; }
        
        @keyframes typing {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .main-container {
                grid-template-columns: 1fr;
                grid-template-rows: 300px 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-header">
        <div class="admin-title">
            <i class="bi bi-chat-square-dots-fill" style="font-size: 24px;"></i>
            <h4>ლაივ ჩათი - ადმინპანელი</h4>
        </div>
        <div class="status-badge">
            <span class="status-dot"></span>
            ონლაინ
        </div>
    </div>

    <div class="main-container">
        <!-- Chat Sessions List -->
        <div class="chat-sessions">
            <div class="sessions-header">
                <i class="bi bi-people-fill"></i>
                <h5>აქტიური ჩათები</h5>
                <span id="sessionCount" style="background: #00a4bd; color: white; padding: 2px 8px; border-radius: 10px; font-size: 12px;">0</span>
            </div>
            
            <div id="sessionsList">
                <!-- Sessions will be loaded here -->
            </div>
        </div>

        <!-- Chat Window -->
        <div class="chat-window">
            <div id="chatWindowContent">
                <div class="empty-state">
                    <i class="bi bi-chat-dots"></i>
                    <h5>აირჩიეთ ჩათი საუბრის დასაწყებად</h5>
                    <p>მარცხნივ აირჩიეთ აქტიური ჩათი რომ დაიწყოთ კომუნიკაცია მომხმარებელთან</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ადმინპანელის JavaScript კოდი
        let currentSessionId = null;
        let sessions = {};
        let adminOnline = true;

        // LocalStorage-ში მონაცემების შენახვა/განახლება
        function saveToStorage(key, data) {
            try {
                localStorage.setItem(key, JSON.stringify(data));
            } catch (e) {
                console.error('Error saving to localStorage:', e);
            }
        }

        function getFromStorage(key) {
            try {
                const data = localStorage.getItem(key);
                return data ? JSON.parse(data) : null;
            } catch (e) {
                console.error('Error reading from localStorage:', e);
                return null;
            }
        }

        // ადმინის სტატუსის დაყენება
        function setAdminStatus(online) {
            adminOnline = online;
            const statusData = {
                online: online,
                timestamp: new Date().toISOString()
            };
            saveToStorage('admin_status', statusData);
        }

        // ადმინის შეტყობინების გაგზავნა მომხმარებლისთვის
        function sendMessageToUser(sessionId, message) {
            const messageData = {
                id: Date.now() + Math.random(),
                sessionId: sessionId,
                message: message,
                type: 'admin',
                timestamp: new Date().toISOString(),
                read: false
            };
            
            // შეტყობინების შენახვა
            let messages = getFromStorage('chat_messages') || [];
            messages.push(messageData);
            saveToStorage('chat_messages', messages);
            
            return messageData;
        }

        // გვერდის ჩატვირთვისას
        document.addEventListener('DOMContentLoaded', function() {
            // ადმინის სტატუსის დაყენება
            setAdminStatus(true);
            
            // სესიების ჩატვირთვა
            loadChatSessions();
            
            // პერიოდული განახლება
            setInterval(loadChatSessions, 2000);
            setInterval(updateCurrentChatMessages, 1000);
        });

        // გვერდის დატოვებისას/დახურვისას
        window.addEventListener('beforeunload', function() {
            setAdminStatus(false);
        });

        // ბრაუზერის დახურვისას
        document.addEventListener('visibilitychange', function() {
            if (document.hidden) {
                setAdminStatus(false);
            } else {
                setAdminStatus(true);
            }
        });

        // ჩათის სესიების ჩატვირთვა localStorage-იდან
        function loadChatSessions() {
            const storedSessions = getFromStorage('chat_sessions') || [];
            const messages = getFromStorage('chat_messages') || [];
            
            // აქტიური სესიების ფილტრაცია (ბოლო 2 საათში აქტიური)
            const twoHoursAgo = new Date(Date.now() - 2 * 60 * 60 * 1000);
            const activeSessions = storedSessions.filter(session => {
                const lastActivity = new Date(session.last_activity);
                return lastActivity > twoHoursAgo && session.status === 'active';
            });

            // თითოეული სესიისთვის უახლესი შეტყობინების პოვნა
            activeSessions.forEach(session => {
                const sessionMessages = messages.filter(msg => msg.sessionId === session.id);
                if (sessionMessages.length > 0) {
                    const lastMessage = sessionMessages[sessionMessages.length - 1];
                    session.last_message = lastMessage.message;
                    session.last_message_time = new Date(lastMessage.timestamp);
                    
                    // წაუკითხავი ადმინის შეტყობინებების რაოდენობა
                    session.unread_admin_count = sessionMessages.filter(msg => 
                        msg.type === 'user' && 
                        !msg.read && 
                        new Date(msg.timestamp) > (session.admin_last_read || new Date(0))
                    ).length;
                } else {
                    session.last_message = 'ახალი ჩათი';
                    session.last_message_time = new Date(session.last_activity);
                    session.unread_admin_count = 0;
                }
            });

            // სესიების დალაგება ბოლო აქტივობის მიხედვით
            activeSessions.sort((a, b) => {
                return new Date(b.last_message_time || b.last_activity) - new Date(a.last_message_time || a.last_activity);
            });

            displaySessions(activeSessions);
        }

        // სესიების ჩვენება
        function displaySessions(sessionsData) {
            const sessionsList = document.getElementById('sessionsList');
            const sessionCount = document.getElementById('sessionCount');
            
            sessionCount.textContent = sessionsData.length;
            sessionsList.innerHTML = '';
            
            if (sessionsData.length === 0) {
                sessionsList.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="bi bi-chat-dots" style="font-size: 32px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>აქტიური ჩათები არ არის</p>
                        <small>მომხმარებლები რომლებიც ბოლო 2 საათში იყვნენ აქტიურნი გამოჩნდებიან აქ</small>
                    </div>
                `;
                return;
            }
            
            sessionsData.forEach(session => {
                const sessionElement = createSessionElement(session);
                sessionsList.appendChild(sessionElement);
                sessions[session.id] = session;
            });
        }

        // სესიის ელემენტის შექმნა
        function createSessionElement(session) {
            const div = document.createElement('div');
            div.className = `session-item ${currentSessionId === session.id ? 'active' : ''}`;
            div.setAttribute('data-session-id', session.id);
            
            const timeString = formatTimestamp(session.last_message_time || new Date(session.last_activity));
            
            div.innerHTML = `
                <div class="session-info">
                    <div class="session-details">
                        <h6>${session.customer_name}</h6>
                        <p>${session.last_message || 'ახალი ჩათი'}</p>
                        <small style="color: #999;">${timeString}</small>
                    </div>
                </div>
                ${session.unread_admin_count > 0 ? '<div class="new-message-indicator"></div>' : ''}
            `;
            
            div.addEventListener('click', () => selectSession(session.id));
            
            return div;
        }

        // სესიის არჩევა
        function selectSession(sessionId) {
            // წინა აქტიური სესიის გამორთვა
            document.querySelectorAll('.session-item').forEach(item => {
                item.classList.remove('active');
            });
            
            // ახალი სესიის გააქტიურება
            const selectedSession = document.querySelector(`[data-session-id="${sessionId}"]`);
            if (selectedSession) {
                selectedSession.classList.add('active');
            }
            
            currentSessionId = sessionId;
            loadChatWindow(sessionId);
            
            // ადმინის ხედვის დროის მონიშვნა
            markAdminRead(sessionId);
        }

        // ადმინის მიერ წაკითხვის მონიშვნა
        function markAdminRead(sessionId) {
            let sessions = getFromStorage('chat_sessions') || [];
            const sessionIndex = sessions.findIndex(s => s.id === sessionId);
            if (sessionIndex >= 0) {
                sessions[sessionIndex].admin_last_read = new Date().toISOString();
                saveToStorage('chat_sessions', sessions);
            }
        }

        // ჩათის ფანჯრის ჩატვირთვა
        function loadChatWindow(sessionId) {
            const session = sessions[sessionId];
            if (!session) return;
            
            const chatWindow = document.getElementById('chatWindowContent');
            chatWindow.innerHTML = `
                <div class="chat-window-header">
                    <div class="customer-info">
                        <h5>${session.customer_name}</h5>
                        <p><i class="bi bi-clock"></i> სესია: ${formatTimestamp(new Date(session.last_activity))}</p>
                    </div>
                    <div class="chat-actions">
                        <button class="action-btn btn-success" onclick="markAsResolved('${sessionId}')">
                            <i class="bi bi-check-circle"></i> გადაწყვეტილი
                        </button>
                        <button class="action-btn btn-danger" onclick="endSession('${sessionId}')">
                            <i class="bi bi-x-circle"></i> დასრულება
                        </button>
                    </div>
                </div>
                
                <div class="messages-container" id="messagesContainer">
                    <!-- Messages will be loaded here -->
                </div>
                
                <div class="admin-input-area">
                    <div class="input-container">
                        <textarea 
                            id="messageInput" 
                            class="message-input" 
                            placeholder="დაწერეთ შეტყობინება..."
                            rows="1"
                        ></textarea>
                        <button class="send-button" onclick="sendAdminMessage()">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </div>
                </div>
            `;
            
            loadMessages(sessionId);
            
            // Auto-resize textarea
            const messageInput = document.getElementById('messageInput');
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = Math.min(this.scrollHeight, 120) + 'px';
            });
            
            // Enter ღილაკით გაგზავნა
            messageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendAdminMessage();
                }
            });
        }

        // შეტყობინებების ჩატვირთვა
        function loadMessages(sessionId) {
            const messages = getFromStorage('chat_messages') || [];
            const sessionMessages = messages.filter(msg => msg.sessionId === sessionId);
            
            // დროის მიხედვით დალაგება
            sessionMessages.sort((a, b) => new Date(a.timestamp) - new Date(b.timestamp));
            
            displayMessages(sessionMessages);
        }

        // შეტყობინებების ჩვენება
        function displayMessages(messages) {
            const container = document.getElementById('messagesContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (messages.length === 0) {
                container.innerHTML = `
                    <div style="text-align: center; padding: 40px; color: #666;">
                        <i class="bi bi-chat-text" style="font-size: 32px; margin-bottom: 15px; opacity: 0.5;"></i>
                        <p>შეტყობინებები არ არის</p>
                    </div>
                `;
                return;
            }
            
            messages.forEach(message => {
                const messageElement = createMessageElement(message);
                container.appendChild(messageElement);
            });
            
            container.scrollTop = container.scrollHeight;
        }

        // შეტყობინების ელემენტის შექმნა
        function createMessageElement(message) {
            const div = document.createElement('div');
            div.className = `admin-message-item ${message.type}`;
            
            const timeString = formatTimestamp(new Date(message.timestamp));
            
            div.innerHTML = `
                <div class="message-bubble">
                    <p class="message-text">${message.message}</p>
                    <span class="message-timestamp">${timeString}</span>
                </div>
            `;
            
            return div;
        }

        // მიმდინარე ჩათის შეტყობინებების განახლება
        function updateCurrentChatMessages() {
            if (currentSessionId) {
                const container = document.getElementById('messagesContainer');
                if (container && !container.innerHTML.includes('შეტყობინებები არ არის')) {
                    const currentHeight = container.scrollHeight;
                    const wasAtBottom = container.scrollTop + container.clientHeight >= currentHeight - 50;
                    
                    loadMessages(currentSessionId);
                    
                    // თუ მომხმარებელი ბოლოში იყო, ავტომატურად ჩამოვასკროლოთ
                    if (wasAtBottom) {
                        setTimeout(() => {
                            container.scrollTop = container.scrollHeight;
                        }, 100);
                    }
                }
            }
        }

        // ადმინის შეტყობინების გაგზავნა
        function sendAdminMessage() {
            const input = document.getElementById('messageInput');
            const message = input.value.trim();
            
            if (!message || !currentSessionId) return;
            
            // შეტყობინების შენახვა
            const messageData = sendMessageToUser(currentSessionId, message);
            
            // შეტყობინების დამატება UI-ში
            const messageElement = createMessageElement(messageData);
            const container = document.getElementById('messagesContainer');
            container.appendChild(messageElement);
            
            // Input-ის გასუფთავება
            input.value = '';
            input.style.height = 'auto';
            
            // სკროლი ბოლოში
            container.scrollTop = container.scrollHeight;
            
            // სესიის განახლება
            updateSessionActivity(currentSessionId);
        }

        // სესიის აქტივობის განახლება
        function updateSessionActivity(sessionId) {
            let sessions = getFromStorage('chat_sessions') || [];
            const sessionIndex = sessions.findIndex(s => s.id === sessionId);
            if (sessionIndex >= 0) {
                sessions[sessionIndex].last_activity = new Date().toISOString();
                saveToStorage('chat_sessions', sessions);
            }
        }

        // დროის ფორმატირება
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'ახლახან';
            if (diff < 3600000) return `${Math.floor(diff / 60000)} წუთის წინ`;
            if (diff < 86400000) return `${Math.floor(diff / 3600000)} საათის წინ`;
            
            const today = new Date();
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);
            
            if (date.toDateString() === today.toDateString()) {
                return date.toLocaleTimeString('ka-GE', {hour: '2-digit', minute: '2-digit'});
            } else if (date.toDateString() === yesterday.toDateString()) {
                return 'გუშინ ' + date.toLocaleTimeString('ka-GE', {hour: '2-digit', minute: '2-digit'});
            } else {
                return date.toLocaleDateString('ka-GE') + ' ' + date.toLocaleTimeString('ka-GE', {hour: '2-digit', minute: '2-digit'});
            }
        }

        // სესიის დასრულების ფუნქციები
        function markAsResolved(sessionId) {
            if (confirm('დარწმუნებული ხართ რომ ეს ჩათი გადაწყვეტილია?')) {
                let sessions = getFromStorage('chat_sessions') || [];
                const sessionIndex = sessions.findIndex(s => s.id === sessionId);
                if (sessionIndex >= 0) {
                    sessions[sessionIndex].status = 'resolved';
                    sessions[sessionIndex].resolved_at = new Date().toISOString();
                    saveToStorage('chat_sessions', sessions);
                    
                    // UI-ის განახლება
                    loadChatSessions();
                    
                    // თუ ეს იყო აქტიური ჩათი, ცარიელი სტეიტის ჩვენება
                    if (currentSessionId === sessionId) {
                        document.getElementById('chatWindowContent').innerHTML = `
                            <div class="empty-state">
                                <i class="bi bi-check-circle" style="color: #28a745;"></i>
                                <h5>ჩათი გადაწყვეტილია</h5>
                                <p>ეს ჩათი მონიშნულია როგორც გადაწყვეტილი</p>
                            </div>
                        `;
                        currentSessionId = null;
                    }
                }
            }
        }

 function endSession(sessionId) {
            if (confirm('დარწმუნებული ხართ რომ გინდათ ჩათის დასრულება?')) {
                let sessions = getFromStorage('chat_sessions') || [];
                const sessionIndex = sessions.findIndex(s => s.id === sessionId);
                if (sessionIndex >= 0) {
                    sessions[sessionIndex].status = 'ended';
                    sessions[sessionIndex].ended_at = new Date().toISOString();
                    saveToStorage('chat_sessions', sessions);
                    
                    // UI-ის განახლება
                    loadChatSessions();
                    
                    // თუ ეს იყო აქტიური ჩათი, ცარიელი სტეიტის ჩვენება
                    if (currentSessionId === sessionId) {
                        document.getElementById('chatWindowContent').innerHTML = `
                            <div class="empty-state">
                                <i class="bi bi-x-circle" style="color: #dc3545;"></i>
                                <h5>ჩათი დასრულებულია</h5>
                                <p>ეს ჩათი დასრულდა ადმინისტრატორის მიერ</p>
                            </div>
                        `;
                        currentSessionId = null;
                    }
                }
            }
        }

        // გვერდის დატოვების ან დახურვის ივენთის გამოჩენა
        window.addEventListener('beforeunload', function(e) {
            // ადმინის სტატუსის განახლება
            setAdminStatus(false);
        });

        // ვისიბილიტის ცვლილების მონიტორინგი
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                setAdminStatus(false);
            } else if (document.visibilityState === 'visible') {
                setAdminStatus(true);
                // გვერდზე დაბრუნებისას სესიების განახლება
                loadChatSessions();
                if (currentSessionId) {
                    loadMessages(currentSessionId);
                }
            }
        });

        // ფოკუსის ივენთები
        window.addEventListener('focus', function() {
            setAdminStatus(true);
            loadChatSessions();
        });

        window.addEventListener('blur', function() {
            setAdminStatus(false);
        });

        // ერრორის ჰენდლინგი localStorage-ისთვის
        function handleStorageError(error) {
            console.error('Storage Error:', error);
            // შეტყობინება მომხმარებლისთვის
            alert('შეცდომა მონაცემების შენახვისას. გთხოვთ განაახლოთ გვერდი.');
        }

        // Storage ივენთის მოსმენა სხვა ტაბებიდან ცვლილებებისთვის
        window.addEventListener('storage', function(e) {
            if (e.key === 'chat_sessions' || e.key === 'chat_messages') {
                loadChatSessions();
                if (currentSessionId) {
                    updateCurrentChatMessages();
                }
            }
        });

        // კლავიატურის შორთკატები
        document.addEventListener('keydown', function(e) {
            // ESC ღილაკით ჩათის დახურვა
            if (e.key === 'Escape' && currentSessionId) {
                currentSessionId = null;
                document.getElementById('chatWindowContent').innerHTML = `
                    <div class="empty-state">
                        <i class="bi bi-chat-dots"></i>
                        <h5>აირჩიეთ ჩათი საუბრის დასაწყებად</h5>
                        <p>მარცხნივ აირჩიეთ აქტიური ჩათი რომ დაიწყოთ კომუნიკაცია მომხმარებელთან</p>
                    </div>
                `;
                document.querySelectorAll('.session-item').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });

        // პროგრესის ბარის დამატება დიდი ფაილების ატვირთვისთვის
        function showLoadingState() {
            const sessionsList = document.getElementById('sessionsList');
            sessionsList.innerHTML = `
                <div style="text-align: center; padding: 40px; color: #666;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p style="margin-top: 15px;">ჩატვირთვა...</p>
                </div>
            `;
        }

        // Debug ფუნქციები განვითარების დროს
        function debugClearStorage() {
            if (confirm('გსურთ ყველა ჩათის მონაცემის წაშლა?')) {
                localStorage.removeItem('chat_sessions');
                localStorage.removeItem('chat_messages');
                localStorage.removeItem('admin_status');
                location.reload();
            }
        }

        // Debug ფუნქცია კონსოლში
        window.debugChat = {
            clearStorage: debugClearStorage,
            getSessions: () => getFromStorage('chat_sessions'),
            getMessages: () => getFromStorage('chat_messages'),
            getAdminStatus: () => getFromStorage('admin_status')
        };

        // საწყისი ინიციალიზაცია
        console.log('ადმინპანელი ჩაიტვირთა წარმატებით');
        console.log('Debug კომანდები: window.debugChat');
    </script>
</body>
</html>