<!-- Bootstrap Icons CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- ჩათის აიქონი (ფიქსირებული პოზიცია) -->
<div id="chatIcon" class="chat-icon">
    <i class="bi bi-chat-dots-fill"></i>
    <span class="chat-badge">1</span>
</div>

<!-- ჩათის მოდალი -->
<div id="chatModal" class="chat-modal">
    <div class="chat-header">
        <h5>ICETECH ბოტი</h5>
        <div class="chat-controls">
            <button id="chatMinimize" class="chat-control-btn">
                <i class="bi bi-dash-lg"></i>
            </button>
            <button id="chatClose" class="chat-control-btn">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>
    <div class="chat-body">
        <div id="chatMessages" class="chat-messages">
            <!-- ადმინისტრატორის მისალმება -->
            <div class="message admin-message">
                <div class="message-content">
                    <p>გამარჯობა! რით შემიძლია დაგეხმაროთ?</p>
                    <span class="message-time" id="initialMessageTime">12:00</span>
                </div>
            </div>
        </div>
    </div>
    <div class="chat-footer">
        <div class="chat-input-container">
            <input type="text" id="chatInput" class="chat-input" placeholder="დაწერეთ შეტყობინება...">
            <button id="chatSend" class="chat-send-btn">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>
</div>