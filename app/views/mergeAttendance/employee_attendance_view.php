<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-slate-50 font-sans">

<div class="max-w-6xl mx-auto p-6 lg:p-10">
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-widest text-slate-400">
        <a href="mergeAttendance.php?a=login" class="hover:text-red-600">Attendance List</a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 font-bold">Individual DTR</span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight uppercase italic">Daily Time Record</h1>
            <div class="flex items-center gap-3 mt-2">
                <div class="bg-red-600 text-white text-[10px] font-black px-2 py-0.5 rounded">ID: <?= $card_id ?></div>
                <h2 class="text-lg font-bold text-slate-600 uppercase italic">
                    <?= ($employee['first_name'] ?? 'UNKNOWN') . ' ' . ($employee['last_name'] ?? 'EMPLOYEE') ?>
                </h2>
            </div>
        </div>

     <form method="GET" action="mergeAttendance.php" class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-wrap gap-4 items-end">
    <input type="hidden" name="a" value="view_attendance">
    
    <div class="flex-1 min-w-[250px]">
        <label class="block text-[9px] font-black text-slate-400 uppercase mb-1 tracking-widest">Select Employee</label>
        <select name="card_id" onchange="this.form.submit()" class="w-full text-xs font-bold p-2.5 bg-slate-50 border border-slate-100 rounded-lg outline-none focus:border-red-600 appearance-none cursor-pointer">
            <option value="">-- Select Employee --</option>
            <?php foreach ($employeeList as $id => $name): ?>
                <option value="<?= $id ?>" <?= ($card_id == $id) ? 'selected' : '' ?>>
                    <?= strtoupper($name) ?> (<?= $id ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div>
        <label class="block text-[9px] font-black text-slate-400 uppercase mb-1 tracking-widest">From</label>
        <input type="date" name="start_date" value="<?= $start_date ?>" class="text-xs font-bold p-2.5 bg-slate-50 border border-slate-100 rounded-lg outline-none">
    </div>
    
    <div>
        <label class="block text-[9px] font-black text-slate-400 uppercase mb-1 tracking-widest">To</label>
        <input type="date" name="end_date" value="<?= $end_date ?>" class="text-xs font-bold p-2.5 bg-slate-50 border border-slate-100 rounded-lg outline-none">
    </div>

    <button type="submit" class="px-6 py-2.5 bg-slate-900 text-white rounded-lg text-[10px] font-black uppercase hover:bg-red-600 transition flex items-center gap-2">
        <i data-lucide="filter" class="w-3 h-3"></i> Filter
    </button>
</form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="flex items-center gap-2 mb-4 bg-slate-200/50 p-1 rounded-xl w-fit">
    <?php 
        $filters = [
            'MERGED' => 'All Logs',
            'FIELD'  => 'Field Only',
            'OFFICE' => 'Office Only'
        ];
        foreach ($filters as $key => $label): 
            $isActive = ($source_filter === $key);
            $url = "mergeAttendance.php?a=view_attendance&card_id=$card_id&start_date=$start_date&end_date=$end_date&source_filter=$key";
    ?>
        <a href="<?= $url ?>" 
           class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all 
           <?= $isActive ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>
        <div class="bg-white p-5 rounded-2xl border-l-4 border-slate-900 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Days with Logs</p>
            <p class="text-2xl font-black text-slate-800"><?= count(array_unique(array_column($logs, 'date'))) ?></p>
        </div>
        <div class="bg-white p-5 rounded-2xl border-l-4 border-red-600 shadow-sm">
            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Latest Log Date</p>
            <p class="text-2xl font-black text-slate-800"><?php 
            // I-check muna kung hindi empty ang $logs bago i-access ang index 0
            if (!empty($logs) && isset($logs[0]['date'])) {
                echo date('M d, Y', strtotime($logs[0]['date']));
            } else {
                echo "No Records";
            }
        ?>   </p>
        </div>
        <button onclick="window.print()" class="bg-slate-100 border-2 border-dashed border-slate-200 p-5 rounded-2xl flex items-center justify-center gap-3 hover:bg-slate-200 transition">
            <i data-lucide="printer" class="w-5 h-5 text-slate-400"></i>
            <span class="text-[10px] font-black uppercase">Print DTR Report</span>
        </button>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                    <th class="px-8 py-5 italic">Date Entry</th>
                    <th class="px-8 py-5 text-center">Time In</th>
                    <th class="px-8 py-5 text-center">Time Out</th>
                    <th class="px-8 py-5 text-right">Source</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if (!empty($logs)): foreach ($logs as $row): ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-8 py-4 font-black text-slate-800 text-sm italic">
                        <?= date('M d, Y (D)', strtotime($row['date'])) ?>
                    </td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-lg font-mono font-bold text-xs border border-emerald-100 italic">
                            <?= $row['time_in'] ?>
                        </span>
                    </td>
                    <td class="px-8 py-4 text-center">
                        <span class="px-3 py-1.5 bg-orange-50 text-orange-700 rounded-lg font-mono font-bold text-xs border border-orange-100 italic">
                            <?= $row['time_out'] ?>
                        </span>
                    </td>
                    <td class="px-8 py-4 text-right">
                        <span class="text-[9px] font-black bg-slate-100 text-slate-500 px-2 py-1 rounded italic">
                            <?= $row['source'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-slate-300 italic font-bold uppercase tracking-widest">No attendance records found for this period</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>lucide.createIcons();</script>
</body>
</html>