<?php include __DIR__ . '/../../includes/header.php'; ?>
<style>
body { background:#f4f6f9; }
.page-layout {
  display:flex;
  padding:20px;
  gap:20px;
}

.content-area {
  background:white;
  border-radius:20px;
  padding:25px;
  width:100%;
  box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.table-modern {
  border-collapse: separate;
  border-spacing: 0 10px;
  width:100%;
}

.table-modern tbody tr {
  background:white;
  box-shadow:0 3px 10px rgba(0,0,0,0.05);
  border-radius:10px;
}

.table-modern td {
  padding:12px;
  border:none !important;
}

.match { background:#dcfce7; }       /* green */
.missing { background:#fee2e2; }     /* red */
.mismatch { background:#fef9c3; }    /* yellow */

.badge {
  padding:4px 8px;
  border-radius:8px;
  font-size:12px;
}

.badge-ok { background:#16a34a; color:white; }
.badge-miss { background:#dc2626; color:white; }
.badge-warn { background:#ca8a04; color:white; }
</style>

<div class="page-layout">

<?php include __DIR__ . '/../../includes/left.php'; ?>
<div class="content-area">
<h3>Falco vs Local Attendance</h3>
<table class="table table-modern text-center" id="compareTable">
<thead>
<tr>
  <th>Card</th>
  <th>Name</th>
  <th>Local IN</th>
  <th>Falco IN</th>
  <th>Local OUT</th>
  <th>Falco OUT</th>
  <th>Status</th>
</tr>
</thead>
<tbody></tbody>
</table>

</div>
</div>

<script>

function loadCompare() {
  fetch('employees.php?a=getMergedData')
  .then(res => res.json())
  .then(data => {
    let tbody = document.querySelector('#compareTable tbody');
    tbody.innerHTML = '';
    data.forEach(r => {
      let status = '';
      let rowClass = '';
      // 🔥 LOGIC
      if (!r.local_in && !r.falco_in) {
        status = '<span class="badge badge-miss">NO IN</span>';
        rowClass = 'missing';
      }
      else if (!r.local_out && !r.falco_out) {
        status = '<span class="badge badge-miss">NO OUT</span>';
        rowClass = 'missing';
      }
      else if (r.local_in !== r.falco_in || r.local_out !== r.falco_out) {
        status = '<span class="badge badge-warn">MISMATCH</span>';
        rowClass = 'mismatch';
      }
      else {
        status = '<span class="badge badge-ok">MATCH</span>';
        rowClass = 'match';
      }
      tbody.innerHTML += `
        <tr class="${rowClass}">
          <td>${r.card_no}</td>
          <td>${r.name}</td>
          <td>${r.local_in ?? '-'}</td>
          <td>${r.falco_in ?? '-'}</td>
          <td>${r.local_out ?? '-'}</td>
          <td>${r.falco_out ?? '-'}</td>
          <td>${status}</td>
        </tr>
      `;
    });
  });
} 
// 🔥 AUTO LOAD
loadCompare();
setInterval(loadCompare, 5000);
</script>
<?php include __DIR__ . '/../../includes/footer.php'; ?>