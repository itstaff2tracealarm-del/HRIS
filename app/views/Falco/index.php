<?php include __DIR__ . '/../../includes/header.php'; ?>

<style>
    body { background:#f4f6f9; }

    .page-layout {
        display:flex;
        padding:20px;
        gap:20px;
    }

    .main-wrapper { flex-grow:1; }

    .content-area {
        background:white;
        border-radius:25px;
        padding:30px;
        min-height:90vh;
        box-shadow:0 5px 15px rgba(0,0,0,0.05);
    }

    .box{
        background:white;
        border-radius:16px;
        padding:20px;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);
    }

    .sync-btn{
        background:#2f66d0;
        color:white;
        border:none;
        padding:10px 20px;
        border-radius:10px;
        font-weight:600;
    }

    /* TABLE */
    .table-modern {
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .table-modern tbody tr {
        background:white;
        box-shadow:0 4px 12px rgba(0,0,0,0.05);
        border-radius:12px;
    }

    .table-modern td {
        border:none !important;
        padding:14px;
    }
</style>

<div class="page-layout">
    <?php include __DIR__ . '/../../includes/left.php'; ?>

    <div class="main-wrapper">
        <main class="content-area">
            <h3 class="fw-bold mb-4">Falco Attendance</h3>

            <div class="box d-flex justify-content-between align-items-center">
                <h5>LiveTransaction Logs</h5>
                <button onclick="syncFalco()" class="sync-btn">🔄 Sync</button>
            </div>

            <div class="box">
                <table class="table table-modern text-center" id="falcoTable">
                    <thead>
                        <tr>
                            <th>Card No</th>
                            <th>Employee Name</th>
                            <th>Time In</th>
                            <th>Time Out</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
    function loadData() {
        fetch('syncFalco.php?a=getFalcoData') // 🔥 IMPORTANT
        .then(res => res.json())
        .then(data => {

            let tbody = document.querySelector('#falcoTable tbody');
            tbody.innerHTML = '';

            if (!data.length) {
                tbody.innerHTML = `<tr><td colspan="3">No Data</td></tr>`;
                return;
            }

            data.forEach(row => {
                tbody.innerHTML += `
                    <tr>
                        <td>${row.card_no}</td>
                        <td>${row.name}</td>
                        <td>${row.time_in ?? '-'}</td>
                        <td>${row.time_out ?? '-'}</td>
                        
                    </tr>
                `;
            });
        });
    }

    function syncFalco() {
        fetch('syncFalco.php?a=sync')
        .then(res => res.json())
        .then(data => {
            alert("Synced: " + data.count);
            loadData();
        });
    }

    // AUTO
    loadData();
    setInterval(loadData, 5000);

</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>