<div class="p-2">
    <?php if ($eligibility['allowed']): ?>
        <div class="bg-indigo-50 p-4 rounded-xl mb-6 border border-indigo-100">
            <h5 class="font-bold text-indigo-800 mb-3 text-sm">Viết cảm nhận của bạn</h5>
            
            <form action="index.php?c=review&a=submit" method="POST">
                <input type="hidden" name="bookid" value="<?= $bookId ?>">
                
                <div class="flex items-center gap-1 mb-3">
                    <div class="star-rating flex flex-row-reverse justify-end">
                        <input type="radio" id="star5" name="rating" value="5" class="peer hidden" checked />
                        <label for="star5" class="text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 text-2xl cursor-pointer transition-colors"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star4" name="rating" value="4" class="peer hidden" />
                        <label for="star4" class="text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 text-2xl cursor-pointer transition-colors"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star3" name="rating" value="3" class="peer hidden" />
                        <label for="star3" class="text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 text-2xl cursor-pointer transition-colors"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star2" name="rating" value="2" class="peer hidden" />
                        <label for="star2" class="text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 text-2xl cursor-pointer transition-colors"><i class="fa-solid fa-star"></i></label>
                        
                        <input type="radio" id="star1" name="rating" value="1" class="peer hidden" />
                        <label for="star1" class="text-slate-300 peer-checked:text-amber-400 hover:text-amber-400 text-2xl cursor-pointer transition-colors"><i class="fa-solid fa-star"></i></label>
                    </div>
                    <span class="text-xs text-slate-500 ml-2 font-medium">(Chọn số sao)</span>
                </div>

                <textarea name="comment" rows="3" required
                    class="w-full p-3 text-sm border border-indigo-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none resize-none"
                    placeholder="Cuốn sách này thế nào? Hãy chia sẻ với mọi người..."></textarea>

                <button type="submit" class="mt-3 w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 rounded-lg text-sm transition-all shadow-md shadow-indigo-200">
                    Gửi đánh giá
                </button>
            </form>
        </div>
    <?php else: ?>
        <div class="bg-slate-50 border border-slate-200 p-4 rounded-xl mb-6 text-center">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-slate-200 text-slate-500 mb-2">
                <i class="fa-solid fa-lock text-lg"></i>
            </div>
            <p class="text-slate-600 text-sm font-medium"><?= $eligibility['msg'] ?></p>
        </div>
    <?php endif; ?>

    <div class="space-y-4">
        <h5 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2 flex justify-between items-center">
            <span>Đánh giá từ cộng đồng</span>
            <span class="bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded text-xs"><?= count($reviews) ?></span>
        </h5>

        <?php if (count($reviews) > 0): ?>
            <div class="max-h-60 overflow-y-auto pr-2 space-y-3 custom-scrollbar">
                <?php foreach ($reviews as $r): ?>
                    <div class="bg-white border border-slate-100 p-3 rounded-xl shadow-sm">
                        <div class="flex justify-between items-start">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs font-bold border border-indigo-100">
                                    <?= substr($r->FullName, 0, 1) ?>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700"><?= htmlspecialchars($r->FullName) ?></p>
                                    <p class="text-[10px] text-slate-400">
                                        <?= date('d/m/Y H:i', strtotime($r->ReviewDate)) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex text-amber-400 text-xs gap-0.5">
                                <?php for($i=1; $i<=5; $i++): ?>
                                    <i class="fa-<?= $i <= $r->Rating ? 'solid' : 'regular' ?> fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if(!empty($r->Comment)): ?>
                            <p class="text-sm text-slate-600 mt-2 pl-10 leading-relaxed break-words">
                                <?= nl2br(htmlspecialchars($r->Comment)) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-6">
                <i class="fa-regular fa-comment-dots text-2xl text-slate-300 mb-2"></i>
                <p class="text-slate-400 text-sm">Chưa có đánh giá nào. Hãy là người đầu tiên!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* CSS cho phần chọn sao */
    .star-rating input:checked ~ label,
    .star-rating label:hover,
    .star-rating label:hover ~ label {
        color: #fbbf24;
    }
    
    /* Scrollbar đẹp hơn cho danh sách review */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>