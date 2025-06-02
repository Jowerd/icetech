document.addEventListener('DOMContentLoaded', function() {
    // ჩათის ელემენტების მოძიება
    const chatIcon = document.getElementById('chatIcon');
    const chatModal = document.getElementById('chatModal');
    const chatMinimize = document.getElementById('chatMinimize');
    const chatClose = document.getElementById('chatClose');
    const chatInput = document.getElementById('chatInput');
    const chatSend = document.getElementById('chatSend');
    const chatMessages = document.getElementById('chatMessages');
    const statusIndicator = document.getElementById('statusIndicator');
    const adminJoinedNotification = document.getElementById('adminJoinedNotification');
    const chatFooter = document.querySelector('.chat-footer');
    
    // ჩათის მდგომარეობა
    let isAdminOnline = false;
    let chatSessionId = generateSessionId();
    let messageQueue = []; // შეტყობინებების რიგი
    let lastCheckTime = Date.now();
    
    // სესიის ID-ის გენერირება
    function generateSessionId() {
        return 'chat_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    // LocalStorage-ში მონაცემების შენახვა/წაკითხვა
    function saveToStorage(key, data) {
        localStorage.setItem(key, JSON.stringify(data));
    }
    
    function getFromStorage(key) {
        const data = localStorage.getItem(key);
        return data ? JSON.parse(data) : null;
    }
    
    // შეტყობინების შენახვა
    function saveMessage(message, type, timestamp = new Date()) {
        const messageData = {
            id: Date.now() + Math.random(),
            sessionId: chatSessionId,
            message: message,
            type: type, // 'user' ან 'admin'
            timestamp: timestamp.toISOString(),
            read: type === 'admin' && !isAdminOnline ? false : true // ბოტის შეტყობინებები მაშინვე წაკითხულად ინიშნება
        };
        
        let messages = getFromStorage('chat_messages') || [];
        messages.push(messageData);
        saveToStorage('chat_messages', messages);
        
        // სესიის ინფორმაციის განახლება
        updateSessionInfo();
        
        return messageData;
    }
    
    // სესიის ინფორმაციის განახლება
    function updateSessionInfo() {
        const sessionData = {
            id: chatSessionId,
            customer_name: 'მომხმარებელი #' + chatSessionId.substr(-4),
            last_activity: new Date().toISOString(),
            status: 'active',
            unread_admin_count: 0
        };
        
        let sessions = getFromStorage('chat_sessions') || [];
        const existingIndex = sessions.findIndex(s => s.id === chatSessionId);
        
        if (existingIndex >= 0) {
            sessions[existingIndex] = { ...sessions[existingIndex], ...sessionData };
        } else {
            sessions.push(sessionData);
        }
        
        saveToStorage('chat_sessions', sessions);
    }
    
    // ადმინის შეტყობინებების შემოწმება
    function checkForAdminMessages() {
        // მხოლოდ ნამდვილი ადმინის შეტყობინებების შემოწმება (არა ბოტისა)
        if (!isAdminOnline) return; // თუ ადმინი ონლაინ არ არის, არ ვამოწმებთ
        
        const messages = getFromStorage('chat_messages') || [];
        const newAdminMessages = messages.filter(msg => 
            msg.sessionId === chatSessionId && 
            msg.type === 'admin' && 
            !msg.read &&
            new Date(msg.timestamp) > new Date(lastCheckTime)
        );
        
        newAdminMessages.forEach(msg => {
            displayAdminMessage(msg.message, new Date(msg.timestamp));
            // მესიჯის როგორც წაკითხულის მონიშვნა
            msg.read = true;
        });
        
        if (newAdminMessages.length > 0) {
            saveToStorage('chat_messages', messages);
            lastCheckTime = Date.now();
        }
    }
    
    // ადმინის სტატუსის შემოწმება
    function checkAdminStatus() {
        const adminStatus = getFromStorage('admin_status');
        if (adminStatus && adminStatus.online !== isAdminOnline) {
            setAdminOnline(adminStatus.online);
        }
        
        // ადმინის შეტყობინებების შემოწმება მხოლოდ თუ ადმინი ონლაინია
        if (isAdminOnline) {
            checkForAdminMessages();
        }
    }
    
    // საწყისი ტაიმსტამპის დაყენება
    const initialMessageTime = document.getElementById('initialMessageTime');
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    initialMessageTime.textContent = `${hours}:${minutes}`;
    
    // ხშირი კითხვების და პასუხების ობიექტი
    const responses = {
        'გამარჯობა': 'გამარჯობა! როგორ შეგვიძლია დაგეხმაროთ? გთხოვთ დაწერეთ თქვენი შეკითხვა.',
        'დახმარება': 'რა სახის დახმარება გჭირდებათ? მზად ვართ დაგეხმაროთ ნებისმიერ საკითხში.',
        'მისამართი': 'ჩვენი ფიზიკური მისამართია: ხაშური, ბორჯომის ქუჩა №2.',
        'საკონტაქტო': 'დაგვიკავშირდით ტელეფონით: 511 555 888 ან ელფოსტით: info@icetech.ge.',
        'ფასები': 'ფასების სანახავად გთხოვთ ეწვიოთ ჩვენს კატალოგს ან დაგვიკავშირდეთ დეტალური ინფორმაციისთვის.',
        'სამუშაო საათები': 'ჩვენ ვმუშაობთ ორშაბათიდან შაბათის ჩათვლით, 10:00-დან 18:00 საათამდე.',
        'მადლობა': 'მადლობას გიხდით! თუ კიდევ რაიმე დაგჭირდებათ, სიამოვნებით დაგეხმარებით.',
        'კი': 'გთხოვთ მიუთითოთ, რაში გჭირდებათ დამატებითი დახმარება.',
        'არა': 'კარგით! თუ მომავალში რამე დაგჭირდებათ, ყოველთვის მზად ვართ დაგეხმაროთ.',
        'პროდუქცია': 'ჩვენი პროდუქციის სრული სია შეგიძლიათ იხილოთ ჩვენს ვებგვერდზე. სურვილის შემთხვევაში, კონკრეტულ კატეგორიებსაც მოგაწვდით.',
        'გადახდა': 'გადახდა შესაძლებელია როგორც ნაღდი ფულით, ასევე საბანკო ბარათით.',
        'მიწოდება': 'მიწოდება ხდება შეკვეთის შემდეგ 2-3 სამუშაო დღეში. დამატებითი ინფორმაცია შეგიძლიათ მიიღოთ ჩვენი ოპერატორისგან.',
        'გარანტია': 'ჩვენს ყველა პროდუქტზე ვრცელდება 1 წლიანი გარანტია, რაც მოიცავს წარმოების დეფექტების დაფარვას.',
        'მიტანის ღირებულება': 'მიტანის ღირებულება დამოკიდებულია მისამართზე. დეტალური ინფორმაციისთვის გთხოვთ დაგვიკავშირდეთ.',
        'როგორ ხდება შეკვეთა': 'შეკვეთა შეგიძლიათ გააფორმოთ ჩვენს ვებგვერდზე ან დაგვიკავშირდეთ პირდაპირ ჩათით/ტელეფონით.',
        'შეიძლება ნახვა ადგილზე?': 'დიახ, შეგიძლიათ გვეწვიოთ მისამართზე: ხაშური, ბორჯომის ქუჩა №2 და დაათვალიეროთ პროდუქცია ადგილზე.',
        'გააქვთ თუ არა რეგიონებში': 'დიახ, პროდუქციის მიწოდება ხორციელდება საქართველოს ყველა რეგიონში.'
    };
    
    // ადმინის ონლაინ სტატუსის დაყენება
    function setAdminOnline(status) {
        isAdminOnline = status;
        const statusText = statusIndicator.querySelector('.status-text');
        const statusDot = statusIndicator.querySelector('.status-dot');
        
        if (isAdminOnline) {
            chatIcon.classList.add('admin-online');
            statusText.textContent = 'ადმინი ონლაინ';
            statusDot.classList.remove('bot-mode');
            statusDot.classList.add('admin-mode');
            chatFooter.classList.add('live-mode');
            chatInput.classList.add('live-mode');
            chatSend.classList.add('live-mode');
            
            showAdminJoinedNotification();
        } else {
            chatIcon.classList.remove('admin-online');
            statusText.textContent = 'ბოტი ონლაინ';
            statusDot.classList.remove('admin-mode');
            statusDot.classList.add('bot-mode');
            chatFooter.classList.remove('live-mode');
            chatInput.classList.remove('live-mode');
            chatSend.classList.remove('live-mode');
            
            hideAdminJoinedNotification();
        }
    }
    
    // ადმინის შემოსვლის შეტყობინების ჩვენება
    function showAdminJoinedNotification() {
        adminJoinedNotification.style.display = 'flex';
        setTimeout(() => {
            adminJoinedNotification.style.display = 'none';
        }, 5000);
    }
    
    // ადმინის შემოსვლის შეტყობინების დამალვა
    function hideAdminJoinedNotification() {
        adminJoinedNotification.style.display = 'none';
    }
    
    // ადმინისგან შეტყობინების ჩვენება
    function displayAdminMessage(message, timestamp) {
        const timeString = formatTime(timestamp);
        
        removeTypingIndicator();
        
        const adminMessageHTML = `
            <div class="message admin-message live-admin">
                <div class="message-content">
                    <p>${message}</p>
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', adminMessageHTML);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // შესაბამისი სიტყვებზე პასუხების გამოტანის ფუნქცია
    function getAutoReply(userMessage) {
        const lowerMessage = userMessage.toLowerCase().trim();
        
        // პირდაპირი თანხვედრის შემოწმება
        if (responses[lowerMessage]) {
            return responses[lowerMessage];
        }
        
        // ნაწილობრივი თანხვედრის შემოწმება
        for (const [keyword, reply] of Object.entries(responses)) {
            if (lowerMessage.includes(keyword.toLowerCase())) {
                return reply;
            }
        }
        
        // საკონტაქტო ინფორმაციასთან დაკავშირებული კითხვები
        if (lowerMessage.includes('ტელეფონი') || lowerMessage.includes('ნომერი') || lowerMessage.includes('დარეკვა')) {
            return 'დაგვიკავშირდით ნომერზე: 511 555 888';
        }
        
        if (lowerMessage.includes('მეილი') || lowerMessage.includes('ელფოსტა') || lowerMessage.includes('ელ-ფოსტა')) {
            return 'ჩვენი ელ-ფოსტა: info@icetech.ge';
        }
        
        // სტანდარტული პასუხი, თუ ვერცერთი თანხვედრა ვერ მოიძებნა
        return 'ბოდიში, ვერ გავიგე თქვენი შეკითხვა. გთხოვთ, დააზუსტოთ ან დაგვიკავშირდით 511 555 888 ნომერზე.';
    }
    
    // ჩათის გახსნა დახურვის და მინიმიზაციის ლოგიკა
    chatIcon.addEventListener('click', function() {
        chatModal.classList.add('open');
        chatModal.classList.remove('minimized');
        const badge = chatIcon.querySelector('.chat-badge');
        if (badge) badge.style.display = 'none';
        setTimeout(() => { chatInput.focus(); }, 300);
        
        chatIcon.classList.remove('pulse');
        checkAdminStatus();
    });
    
    chatClose.addEventListener('click', function() {
        chatModal.classList.remove('open');
        chatModal.classList.remove('minimized');
    });
    
    chatMinimize.addEventListener('click', function() {
        chatModal.classList.toggle('minimized');
    });
    
    // ტაიპინგ ინდიკატორის ფუნქციები
    function showTypingIndicator() {
        const now = new Date();
        const timeString = formatTime(now);
        
        removeTypingIndicator();
        
        const typingHTML = `
            <div class="message admin-message" id="typing-indicator">
                <div class="message-content typing-indicator">
                    <div class="typing-dots">
                        <span class="dot"></span>
                        <span class="dot"></span>
                        <span class="dot"></span>
                    </div>
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', typingHTML);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function removeTypingIndicator() {
        const typingIndicator = document.getElementById('typing-indicator');
        if (typingIndicator) {
            typingIndicator.remove();
        }
    }
    
    // დროის ფორმატის ფუნქცია
    function formatTime(date) {
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
    }
    
    function getFormattedTime() {
        return formatTime(new Date());
    }
    
    // შეტყობინების გაგზავნის ფუნქცია
    function sendMessage() {
        const message = chatInput.value.trim();
        if (message === '') return;
        
        const timeString = getFormattedTime();
        
        // მომხმარებლის შეტყობინების HTML
        const userMessageHTML = `
            <div class="message user-message">
                <div class="message-content">
                    <p>${message}</p>
                    <span class="message-time">${timeString}</span>
                </div>
            </div>
        `;
        chatMessages.insertAdjacentHTML('beforeend', userMessageHTML);
        chatInput.value = '';
        chatMessages.scrollTop = chatMessages.scrollHeight;
        
        // შეტყობინების შენახვა
        saveMessage(message, 'user');
        
        // თუ ადმინი ონლაინია
        if (isAdminOnline) {
            // ტაიპინგ ინდიკატორის ჩვენება
            setTimeout(() => {
                showTypingIndicator();
            }, 1000);
        } else {
            // ბოტის რეჟიმი - ავტომატური პასუხები
            setTimeout(() => {
                showTypingIndicator();
                
                const delay = Math.min(1000 + message.length * 30, 3000);
                
                setTimeout(() => {
                    removeTypingIndicator();
                    
                    const reply = getAutoReply(message);
                    const replyTimeString = getFormattedTime();
                    
                    // ბოტის პასუხის მაშინვე ჩვენება (არ ველოდებით checkForAdminMessages-ს)
                    const replyHTML = `
                        <div class="message admin-message">
                            <div class="message-content">
                                <p>${reply}</p>
                                <span class="message-time">${replyTimeString}</span>
                            </div>
                        </div>
                    `;
                    chatMessages.insertAdjacentHTML('beforeend', replyHTML);
                    chatMessages.scrollTop = chatMessages.scrollHeight;
                    
                    // ბოტის პასუხის შენახვა როგორც უკვე წაკითხული
                    //saveMessage(reply, 'admin');////////////////////////////////////////////
                }, delay);
            }, 500);
        }
    }
    
    // ღილაკებზე ივენთ ლისენერების მიბმა
    chatSend.addEventListener('click', sendMessage);
    
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // ჩტის აიკონის ანიმაცია
    setTimeout(() => {
        if (!chatModal.classList.contains('open')) {
            chatIcon.classList.add('pulse');
        }
    }, 3000);
    
    // პერიოდული შემოწმებები
    setInterval(checkAdminStatus, 2000); // ყოველ 2 წამში
    
    // სესიის ინფორმაციის საწყისი შენახვა
    updateSessionInfo();
    
    // საწყისი ადმინის სტატუსის შემოწმება
    checkAdminStatus();
});