<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Kết quả báo cáo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
    /* === CSS CHUYÊN DỤNG CHO MÁY IN === */
    @media print {

        /* Ẩn các thành phần không cần thiết */
        .no-print,
        .sidebar,
        header,
        footer {
            display: none !important;
        }

        /* Reset màu nền về trắng để tiết kiệm mực */
        body,
        .bg-slate-50 {
            background-color: white !important;
            background: white !important;
            color: black !important;
        }

        /* Căn chỉnh khổ giấy */
        @page {
            margin: 2cm;
        }

        /* Hiển thị viền bảng rõ ràng */
        table,
        th,
        td {
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }

        /* Bóng đổ tắt hết */
        .shadow-xl,
        .shadow-md {
            box-shadow: none !important;
        }
    }
    </style>
</head>

<body class="bg-slate-50 font-sans text-slate-800">

    <div class="flex min-h-screen">
        <div class="no-print">
            <?php include __DIR__ . '/../layouts/layoutsadmin/sidebaradmin.php'; ?>
        </div>

        <div class="flex-1 p-8 md:ml-64">

            <div class="no-print flex justify-between items-center mb-6">
                <a href="index.php?c=report" class="text-indigo-600 hover:underline">
                    <i class="fa fa-arrow-left"></i> Quay lại
                </a>
                <button onclick="window.print()"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-lg flex items-center gap-2 font-bold transition">
                    <i class="fa fa-print"></i> In Báo Cáo
                </button>
            </div>

            <div class="bg-white p-10 rounded-xl shadow-xl border border-gray-200" id="print-area">

                <div class="text-center mb-8 border-b-2 border-gray-800 pb-4">
                    <h1 class="text-2xl font-bold uppercase">Thư Viện Số</h1>
                    <h2 class="text-xl mt-2"><?= $title ?></h2>
                    <p class="text-sm text-gray-500 mt-1">Ngày xuất báo cáo: <?= date('d/m/Y H:i') ?></p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-4 border border-gray-300">STT</th>
                                <?php if ($reportType == 'overdue'): ?>
                                <th class="py-3 px-4 border border-gray-300">Mã SV</th>
                                <th class="py-3 px-4 border border-gray-300">Tên SV</th>
                                <th class="py-3 px-4 border border-gray-300">Tên Sách</th>
                                <th class="py-3 px-4 border border-gray-300">Hạn Trả</th>
                                <th class="py-3 px-4 border border-gray-300 text-red-600">Quá Hạn (Ngày)</th>
                                <?php else: ?>
                                <th class="py-3 px-4 border border-gray-300">Tên Sách</th>
                                <th class="py-3 px-4 border border-gray-300">ISBN</th>
                                <th class="py-3 px-4 border border-gray-300">Ngày Mượn</th>
                                <th class="py-3 px-4 border border-gray-300">Ngày Trả</th>
                                <th class="py-3 px-4 border border-gray-300">Trạng Thái</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            <?php if (!empty($rows)): ?>
                            <?php $cnt = 1;
                                foreach ($rows as $row): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="py-3 px-4 border border-gray-300 text-center font-bold"><?= $cnt++ ?></td>

                                <?php if ($reportType == 'overdue'): ?>
                                <td class="py-3 px-4 border border-gray-300"><?= $row->StudentID ?></td>
                                <td class="py-3 px-4 border border-gray-300"><?= $row->FullName ?></td>
                                <td class="py-3 px-4 border border-gray-300"><?= $row->BookName ?></td>
                                <td class="py-3 px-4 border border-gray-300">
                                    <?= date('d/m/Y', strtotime($row->DueDate)) ?></td>
                                <td class="py-3 px-4 border border-gray-300 text-red-600 font-bold text-center">
                                    <?= $row->days_overdue ?>
                                </td>
                                <?php else: ?>
                                <td class="py-3 px-4 border border-gray-300"><?= $row->BookName ?></td>
                                <td class="py-3 px-4 border border-gray-300"><?= $row->ISBNNumber ?></td>
                                <td class="py-3 px-4 border border-gray-300">
                                    <?= date('d/m/Y', strtotime($row->IssuesDate)) ?></td>
                                <td class="py-3 px-4 border border-gray-300">
                                    <?= $row->ReturnDate ? date('d/m/Y', strtotime($row->ReturnDate)) : 'Chưa trả' ?>
                                </td>
                                <td class="py-3 px-4 border border-gray-300">
                                    <?php if ($row->ReturnStatus == 1): ?>
                                    <span class="text-green-600 font-bold">Đã trả</span>
                                    <?php elseif (date('Y-m-d') > $row->DueDate): ?>
                                    <span class="text-red-600 font-bold">Quá hạn</span>
                                    <?php else: ?>
                                    <span class="text-blue-600">Đang mượn</span>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="6" class="py-4 text-center text-gray-500">Không có dữ liệu nào.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-12 flex justify-between px-8 invisible print:visible">
                    <div class="text-center">
                        <p class="font-bold">Người lập biểu</p>
                        <p class="italic text-xs">(Ký và ghi rõ họ tên)</p>
                    </div>
                    <div class="text-center">
                        <p class="font-bold">Xác nhận của thủ thư</p>
                        <p class="italic text-xs">(Ký và ghi rõ họ tên)</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>