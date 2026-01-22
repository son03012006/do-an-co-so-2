<?php
// ===== INCLUDE SIDEBAR ADMIN =====
include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php';
?>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
    rel="stylesheet">

<script>
tailwind.config = {
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Plus Jakarta Sans"', 'sans-serif']
            },
            colors: {
                slate: {
                    50: '#f8fafc',
                    100: '#f1f5f9',
                    800: '#1e293b',
                    900: '#0f172a'
                },
            }
        }
    }
}
</script>

<div class="md:ml-64 p-8 bg-slate-50 min-h-screen text-slate-800">

    <div class="mb-8 flex flex-col md:flex-row justify-between items-end gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Dashboard Overview</h1>
            <p class="text-slate-500 mt-1 text-sm">Báo cáo chi tiết hoạt động thư viện.</p>
        </div>
        <div
            class="bg-white px-4 py-2 rounded-xl shadow-sm border border-slate-100 text-sm font-medium text-slate-600 flex items-center">
            <i class="fa-regular fa-calendar-days mr-2 text-indigo-500"></i> <?= date('l, d F Y') ?>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div
            class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-lg transition-all duration-300">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full opacity-10 blur-xl">
            </div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Kho sách</p>
                    <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalBooks) ?></h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-book-open"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                <span class="bg-emerald-50 px-2 py-0.5 rounded-full">+2.5%</span>
                <span class="text-slate-400 ml-2 font-normal">so với tháng trước</span>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-lg transition-all duration-300">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-full opacity-10 blur-xl">
            </div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Lượt mượn</p>
                    <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalBorrow) ?></h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-bookmark"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                <span class="bg-emerald-50 px-2 py-0.5 rounded-full">+12%</span>
                <span class="text-slate-400 ml-2 font-normal">tăng trưởng</span>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-lg transition-all duration-300">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full opacity-10 blur-xl">
            </div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Lượt trả</p>
                    <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalReturn) ?></h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-rotate-left"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                <span class="bg-emerald-50 px-2 py-0.5 rounded-full">Ổn định</span>
                <span class="text-slate-400 ml-2 font-normal">hiệu suất tốt</span>
            </div>
        </div>

        <div
            class="relative overflow-hidden bg-white p-6 rounded-2xl shadow-sm border border-slate-100 group hover:shadow-lg transition-all duration-300">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-orange-400 to-rose-500 rounded-full opacity-10 blur-xl">
            </div>
            <div class="flex items-center justify-between relative z-10">
                <div>
                    <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Độc giả</p>
                    <h3 class="text-3xl font-bold text-slate-800"><?= number_format($totalUsers) ?></h3>
                </div>
                <div
                    class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl shadow-sm group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-user-group"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-xs font-medium text-emerald-600">
                <span class="bg-emerald-50 px-2 py-0.5 rounded-full">+5</span>
                <span class="text-slate-400 ml-2 font-normal">thành viên mới</span>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="font-bold text-slate-900 text-lg">Phân tích Mượn & Trả</h3>
                <p class="text-slate-400 text-xs mt-1">Dữ liệu thống kê trong 12 tháng qua</p>
            </div>
            <div class="flex gap-2">
                <span class="flex items-center text-xs text-slate-500"><span
                        class="w-2 h-2 rounded-full bg-indigo-500 mr-2"></span>Mượn</span>
                <span class="flex items-center text-xs text-slate-500"><span
                        class="w-2 h-2 rounded-full bg-cyan-400 mr-2"></span>Trả</span>
            </div>
        </div>
        <div class="relative h-80 w-full">
            <canvas id="borrowChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg">Sách đọc nhiều nhất</h3>
                <button class="text-slate-400 hover:text-indigo-600 transition-colors"><i
                        class="fa-solid fa-ellipsis"></i></button>
            </div>
            <div class="p-4 flex-1">
                <ul class="space-y-4">
                    <?php
                    $i = 0;
foreach ($topBooks as $b):
    $i++;
    // Màu sắc Top 1, 2, 3
    $medal = match($i) {
        1 => 'text-yellow-500', 2 => 'text-slate-400', 3 => 'text-orange-400', default => 'text-slate-300 font-normal'
    };
    ?>
                    <li class="flex items-center justify-between group">
                        <div class="flex items-center gap-4">
                            <span class="text-lg font-bold w-6 text-center <?= $medal ?>"><?= $i ?></span>
                            <div class="bg-slate-50 p-2 rounded-lg group-hover:bg-indigo-50 transition-colors">
                                <i class="fa-solid fa-book-bookmark text-slate-400 group-hover:text-indigo-500"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-700 truncate max-w-[200px]"
                                    title="<?= htmlspecialchars($b->BookName) ?>">
                                    <?= htmlspecialchars($b->BookName) ?>
                                </h4>
                                <p class="text-xs text-slate-400">Xu hướng đọc tăng cao</p>
                            </div>
                        </div>
                        <span
                            class="text-sm font-bold text-slate-800 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
                            <?= $b->total ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-50 flex justify-between items-center bg-rose-50/30">
                <h3 class="font-bold text-rose-600 text-lg flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                    </span>
                    Cảnh báo quá hạn
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase tracking-wider font-semibold">
                        <tr>
                            <th class="px-6 py-3">Tên sách</th>
                            <th class="px-6 py-3">Người mượn</th>
                            <th class="px-6 py-3 text-right">Tình trạng</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        <?php if (!empty($overdueBooks)): ?>
                        <?php foreach ($overdueBooks as $o): ?>
                        <tr class="group hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-700">
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-1 h-8 bg-rose-500 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                                    </div>
                                    <span class="truncate max-w-[140px]" title="<?= htmlspecialchars($o->BookName) ?>">
                                        <?= htmlspecialchars($o->BookName) ?>
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                <?= htmlspecialchars($o->FullName) ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center gap-1 text-rose-600 font-bold bg-rose-50 px-2 py-1 rounded-md text-xs">
                                    <i class="fa-regular fa-clock"></i> <?= (int)$o->late_days ?> ngày
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-400">
                                <div
                                    class="mb-3 bg-emerald-50 w-12 h-12 rounded-full flex items-center justify-center mx-auto text-emerald-500">
                                    <i class="fa-solid fa-check text-xl"></i>
                                </div>
                                Tuyệt vời! Không có sách quá hạn.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('borrowChart').getContext('2d');

// Gradient Purple (Mượn)
const gradientBorrow = ctx.createLinearGradient(0, 0, 0, 300);
gradientBorrow.addColorStop(0, 'rgba(99, 102, 241, 0.4)'); // Indigo
gradientBorrow.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

// Gradient Cyan (Trả)
const gradientReturn = ctx.createLinearGradient(0, 0, 0, 300);
gradientReturn.addColorStop(0, 'rgba(34, 211, 238, 0.4)'); // Cyan
gradientReturn.addColorStop(1, 'rgba(34, 211, 238, 0.0)');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        datasets: [{
                label: 'Mượn sách',
                data: <?= json_encode($borrowChart) ?>,
                borderColor: '#6366f1', // Indigo 500
                backgroundColor: gradientBorrow,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#6366f1',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            },
            {
                label: 'Trả sách',
                data: <?= json_encode($returnChart) ?>,
                borderColor: '#22d3ee', // Cyan 400
                backgroundColor: gradientReturn,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#22d3ee',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }, // Ẩn legend mặc định để dùng custom legend ở trên
            tooltip: {
                backgroundColor: '#1e293b',
                padding: 12,
                titleFont: {
                    size: 13
                },
                bodyFont: {
                    size: 13
                },
                cornerRadius: 8,
                displayColors: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    borderDash: [4, 4],
                    color: '#f1f5f9',
                    drawBorder: false
                },
                ticks: {
                    color: '#94a3b8',
                    font: {
                        size: 11
                    }
                }
            },
            x: {
                grid: {
                    display: false
                },
                ticks: {
                    color: '#94a3b8',
                    font: {
                        size: 11
                    }
                }
            }
        },
        interaction: {
            mode: 'index',
            intersect: false,
        },
    }
});
</script>