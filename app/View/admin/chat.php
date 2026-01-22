<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Chat System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .animate-msg { animation: fadeInUp 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-slate-100 font-sans antialiased text-slate-800">

    <?php 
        // Load sidebar nếu có
        $sidebar_path = __DIR__ . '/../../View/layouts/layoutsadmin/sidebaradmin.php';
        if (file_exists($sidebar_path)) include $sidebar_path;
    ?>

    <div class="md:ml-64 h-screen flex flex-col transition-all duration-300">
        <div class="bg-white p-4 shadow-sm md:hidden flex items-center justify-between shrink-0">
            <h1 class="font-bold text-lg text-indigo-700">Live Chat Admin</h1>
            <button class="text-slate-500 hover:text-indigo-600"><i class="fa-solid fa-bars"></i></button>
        </div>

        <div class="flex-1 flex overflow-hidden p-2 md:p-4 gap-4 h-full">
            
            <div class="w-full md:w-1/3 lg:w-1/4 bg-white rounded-2xl shadow-lg border border-gray-200 flex flex-col h-full">
                <div class="p-4 border-b border-gray-100 bg-gray-50 rounded-t-2xl flex justify-between items-center shrink-0">
                    <h2 class="font-bold text-gray-700"><i class="fa-solid fa-users text-indigo-500 mr-2"></i> Sinh viên</h2>
                    <span id="loading-status" class="text-xs text-indigo-500 font-medium hidden"><i class="fa fa-sync fa-spin"></i></span>
                </div>

                <div class="p-3 border-b border-gray-100 bg-white">
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" id="search-input" placeholder="Tìm tên sinh viên..." 
                               class="w-full pl-9 pr-3 py-2 bg-gray-100 rounded-lg text-sm focus:bg-white focus:ring-1 focus:ring-indigo-500 outline-none transition-all">
                    </div>
                </div>

                <div id="contact-list" class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
                    <div class="text-center text-gray-400 mt-10 text-sm">Đang tải dữ liệu...</div>
                </div>
            </div>

            <div class="hidden md:flex flex-col w-full md:w-2/3 lg:w-3/4 bg-white rounded-2xl shadow-lg border border-gray-200 relative h-full overflow-hidden" id="chat-container">
                
                <div id="welcome-screen" class="absolute inset-0 z-10 bg-slate-50 rounded-2xl flex flex-col items-center justify-center text-gray-400">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mb-4 shadow-sm">
                        <i class="fa-regular fa-paper-plane text-4xl text-indigo-400"></i>
                    </div>
                    <p class="text-lg font-medium text-gray-600">Chọn sinh viên để bắt đầu</p>
                </div>

                <div id="chat-header" class="hidden shrink-0 px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-white z-20">
                    <div class="flex items-center gap-4">
                        <img id="current-avatar" src="" class="w-10 h-10 rounded-full border border-gray-200 object-cover">
                        <div>
                            <h3 id="current-name" class="font-bold text-gray-800 text-lg leading-tight">---</h3>
                            <span class="text-xs text-green-600 font-medium">Đang trực tuyến</span>
                        </div>
                    </div>
                </div>

                <div id="messages-box" class="hidden flex-1 overflow-y-auto p-6 bg-slate-50 space-y-3 custom-scrollbar z-20"></div>

                <div id="input-area" class="hidden shrink-0 p-4 bg-white border-t border-gray-100 z-20">
                    <form id="chat-form" class="flex items-end gap-2">
                        <input type="hidden" id="current-student-id">
                        
                        <div class="flex-1 bg-gray-100 rounded-2xl flex items-center px-4 py-2 focus-within:ring-2 focus-within:ring-indigo-100 focus-within:bg-white transition">
                            <input type="text" id="msg-input" placeholder="Nhập tin nhắn..." autocomplete="off"
                                class="w-full bg-transparent border-none outline-none text-gray-700 placeholder-gray-400 py-2">
                        </div>
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition transform hover:scale-105 active:scale-95 shrink-0">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- CẤU HÌNH ĐƯỜNG DẪN TỰ ĐỘNG ---
        let pathName = window.location.pathname;
        let rootPath = '/';
        let parts = pathName.split('/');
        if (parts.length > 2 && parts[1] !== 'admin') {
            rootPath = '/' + parts[1] + '/';
        }

        const API_URL = rootPath + 'app/View/admin/chat_api.php';
        const IMG_BASE_URL = rootPath;

        let currentStudentId = null;
        let pollInterval = null;
        let isScrolledToBottom = true;
        
        // Biến lưu trữ toàn bộ danh sách để tìm kiếm
        let allContactsData = []; 

        $(document).ready(function() {
            loadContacts();
            setInterval(() => loadContacts(true), 5000);

            // Kiểm tra scroll
            $('#messages-box').on('scroll', function() {
                let div = this;
                isScrolledToBottom = (div.scrollHeight - div.scrollTop - div.clientHeight) < 50;
            });

            // Xử lý sự kiện tìm kiếm
            $('#search-input').on('keyup', function() {
                let keyword = $(this).val().toLowerCase();
                filterContacts(keyword);
            });
        });

        // 1. Load danh sách SV và lưu vào biến toàn cục
        function loadContacts(silent = false) {
            if(!silent) $('#loading-status').removeClass('hidden');
            
            $.post(API_URL, { action: 'get_contacts' }, function(res) {
                if(!silent) $('#loading-status').addClass('hidden');
                
                if(res.status === 'success') {
                    allContactsData = res.data; // Lưu dữ liệu gốc
                    
                    // Nếu đang tìm kiếm thì filter, không thì hiển thị hết
                    let keyword = $('#search-input').val().toLowerCase();
                    if(keyword) {
                        filterContacts(keyword);
                    } else {
                        renderContacts(allContactsData);
                    }
                }
            }, 'json').fail(function() { console.error("Lỗi kết nối API"); });
        }

        // Hàm lọc danh sách
        function filterContacts(keyword) {
            if(!keyword) {
                renderContacts(allContactsData);
                return;
            }
            // Lọc theo tên hoặc mã sinh viên
            let filtered = allContactsData.filter(u => 
                (u.FullName && u.FullName.toLowerCase().includes(keyword)) || 
                (u.StudentId && u.StudentId.toLowerCase().includes(keyword))
            );
            renderContacts(filtered);
        }

        // Hàm render giao diện
        function renderContacts(users) {
            if(users.length === 0) {
                $('#contact-list').html('<div class="p-4 text-center text-gray-400 text-sm">Không tìm thấy sinh viên</div>');
                return;
            }
            let html = '';
            users.forEach(u => {
                let isActive = (u.StudentId == currentStudentId);
                let bgClass = isActive ? 'bg-indigo-50 border-indigo-200' : 'hover:bg-gray-50 border-transparent';
                
                // Xử lý avatar
                let avatarUrl = (u.ProfileImage && u.ProfileImage !== 'NULL' && u.ProfileImage !== '') 
                                ? IMG_BASE_URL + 'public/assets/img/profile/' + u.ProfileImage 
                                : `https://ui-avatars.com/api/?name=${encodeURIComponent(u.FullName)}&background=random&color=fff`;
                
                // Badge tin chưa đọc
                let badge = (u.unread > 0) ? `<span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full ml-auto">${u.unread}</span>` : '';
                
                // Hiển thị tin cuối
                let lastMsg = u.last_msg || (u.last_file ? '<i class="fa fa-image"></i> [Hình ảnh]' : '...');
                
                html += `
                <div onclick="selectUser('${u.StudentId}', '${u.FullName}', '${avatarUrl}')" class="flex items-center gap-3 p-3 mb-2 rounded-xl cursor-pointer border transition-all duration-200 ${bgClass}">
                    <div class="relative shrink-0">
                        <img src="${avatarUrl}" class="w-12 h-12 rounded-full object-cover bg-gray-200 border border-gray-100">
                        ${u.unread > 0 ? '<div class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white animate-pulse"></div>' : ''}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-0.5"><h4 class="text-sm font-bold text-gray-700 truncate">${u.FullName}</h4>${badge}</div>
                        <p class="text-xs text-gray-500 truncate pr-2">${lastMsg}</p>
                    </div>
                </div>`;
            });
            $('#contact-list').html(html);
        }

        // 2. Chọn SV
        window.selectUser = function(id, name, avatar) {
            currentStudentId = id;
            $('#welcome-screen').addClass('hidden');
            $('#chat-header, #messages-box, #input-area').removeClass('hidden');
            $('#current-name').text(name);
            $('#current-avatar').attr('src', avatar);
            $('#current-student-id').val(id);
            $('#chat-container').removeClass('hidden').addClass('flex');
            $('.md\\:w-1\\/3').addClass('hidden md:flex'); // Ẩn list trên mobile

            loadMessages(true);
            if(pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(() => loadMessages(false), 3000);
            
            // Cập nhật lại list để xóa badge unread ngay lập tức
            loadContacts(true); 
        }

        // 3. Load tin nhắn
        function loadMessages(forceScroll = false) {
            if(!currentStudentId) return;
            $.post(API_URL, { action: 'get_messages', student_id: currentStudentId }, function(res) {
                if(res.status === 'success') renderMessages(res.data, forceScroll);
            }, 'json');
        }

        function renderMessages(msgs, forceScroll) {
            let html = '';
            msgs.forEach(msg => {
                let time = new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour: '2-digit', minute:'2-digit'});
                let contentHtml = '';
                
                // Hiển thị ảnh nếu tin cũ có ảnh (vẫn giữ logic hiển thị để xem lại tin cũ)
                if(msg.file_path) {
                    contentHtml += `<div class="mb-1"><img src="${IMG_BASE_URL + msg.file_path}" class="max-w-[200px] rounded-lg border cursor-pointer" onclick="window.open(this.src)"></div>`;
                }
                if(msg.message) contentHtml += `<span>${msg.message}</span>`;

                if(msg.sender_type === 'admin') {
                    html += `<div class="flex justify-end animate-msg mb-2"><div class="max-w-[75%]"><div class="bg-indigo-600 text-white py-2 px-4 rounded-2xl rounded-tr-sm shadow-md text-[15px]">${contentHtml}</div><div class="text-[10px] text-gray-400 text-right mt-1">${time}</div></div></div>`;
                } else {
                    html += `<div class="flex justify-start animate-msg mb-2"><div class="max-w-[75%]"><div class="bg-white border text-gray-800 py-2 px-4 rounded-2xl rounded-tl-sm shadow-sm text-[15px]">${contentHtml}</div><div class="text-[10px] text-gray-400 text-left mt-1">${time}</div></div></div>`;
                }
            });

            let box = $('#messages-box');
            if(box.html().length !== html.length) {
                box.html(html);
                if(forceScroll || isScrolledToBottom) box.scrollTop(box[0].scrollHeight);
            }
        }

        // 4. GỬI TIN NHẮN (CHỈ TEXT)
        $('#chat-form').submit(function(e) {
            e.preventDefault();
            
            let msg = $('#msg-input').val().trim();

            if(!msg || !currentStudentId) return;

            // UI giả lập hiển thị ngay
            $('#messages-box').append(`<div class="flex justify-end opacity-70"><div class="bg-indigo-600 text-white py-2 px-4 rounded-2xl text-[15px]">${msg} <i class="fa fa-spinner fa-spin text-xs"></i></div></div>`);
            $('#messages-box').scrollTop($('#messages-box')[0].scrollHeight);

            // Reset input
            $('#msg-input').val('');

            // Gửi Ajax (Chỉ gửi text)
            $.post(API_URL, {
                action: 'send_message',
                student_id: currentStudentId,
                message: msg
            }, function(res) {
                if(res.status === 'success') {
                    loadMessages(true);
                    loadContacts(true); // Cập nhật thứ tự user
                } else {
                    alert('Lỗi: ' + res.msg);
                }
            }, 'json').fail(function() {
                alert('Mất kết nối server');
            });
        });
    </script>
</body>
</html>