<!-- Chatbot Trigger Button -->
<div class="chatbot-trigger" onclick="toggleChat()">
    <i class="fa-solid fa-robot"></i>
</div>

<style>
/* Mini Cards inside Chat */
.bot-card {
    background: #f8f9fa;
    border: 1px solid #eee;
    border-radius: 8px;
    padding: 10px;
    margin-top: 5px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: 0.2s;
    display: flex;
    gap: 10px;
    align-items: center;
}
.bot-card:hover { background: #eef; border-color: #aaf; }
.bot-card img { width: 40px; height: 40px; border-radius: 4px; object-fit: cover; }
.bot-card-info { flex: 1; overflow: hidden; }
.bot-card-title { font-weight: bold; color: #333; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.bot-card-sub { font-size: 0.75rem; color: #666; }
.bot-section-title { font-size: 0.7rem; font-weight: bold; color: #888; text-transform: uppercase; margin: 10px 0 5px; letter-spacing: 1px; }
</style>

<!-- Chat Window -->
<div id="chatWindow" class="chat-window">
    <!-- Header -->
    <div class="chat-header">
        <div class="chat-brand">
            <div class="chat-logo-circle">
                <i class="fa-solid fa-robot"></i>
            </div>
            <div class="chat-info">
                <h4>BOTMOTIF</h4>
                <span>Online</span>
            </div>
        </div>
        <i class="fa-solid fa-xmark chat-close" onclick="toggleChat()"></i>
    </div>

    <!-- Body -->
    <div class="chat-body" id="chatBody">
        <!-- Initial Message -->
        <div class="message bot">
            <div class="avatar-small"><i class="fa-solid fa-robot"></i></div>
            <div class="message-box">
                Halo! Ceritakan masalah kendaraan Anda, saya akan bantu diagnosa dan carikan solusinya. 🚗🔧
            </div>
        </div>
    </div>

    <!-- Footer Input -->
    <div class="chat-footer">
        <div class="chat-input-wrapper">
            <input type="text" id="chatInput" class="chat-input" placeholder="Misal: Mesin bunyi berisik..." onkeypress="handleKeyPress(event)">
            <button class="chat-send-btn" onclick="sendMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<script>
    function toggleChat() {
        const chatWindow = document.getElementById('chatWindow');
        chatWindow.style.display = (chatWindow.style.display === 'flex') ? 'none' : 'flex';
    }

    async function sendMessage() {
        const input = document.getElementById('chatInput');
        const text = input.value.trim();
        const chatBody = document.getElementById('chatBody');

        if (!text) return;

        // 1. User Message
        const userMsg = document.createElement('div');
        userMsg.className = 'message user';
        userMsg.innerHTML = `<div class="message-box">${text}</div><div class="avatar-small" style="background:var(--primary-blue);"><i class="fa-solid fa-user"></i></div>`;
        chatBody.appendChild(userMsg);
        input.value = '';
        chatBody.scrollTop = chatBody.scrollHeight;

        // 2. Loading State
        const loadingId = 'loading-' + Date.now();
        const loadingMsg = document.createElement('div');
        loadingMsg.className = 'message bot';
        loadingMsg.id = loadingId;
        loadingMsg.innerHTML = `<div class="avatar-small"><i class="fa-solid fa-robot"></i></div><div class="message-box">...</div>`;
        chatBody.appendChild(loadingMsg);
        chatBody.scrollTop = chatBody.scrollHeight;

        try {
            // 3. Call API
            const response = await fetch('api/chat_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: text })
            });
            const data = await response.json();

            // Remove Loading
            document.getElementById(loadingId).remove();

            // 4. Construct Rich Response
            let contentHtml = "";

            // A. Explanation
            contentHtml += `<div class="message-box" style="margin-bottom:10px;">${data.explanation}</div>`;

            // B. Article (Edukasi)
            if (data.article) {
                contentHtml += `<div class="bot-section-title">📚 Edukasi Terkait</div>`;
                contentHtml += `
                    <div class="bot-card" onclick="window.location.href='edukasi.php'">
                        <img src="${data.article.image}" alt="Article">
                        <div class="bot-card-info">
                            <div class="bot-card-title">${data.article.title}</div>
                            <div class="bot-card-sub">Baca selengkapnya &rarr;</div>
                        </div>
                    </div>`;
            }

            // C. Products (Harga)
            if (data.products && data.products.length > 0) {
                contentHtml += `<div class="bot-section-title">🛒 Sparepart Rekomendasi</div>`;
                data.products.forEach(p => {
                    contentHtml += `
                        <div class="bot-card" onclick="window.location.href='harga.php?search=${encodeURIComponent(p.name)}'">
                            <img src="${p.image}" alt="Part">
                            <div class="bot-card-info">
                                <div class="bot-card-title">${p.name}</div>
                                <div class="bot-card-sub">Rp ${Number(p.price_min).toLocaleString()}</div>
                            </div>
                        </div>`;
                });
            }

            // D. Workshop (Bengkel)
            if (data.workshop) {
                contentHtml += `<div class="bot-section-title">📍 Bengkel Terdekat</div>`;
                contentHtml += `
                    <div class="bot-card" onclick="window.open('https://www.google.com/maps/search/?api=1&query=${data.workshop.lat},${data.workshop.lng}', '_blank')">
                        <div class="avatar-small" style="background:#eee; color:#333;"><i class="fa-solid fa-map-location-dot"></i></div>
                        <div class="bot-card-info">
                            <div class="bot-card-title">${data.workshop.name}</div>
                            <div class="bot-card-sub">${data.workshop.address} <i class="fa-solid fa-arrow-up-right-from-square" style="font-size:10px; margin-left:5px;"></i></div>
                        </div>
                    </div>`;
            }

            // Append Final Bot Message
            const botMsg = document.createElement('div');
            botMsg.className = 'message bot';
            botMsg.innerHTML = `<div class="avatar-small"><i class="fa-solid fa-robot"></i></div><div style="display:flex; flex-direction:column; width:100%;">${contentHtml}</div>`;
            chatBody.appendChild(botMsg);

        } catch (error) {
            console.error(error);
            document.getElementById(loadingId).innerHTML = `<div class="avatar-small"><i class="fa-solid fa-robot"></i></div><div class="message-box">Maaf, ada gangguan server.</div>`;
        }
        
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    function handleKeyPress(e) {
        if (e.key === 'Enter') sendMessage();
    }
</script>
