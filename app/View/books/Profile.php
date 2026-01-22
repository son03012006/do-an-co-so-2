<?php
// ===== INCLUDE SIDEBAR =====
include __DIR__ . '/../layouts/sidebar.php';

// Xử lý logic ảnh đại diện
$avatarFile = $profile->ProfileImage ?? '';
$avatarPath = 'public/assets/img/profile/' . $avatarFile;

// Kiểm tra file có tồn tại không (Dùng đường dẫn vật lý nếu cần, ở đây giả lập check đơn giản)
// Lưu ý: BASE_URL cần được định nghĩa trong file config
$avatarUrl = (!empty($avatarFile)) ? BASE_URL . $avatarPath . '?v=' . time() : 'https://ui-avatars.com/api/?name=' . urlencode($profile->FullName) . '&background=random&size=256';
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    indigo: { 50: '#eef2ff', 100: '#e0e7ff', 600: '#4f46e5', 700: '#4338ca', 900: '#312e81' },
                    slate: { 50: '#f8fafc', 100: '#f1f5f9', 800: '#1e293b' },
                    emerald: { 50: '#ecfdf5', 600: '#059669', 700: '#047857' },
                    rose: { 50: '#fff1f2', 600: '#e11d48', 700: '#be123c' }
                }
            }
        }
    }
</script>

<div class="max-w-7xl mx-auto md:ml-80 px-4 sm:px-8 py-10 min-h-screen bg-slate-50">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Hồ sơ cá nhân</h1>
        <p class="text-slate-500 mt-1">Quản lý thông tin tài khoản và cập nhật ảnh đại diện.</p>
    </div>

    <?php if (isset($_GET['updated'])): ?>
        <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-2 shadow-sm animate-bounce-short">
            <i class="fa-solid fa-circle-check text-lg"></i>
            <span class="font-medium">Cập nhật thông tin thành công!</span>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8 text-center relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                
                <div class="relative inline-block mt-8 mb-4">
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="w-32 h-32 rounded-full border-4 border-white shadow-lg object-cover bg-white">
                    <label for="avatarInput" class="absolute bottom-1 right-1 bg-white text-slate-700 p-2 rounded-full shadow-md cursor-pointer hover:text-indigo-600 transition-colors border border-slate-100">
                        <i class="fa-solid fa-camera"></i>
                    </label>
                </div>

                <h2 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($profile->FullName) ?></h2>
                <p class="text-slate-500 text-sm mb-4"><?= htmlspecialchars($profile->EmailId) ?></p>

                <div class="flex justify-center mb-6">
                    <?php if ($profile->Status): ?>
                        <span class="px-4 py-1.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Đang hoạt động
                        </span>
                    <?php else: ?>
                        <span class="px-4 py-1.5 rounded-full text-sm font-bold bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span> Đã khóa
                        </span>
                    <?php endif; ?>
                </div>

                <div class="border-t border-slate-100 pt-6 text-left space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-sm font-medium">Mã sinh viên</span>
                        <span class="text-slate-800 font-bold bg-slate-100 px-3 py-1 rounded-lg"><?= $profile->StudentId ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 text-sm font-medium">Ngày tham gia</span>
                        <span class="text-slate-700 font-medium"><?= date('d/m/Y', strtotime($profile->RegDate)) ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-8">
                <div class="mb-6 pb-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Chỉnh sửa thông tin</h3>
                    <p class="text-slate-500 text-sm">Cập nhật thông tin cá nhân của bạn.</p>
                </div>

                <form method="post" enctype="multipart/form-data" class="space-y-6">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Họ và tên</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa-regular fa-user"></i>
                                </span>
                                <input type="text" name="fullname" value="<?= htmlentities($profile->FullName) ?>" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium placeholder-slate-400">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Số điện thoại</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <i class="fa-solid fa-phone"></i>
                                </span>
                                <input type="text" name="mobileno" maxlength="10" value="<?= htmlentities($profile->MobileNumber) ?>" required
                                    class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all text-slate-700 font-medium placeholder-slate-400">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Email đăng nhập <span class="text-xs font-normal text-slate-400">(Không thể thay đổi)</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <input type="email" value="<?= htmlentities($profile->EmailId) ?>" readonly
                                class="w-full pl-10 pr-4 py-3 bg-slate-100 border border-slate-200 rounded-xl text-slate-500 font-medium cursor-not-allowed select-none">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Tải lên ảnh mới</label>
                        <input type="file" name="avatar" id="avatarInput"
                            class="block w-full text-sm text-slate-500
                            file:mr-4 file:py-2.5 file:px-4
                            file:rounded-full file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100 transition-all cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                        <p class="text-xs text-slate-400 mt-1">Hỗ trợ định dạng: JPG, PNG, GIF. Tối đa 2MB.</p>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <button type="button" onclick="window.history.back()" 
                            class="px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-all">
                            Hủy bỏ
                        </button>
                        <button type="submit" name="update" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>