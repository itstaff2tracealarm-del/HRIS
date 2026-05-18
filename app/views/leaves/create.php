<?php include __DIR__ . '/../../includes/header.php'; ?>

<style>
    body { background-color: #f8f9fa; margin: 0; padding: 0; }
    
    /* Ito ang nagpapadikit sa Sidebar at Content */
    .page-layout {
        display: flex;
        align-items: flex-start;
        padding: 20px;
        gap: 20px;
        width: 100%;
    }

    .main-wrapper {
        flex-grow: 1;
        min-width: 0; 
    }

    .content-area { 
        background: white;
        border-radius: 25px; 
        padding: 30px; 
        min-height: 90vh; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .breadcrumb-ui { font-size: 0.90rem; font-weight: 500; color: #6c757d; }
    .breadcrumb-ui a { text-decoration: none; color: #6c757d; transition: 0.2s; }
    .breadcrumb-ui a:hover { color: #e32133; }
    .breadcrumb-ui span { font-weight: 600; color: #1c1e1b; }
</style>

<div class="page-layout">
    
    <?php include __DIR__ . '/../../includes/left.php'; ?>

    <div class="main-wrapper">
      <main class="content-area">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm no-print" style="border: 1px solid #f1f1f1;">
          <nav class="breadcrumb-ui">
            <a href="dashboard.php">Dashboard</a> / 
            <a href="leaves.php">Leave</a> /
            <span>Add New Leave</span>
          </nav>
          <a href="leaves.php?a=index" class="btn btn-outline-secondary btn-sm"> <i class="fas fa-arrow-left"></i> Back </a>
        </div>

      <h2 class="mb-4">Add Leave</h2>
      <div class="d-flex justify-content-between align-items-center mt-4 mb-3 border-bottom pb-2"></div>

      <?php if (!empty($errorMsg)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
      <?php endif; ?>

      <form method="POST" action="leaves.php?a=store">
        <div class="mb-3">
          <label class="form-label">Employee</label>
          <select name="employee_id" class="form-select" required>
            <option value="">Select Employee</option>
            <?php foreach ($employees as $emp): ?>
              <option value="<?= $emp['id'] ?>">
                <?= $emp['employee_code'] ?> - <?= $emp['first_name'] ?> <?= $emp['last_name'] ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Leave Type</label>
          <select name="leave_type" class="form-select">
            <option value="casual">Casual</option>
            <option value="sick">Sick</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" class="form-control" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reason</label>
          <textarea name="reason" class="form-control"></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
          </select>
        </div>

        <button class="btn btn-primary">Submit</button>
        <a href="leaves.php?a=index" class="btn btn-secondary ms-2">Cancel</a>

      </form>
    </main>
  </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
