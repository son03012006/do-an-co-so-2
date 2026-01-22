<?php 
// ===== INCLUDE SIDEBAR ADMIN =====
include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; 
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                animation: { 'blob': 'blob 7s infinite' },
                keyframes: {
                    blob: {
                        '0%': { transform: 'translate(0px, 0px) scale(1)' },
                        '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                        '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                        '100%': { transform: 'translate(0px, 0px) scale(1)' },
                    }
                }
            }
        }
    }
</script>

<div class="md:ml-64 bg-slate-50 min-h-screen flex items-center justify-center p-8 relative overflow-hidden">
    <div class="absolute inset-0 z-0 opacity-[0.4]" style="background-image: radial-gradient(#cbd5e1 1px, transparent 1px); background-size: 32px 32px;"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-2xl bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl shadow-indigo-100/50 border border-white/50 overflow-hidden">
        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-white">Thêm sách mới</h2>
                <p class="text-indigo-100 text-sm mt-1">Nhập thông tin chi tiết để thêm sách vào kho.</p>
            </div>
            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center text-white backdrop-blur-md">
                <i class="fa-solid fa-book-medical text-lg"></i>
            </div>
        </div>

        <div class="p-8">
            <?php if (!empty($error)): ?>
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-xl"></i>
                    <span class="text-sm font-medium"><?= $error ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($msg)): ?>
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="text-sm font-medium"><?= $msg ?></span>
                </div>
            <?php endif; ?>

            <form method="post" action="index.php?c=addbook&a=add" enctype="multipart/form-data" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Ảnh bìa sách</label>
                    
                    <div class="flex flex-row items-center gap-6 p-4 border border-slate-300 rounded-xl bg-slate-50">
                        
                        <div class="relative w-28 h-36 bg-white border border-slate-300 rounded-lg overflow-hidden shrink-0 flex items-center justify-center shadow-sm">
                            
                            <div class="text-slate-300 flex flex-col items-center">
                                <i class="fa-regular fa-image text-3xl mb-1"></i>
                                <span class="text-[10px] font-bold">PREVIEW</span>
                            </div>

                            <img id="preview-img" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden z-10">
                        </div>
                        
                        <div class="flex-1">
                            <input type="file" name="bookimg" accept="image/*" onchange="loadFile(event)"
                                class="block w-full text-sm text-slate-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-600 file:text-white
                                hover:file:bg-indigo-700
                                cursor-pointer outline-none mb-2
                            "/>
                            <p class="text-xs text-slate-500">
                                <i class="fa-solid fa-circle-info mr-1"></i>Chấp nhận: JPG, PNG, GIF.
                            </p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Tên sách <span class="text-red-500">*</span></label>
                    <input type="text" name="bookname" required 
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all"
                        placeholder="Ví dụ: Đắc Nhân Tâm">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category" required 
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                            <option value="">-- Chọn danh mục --</option>
                            <?php if(!empty($categories)): foreach ($categories as $c): ?>
                                <option value="<?= $c->id ?>"><?= $c->CategoryName ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tác giả <span class="text-red-500">*</span></label>
                        <select name="author" required 
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
                            <option value="">-- Chọn tác giả --</option>
                            <?php if(!empty($authors)): foreach ($authors as $a): ?>
                                <option value="<?= $a->id ?>"><?= $a->AuthorName ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Mã ISBN <span class="text-red-500">*</span></label>
                        <input type="text" name="isbn" required 
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="VD: 978-3-16">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Số lượng <span class="text-red-500">*</span></label>
                        <input type="number" name="copies" required min="1"
                            class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none"
                            placeholder="VD: 10">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Giá tiền (VNĐ) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required min="0"
                        class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none"
                        placeholder="VD: 50000">
                </div>

                <div class="pt-4">
                    <button type="submit" name="add" 
                        class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-plus-circle"></i> Thêm sách ngay
                    </button>
                    <a href="index.php?c=managerbook&a=index" class="block w-full text-center mt-3 text-sm text-slate-500 hover:text-indigo-600">
                        <i class="fa-solid fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    // Hàm xử lý hiển thị ảnh - Rất đơn giản để tránh lỗi
    var loadFile = function(event) {
        var output = document.getElementById('preview-img');
        
        // Kiểm tra xem người dùng có chọn file thật không
        if(event.target.files && event.target.files[0]){
            output.src = URL.createObjectURL(event.target.files[0]);
            output.onload = function() {
                URL.revokeObjectURL(output.src) 
            }
            // Chỉ cần bỏ class hidden là ảnh sẽ hiện đè lên icon (do z-index)
            output.classList.remove('hidden'); 
        }
    };
</script>