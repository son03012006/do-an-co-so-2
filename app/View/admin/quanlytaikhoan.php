<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<?php include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; ?>

<?php
// SỬA LỖI PATH: Dựa vào ảnh bạn gửi, ảnh nằm ngay trong public/assets/img/profile/
// Và vì chạy từ index.php ở root nên không cần dấu ../ ở đầu
$img_path_prefix = 'public/assets/img/profile/';
?>

<div class="min-h-screen bg-slate-50 relative font-sans md:ml-64 transition-all duration-300">

    <div class="bg-white shadow-sm sticky top-0 z-30 px-8 py-5 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <span class="bg-indigo-100 text-indigo-600 p-2 rounded-lg">
                    <i class="fa fa-users"></i>
                </span>
                Quản lý sinh viên
            </h2>
            <p class="text-sm text-slate-500 mt-1 ml-11">Danh sách và trạng thái tài khoản sinh viên</p>
        </div>
    </div>

    <div class="p-6">
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r shadow-sm flex items-center justify-between animate-fade-in-down">
                <div class="flex items-center">
                    <i class="fa fa-check-circle text-xl mr-3"></i>
                    <span class="font-medium"><?= $_SESSION['msg']; ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 text-slate-600 uppercase text-xs font-bold tracking-wider leading-normal">
                            <th class="py-4 px-6 text-center w-16">#</th>
                            <th class="py-4 px-6">Mã SV</th>
                            <th class="py-4 px-6">Họ tên & Email</th>
                            <th class="py-4 px-6">Số điện thoại</th>
                            <th class="py-4 px-6">Ngày đăng ký</th>
                            <th class="py-4 px-6 text-center">Trạng thái</th>
                            <th class="py-4 px-6 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-600 text-sm font-light">
                        <?php foreach ($users as $i => $u): ?>
                            <tr class="border-b border-gray-100 hover:bg-indigo-50/50 transition duration-150">
                                <td class="py-4 px-6 text-center font-bold text-slate-400"><?= $i + 1 ?></td>
                                <td class="py-4 px-6 font-semibold text-slate-700"><?= $u->StudentId ?></td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center group">
                                        <div class="relative w-10 h-10 mr-3">
                                            <?php 
                                                $hasImage = !empty($u->ProfileImage) && $u->ProfileImage != 'NULL';
                                                // Ghép chuỗi đường dẫn ảnh
                                                $imageSrc = $hasImage ? $img_path_prefix . htmlentities($u->ProfileImage) : '';
                                                $initial = substr($u->FullName, 0, 1);
                                            ?>
                                            
                                            <div class="absolute inset-0 w-full h-full rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase avatar-text">
                                                <?= $initial ?>
                                            </div>

                                            <?php if ($hasImage): ?>
                                                <img src="<?= $imageSrc ?>" 
                                                     alt="Avatar" 
                                                     class="absolute inset-0 w-full h-full rounded-full object-cover border border-gray-200 shadow-sm z-10"
                                                     onerror="this.style.display='none'"> 
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div>
                                            <span class="block font-medium text-slate-800 group-hover:text-indigo-600 transition"><?= $u->FullName ?></span>
                                            <span class="block text-xs text-slate-500"><?= $u->EmailId ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6"><?= $u->MobileNumber ?></td>
                                <td class="py-4 px-6">
                                    <span class="bg-gray-100 text-gray-600 py-1 px-3 rounded-full text-xs font-medium">
                                        <?= date('d/m/Y', strtotime($u->RegDate)) ?>
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <?php if ($u->Status): ?>
                                        <span class="bg-emerald-100 text-emerald-600 py-1 px-3 rounded-full text-xs font-bold shadow-sm"><i class="fa fa-check mr-1"></i> Hoạt động</span>
                                    <?php else: ?>
                                        <span class="bg-rose-100 text-rose-600 py-1 px-3 rounded-full text-xs font-bold shadow-sm"><i class="fa fa-ban mr-1"></i> Đã khóa</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <div class="flex item-center justify-center gap-2">
                                        <?php if ($u->Status): ?>
                                            <a href="index.php?c=user&a=index&lock=<?= $u->id ?>" onclick="return confirm('Bạn chắc chắn muốn khóa tài khoản này?')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition shadow-sm" title="Khóa"><i class="fa fa-lock text-xs"></i></a>
                                        <?php else: ?>
                                            <a href="index.php?c=user&a=index&unlock=<?= $u->id ?>" onclick="return confirm('Bạn muốn mở khóa tài khoản này?')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition shadow-sm" title="Mở khóa"><i class="fa fa-unlock text-xs"></i></a>
                                        <?php endif; ?>
                                        
                                        <button 
                                            onclick="openModal(this)"
                                            data-id="<?= htmlspecialchars($u->StudentId) ?>"
                                            data-db-id="<?= $u->id ?>"
                                            data-fullname="<?= htmlspecialchars($u->FullName) ?>"
                                            data-email="<?= htmlspecialchars($u->EmailId) ?>"
                                            data-phone="<?= htmlspecialchars($u->MobileNumber) ?>"
                                            data-regdate="<?= date('d/m/Y', strtotime($u->RegDate)) ?>"
                                            data-status="<?= $u->Status ?>"
                                            data-password="<?= htmlspecialchars($u->Password) ?>"
                                            data-avatar="<?= $hasImage ? $imageSrc : '' ?>" 
                                            class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-50 text-indigo-500 hover:bg-indigo-500 hover:text-white transition shadow-sm"
                                            title="Xem chi tiết">
                                            <i class="fa fa-eye text-xs"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-100 flex items-center justify-between">
                <span class="text-xs text-gray-500">Hiển thị <?= count($users) ?> kết quả</span>
                <div class="flex gap-1">
                    <button class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-500 text-xs hover:bg-gray-100 disabled:opacity-50">Trước</button>
                    <button class="px-3 py-1 rounded border border-gray-300 bg-white text-gray-500 text-xs hover:bg-gray-100">Sau</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="studentModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-xl animate-fade-in-up">
                
                <div class="h-28 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                    <button type="button" onclick="closeModal()" class="absolute top-4 right-4 text-white hover:text-gray-200 bg-white/20 hover:bg-white/30 rounded-full w-8 h-8 flex items-center justify-center transition focus:outline-none">
                        <i class="fa fa-times text-sm"></i>
                    </button>
                </div>

                <div class="px-8 pb-8">
                    <div class="flex flex-col items-center -mt-12 mb-6">
                        <div class="h-24 w-24 rounded-full ring-4 ring-white bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-4xl uppercase shadow-lg z-10 overflow-hidden relative group">
                            <span id="modal_avatar_text">A</span>
                            <img id="modal_avatar_img" src="" class="absolute inset-0 w-full h-full object-cover hidden" alt="Avatar">
                        </div>
                        
                        <div class="text-center mt-3">
                            <h3 class="text-2xl font-bold text-gray-800 leading-tight" id="modal_fullname">Loading...</h3>
                            <p class="text-indigo-500 font-medium" id="modal_email">loading...</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 border-t border-gray-100 pt-6">
                        
                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Mã Sinh Viên</label>
                            <p class="mt-1 text-gray-800 font-medium bg-gray-50 px-3 py-2 rounded border border-gray-100 flex items-center gap-2">
                                <i class="fa fa-id-card text-indigo-400"></i> 
                                <span id="modal_id">---</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Số điện thoại</label>
                            <p class="mt-1 text-gray-800 font-medium bg-gray-50 px-3 py-2 rounded border border-gray-100 flex items-center gap-2">
                                <i class="fa fa-phone text-indigo-400"></i> 
                                <span id="modal_phone">---</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Ngày đăng ký</label>
                            <p class="mt-1 text-gray-800 font-medium bg-gray-50 px-3 py-2 rounded border border-gray-100 flex items-center gap-2">
                                <i class="fa fa-calendar text-indigo-400"></i> 
                                <span id="modal_regdate">---</span>
                            </p>
                        </div>

                        <div>
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Trạng thái</label>
                            <div class="mt-1 h-[42px] flex items-center" id="modal_status">
                                </div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wide">Mật khẩu (Mã hóa)</label>
                            <div class="relative mt-1">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fa fa-key text-indigo-400"></i>
                                </div>
                                <input type="password" id="modal_password" readonly 
                                    class="block w-full pl-10 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 focus:ring-indigo-500 focus:border-indigo-500 transition" 
                                    value="******">
                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-indigo-600 cursor-pointer focus:outline-none">
                                    <i class="fa fa-eye" id="password_icon"></i>
                                </button>
                            </div>
                            <p class="text-[10px] text-red-400 mt-1 italic pl-1">
                                * Lưu ý: Mật khẩu hiển thị chuỗi mã hóa MD5.
                            </p>
                        </div>

                    </div> 
                    
                    <div class="mt-8 flex justify-end gap-3 border-t border-gray-100 pt-6">
                        <button type="button" onclick="closeModal()" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition shadow-sm">
                            Đóng
                        </button>
                        <a href="#" id="modal_history_link" class="px-5 py-2.5 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center gap-2">
                            <i class="fa fa-history"></i> Lịch sử mượn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(button) {
        // 1. Lấy dữ liệu từ data attributes
        const dataset = button.dataset;

        // 2. Gán dữ liệu Text
        document.getElementById('modal_fullname').innerText = dataset.fullname;
        document.getElementById('modal_email').innerText = dataset.email;
        document.getElementById('modal_id').innerText = dataset.id;
        document.getElementById('modal_phone').innerText = dataset.phone;
        document.getElementById('modal_regdate').innerText = dataset.regdate;
        document.getElementById('modal_password').value = dataset.password;
        
        // Reset password view
        const pwdInput = document.getElementById('modal_password');
        const pwdIcon = document.getElementById('password_icon');
        pwdInput.type = "password";
        pwdIcon.classList.remove('fa-eye-slash');
        pwdIcon.classList.add('fa-eye');

        // 3. Xử lý Avatar (Logic mới cải tiến)
        const imgEl = document.getElementById('modal_avatar_img');
        const textEl = document.getElementById('modal_avatar_text');
        const avatarSrc = dataset.avatar;

        // Reset trạng thái trước khi load
        imgEl.classList.add('hidden');
        textEl.classList.remove('hidden');
        textEl.innerText = dataset.fullname.charAt(0); // Chữ cái đầu

        if (avatarSrc && avatarSrc.trim() !== "") {
            imgEl.src = avatarSrc;
            imgEl.classList.remove('hidden');
            textEl.classList.add('hidden');

            // FIX: Nếu ảnh load lỗi (404), tự động ẩn ảnh và hiện chữ
            imgEl.onerror = function() {
                console.warn('Image failed to load:', this.src);
                this.classList.add('hidden');
                textEl.classList.remove('hidden');
            };
        }

        // 4. Xử lý Trạng thái
        const statusDiv = document.getElementById('modal_status');
        if (dataset.status == '1') {
            statusDiv.innerHTML = '<span class="bg-emerald-100 text-emerald-600 py-1.5 px-4 rounded-lg text-sm font-bold border border-emerald-200 shadow-sm"><i class="fa fa-check-circle mr-1"></i> Đang hoạt động</span>';
        } else {
            statusDiv.innerHTML = '<span class="bg-rose-100 text-rose-600 py-1.5 px-4 rounded-lg text-sm font-bold border border-rose-200 shadow-sm"><i class="fa fa-ban mr-1"></i> Đã khóa</span>';
        }

        // 5. Link lịch sử
        document.getElementById('modal_history_link').href = 'index.php?c=report&a=userwise&sid=' + dataset.id;

        // 6. Hiển thị modal
        document.getElementById('studentModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('studentModal').classList.add('hidden');
    }

    function togglePassword() {
        const input = document.getElementById('modal_password');
        const icon = document.getElementById('password_icon');
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Đóng modal khi nhấn ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") closeModal();
    });
</script>

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: scale(0.95) translate3d(0, 20px, 0); }
        to { opacity: 1; transform: scale(1) translate3d(0, 0, 0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    }
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; height: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #c7c7c7; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #a0a0a0; }
</style>