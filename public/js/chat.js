document.addEventListener('DOMContentLoaded', function() {
    // ჩათის ელემენტების მოძიება
    const chatIcon = document.getElementById('chatIcon');
    const chatModal = document.getElementById('chatModal');
    const chatMinimize = document.getElementById('chatMinimize');
    const chatClose = document.getElementById('chatClose');
    const chatInput = document.getElementById('chatInput');
    const chatSend = document.getElementById('chatSend');
    const chatMessages = document.getElementById('chatMessages');
    
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
        
        // პულსაციის გაუქმება გახსნის შემდეგ
        chatIcon.classList.remove('pulse');
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
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const timeString = `${hours}:${minutes}`;
        
        // ტაიპინგ ინდიკატორი ჯერ წაშალე თუ უკვე არსებობს
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
    function getFormattedTime() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        return `${hours}:${minutes}`;
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
        
        // ტაიპინგ ინდიკატორის გამოჩენა
        setTimeout(() => {
            showTypingIndicator();
            
            // შეტყობინების სიგრძესთან დაკავშირებული დაყოვნება
            const delay = Math.min(1000 + message.length * 30, 3000);
            
            // ავტომატური პასუხის გამოტანა დაყოვნებით
            setTimeout(() => {
                removeTypingIndicator();
                
                const reply = getAutoReply(message);
                const replyTimeString = getFormattedTime();
                
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
            }, delay);
        }, 500);
    }
    
    // ღილაკებზე ივენთ ლისენერების მიბმა
    chatSend.addEventListener('click', sendMessage);
    
    chatInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });
    
    // ჩატის აიკონის ანიმაცია, თუ ის არ გაიხსნა გარკვეული დროის განმავლობაში
    setTimeout(() => {
        if (!chatModal.classList.contains('open')) {
            chatIcon.classList.add('pulse');
        }
    }, 3000);
});