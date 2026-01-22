<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    body, html { font-family: 'Be Vietnam Pro', sans-serif; background-color: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.3s ease-out forwards; }
</style>

<div class="p-4 sm:ml-64 h-screen flex flex-col box-border">
    
    <div class="mb-4 flex items-center justify-between shrink-0">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Hỗ trợ & Tư vấn</h1>
            <p class="text-slate-500 text-sm">Kết nối trực tiếp với thủ thư</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 bg-white px-3 py-1.5 rounded-full shadow-sm border border-slate-200">
            <span class="relative flex h-3 w-3">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
            </span>
            <span class="text-xs font-semibold text-slate-600">Online</span>
        </div>
    </div>

    <div class="flex-1 bg-white rounded-2xl shadow-xl border border-slate-200 flex flex-col overflow-hidden relative">
        
        <div class="px-6 py-3 border-b border-slate-100 bg-white flex items-center justify-between z-10 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 relative">
                    <i class="fa fa-headset text-lg"></i>
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Admin Thư Viện</h3>
                    <p class="text-xs text-slate-500">Thường trả lời ngay</p>
                </div>
            </div>
        </div>

        <div id="chat-box" class="flex-1 overflow-y-auto p-6 space-y-4 bg-slate-50 custom-scrollbar">
            <div class="flex flex-col items-center justify-center h-full text-slate-400 gap-2">
                <i class="fa fa-circle-notch fa-spin text-2xl text-indigo-500"></i>
                <span class="text-sm">Đang tải...</span>
            </div>
        </div>

        <div id="image-preview-area" class="hidden px-4 py-2 bg-slate-100 border-t border-slate-200">
            <div class="relative inline-block">
                <img id="img-preview" src="" class="h-20 rounded-lg border border-slate-300 shadow-sm object-cover">
                <button type="button" id="btn-remove-img" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow-md hover:bg-red-600">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>

        <div class="p-4 bg-white border-t border-slate-100">
            <form id="chat-form" class="flex gap-2 items-end">
                <button type="button" id="btn-select-img" class="text-slate-400 hover:text-indigo-600 p-3 mb-1 transition-colors" title="Gửi hình ảnh">
                    <i class="fa fa-image text-xl"></i>
                </button>
                <input type="file" id="image-input" accept="image/*" class="hidden">

                <div class="flex-1 relative">
                    <input type="text" id="message-input" 
                        class="w-full bg-slate-100 text-slate-700 text-sm rounded-xl border-0 focus:ring-2 focus:ring-indigo-500 focus:bg-white transition-all px-4 py-3.5 shadow-inner outline-none" 
                        placeholder="Nhập nội dung..." autocomplete="off">
                </div>
                
                <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl w-12 h-12 flex items-center justify-center shadow-lg shadow-indigo-200 transition-all hover:scale-105 active:scale-95">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const API_URL = 'app/View/books/user_chat_api.php'; 

    const chatBox = document.getElementById('chat-box');
    const chatForm = document.getElementById('chat-form');
    const msgInput = document.getElementById('message-input');
    
    // Các biến cho phần ảnh
    const btnSelectImg = document.getElementById('btn-select-img');
    const imageInput = document.getElementById('image-input');
    const previewArea = document.getElementById('image-preview-area');
    const imgPreview = document.getElementById('img-preview');
    const btnRemoveImg = document.getElementById('btn-remove-img');

    let isFirstLoad = true;

    // --- 1. XỬ LÝ CHỌN ẢNH & PREVIEW ---
    btnSelectImg.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                imgPreview.src = e.target.result;
                previewArea.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    btnRemoveImg.addEventListener('click', function() {
        imageInput.value = ''; // Reset input
        previewArea.classList.add('hidden');
        imgPreview.src = '';
    });

    // --- 2. HÀM TẢI TIN NHẮN ---
    function loadMessages() {
        const formData = new FormData();
        formData.append('action', 'get_messages');

        fetch(API_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                renderMessages(res.data);
            }
        })
        .catch(err => console.error(err));
    }

    // --- 3. HÀM HIỂN THỊ TIN NHẮN ---
    function renderMessages(data) {
        if(data.length === 0 && isFirstLoad) {
            chatBox.innerHTML = '<div class="text-center text-slate-400 mt-10">Chưa có tin nhắn.</div>';
            isFirstLoad = false;
            return;
        }

        let html = '';
        data.forEach(msg => {
            const isMe = (msg.sender_type === 'student'); 
            const timeStr = msg.created_at ? new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'}) : '';

            // Kiểm tra nội dung: Có text không? Có ảnh không?
            let contentHtml = '';
            
            // Nếu có ảnh
            if (msg.file_path) {
                // Đường dẫn ảnh (Nếu bạn lưu file ở assets/uploads/chat/...)
                // Cần đảm bảo BASE_URL đúng hoặc dùng đường dẫn tương đối
                const imgSrc = msg.file_path; 
                contentHtml += `<div class="mb-2"><img src="${imgSrc}" class="max-w-[200px] rounded-lg border border-slate-200 cursor-pointer hover:opacity-90 transition" onclick="window.open(this.src, '_blank')"></div>`;
            }
            
            // Nếu có text
            if (msg.message) {
                contentHtml += `<span>${msg.message}</span>`;
            }

            if (isMe) {
                html += `
                <div class="flex justify-end mb-4 animate-fade-in-up">
                    <div class="flex flex-col items-end max-w-[80%]">
                        <div class="bg-indigo-600 text-white py-2 px-4 rounded-2xl rounded-tr-none shadow-md text-sm">
                            ${contentHtml}
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 mr-1">${timeStr}</span>
                    </div>
                </div>`;
            } else {
                html += `
                <div class="flex justify-start mb-4 animate-fade-in-up">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center mr-2 shadow-sm shrink-0">
                        <i class="fa fa-user-shield text-xs text-indigo-600"></i>
                    </div>
                    <div class="flex flex-col items-start max-w-[80%]">
                        <div class="bg-white border border-slate-200 text-slate-700 py-2 px-4 rounded-2xl rounded-tl-none shadow-sm text-sm">
                            ${contentHtml}
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 ml-1">${timeStr} • Admin</span>
                    </div>
                </div>`;
            }
        });

        if (chatBox.innerHTML !== html) {
             if (!isFirstLoad || data.length > 0) {
                 chatBox.innerHTML = html;
             }
        }
        
        if (isFirstLoad || (chatBox.scrollHeight - chatBox.scrollTop <= chatBox.clientHeight + 200)) {
            chatBox.scrollTop = chatBox.scrollHeight;
            isFirstLoad = false;
        }
    }

    // --- 4. GỬI TIN NHẮN (Text + Ảnh) ---
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const msg = msgInput.value.trim();
        const hasFile = imageInput.files.length > 0;

        if (!msg && !hasFile) return; // Không gửi nếu trống cả 2

        // Chuẩn bị dữ liệu gửi
        const formData = new FormData();
        formData.append('action', 'send_message');
        formData.append('message', msg);
        
        if (hasFile) {
            formData.append('image', imageInput.files[0]);
        }

        // Hiện tạm UI (Optimistic UI)
        let tempContent = '';
        if (hasFile) {
            tempContent += `<div class="mb-1"><i class="fa fa-image"></i> [Đang gửi ảnh...]</div>`;
        }
        if (msg) tempContent += msg;

        const tempHtml = `
            <div class="flex justify-end mb-4 animate-fade-in-up opacity-70">
                <div class="flex flex-col items-end max-w-[80%]">
                    <div class="bg-indigo-600 text-white py-2 px-4 rounded-2xl rounded-tr-none text-sm">
                        ${tempContent} <i class="fa fa-spinner fa-spin text-xs ml-1"></i>
                    </div>
                </div>
            </div>`;
        chatBox.insertAdjacentHTML('beforeend', tempHtml);
        chatBox.scrollTop = chatBox.scrollHeight;

        // Reset form ngay lập tức
        msgInput.value = '';
        imageInput.value = '';
        previewArea.classList.add('hidden');
        imgPreview.src = '';

        // Gửi API
        fetch(API_URL, { method: 'POST', body: formData })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                loadMessages();
            } else {
                alert('Lỗi: ' + (res.msg || 'Không thể gửi tin nhắn'));
                loadMessages(); // Reload để thấy trạng thái cuối
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            alert('Lỗi kết nối: ' + err.message);
        });
    });

    loadMessages();
    setInterval(loadMessages, 3000);
});
</script>