<?php
// expenses.php - Expenses Management
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";

// Handle Add Expense Form Submission
$success_msg = $error_msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_expense'])) {
    $title = trim($_POST['title']);
    $category = trim($_POST['category']);
    $amount = trim($_POST['amount']);
    $expense_date = trim($_POST['expense_date']);
    $description = trim($_POST['description']);

    if(!empty($title) && !empty($amount) && !empty($expense_date)) {
        $sql = "INSERT INTO expenses (title, category, amount, expense_date, description) VALUES (?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            mysqli_stmt_bind_param($stmt, "ssdss", $title, $category, $amount, $expense_date, $description);
            if(mysqli_stmt_execute($stmt)){
                $success_msg = "Item successfully recorded!";
            } else {
                $error_msg = "Something went wrong. Please try again.";
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Expenses - EMS</title>
    
    <!-- Custom Circular Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Ccircle cx='50' cy='50' r='48' fill='white' stroke='%230b3b24' stroke-width='3'/%3E%3Ctext x='50%25' y='36%25' font-family='Georgia, serif' font-style='italic' font-weight='bold' font-size='10' fill='%230b3b24' text-anchor='middle' dominant-baseline='middle'%3EEmployee%3C/text%3E%3Ctext x='50%25' y='52%25' font-family='Georgia, serif' font-style='italic' font-weight='bold' font-size='9' fill='%230b3b24' text-anchor='middle' dominant-baseline='middle'%3EManagement%3C/text%3E%3Ctext x='50%25' y='68%25' font-family='Georgia, serif' font-style='italic' font-weight='bold' font-size='10' fill='%230b3b24' text-anchor='middle' dominant-baseline='middle'%3ESystem%3C/text%3E%3C/svg%3E">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .main-banner {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }
        .card-table-header {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container my-4">
        <!-- Navigation Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="index.php" class="btn btn-secondary btn-sm mb-2"><i class="fas fa-arrow-left mr-1"></i> Back to Dashboard</a>
                <h4 class="font-weight-bold text-dark mb-0"><i class="fas fa-receipt mr-2"></i>Company Expenses Tracker</h4>
            </div>
            <div>
                <span class="mr-3 font-weight-bold text-secondary">Hello, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</span>
                <a href="logout.php" class="btn btn-outline-danger btn-sm"><i class="fas fa-sign-out-alt mr-1"></i> Logout</a>
            </div>
        </div>

        <?php if(!empty($success_msg)): ?>
            <div class="alert alert-success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if(!empty($error_msg)): ?>
            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <!-- Add Expense Form Card -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-success text-white font-weight-bold">
                <i class="fas fa-plus-circle mr-1"></i> Record New Expense
            </div>
            <div class="card-body">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label class="font-weight-bold small">Item</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Bottle / Water" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="font-weight-bold small">Category</label>
                            <select name="category" class="form-control">
                                <option value="Beverages">Beverages</option>
                                <option value="Food & Beverages">Food & Beverages</option>
                                <option value="Office Supplies">Office Supplies</option>
                                <option value="Utilities">Utilities</option>
                                <option value="Maintenance & Repairs">Maintenance & Repairs</option>
                                <option value="Communication / Internet">Communication / Internet</option>
                                <option value="Employee Allowance">Employee Allowance</option>
                                <option value="Inventory">Inventory</option>
                                <option value="Transportation">Transportation</option>
                                <option value="Others">Others</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold small">Amount (₱)</label>
                            <input type="number" step="0.01" name="amount" class="form-control" placeholder="0.00" required>
                        </div>
                        <div class="form-group col-md-2">
                            <label class="font-weight-bold small">Date</label>
                            <input type="date" name="expense_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="form-group col-md-2 d-flex align-items-end">
                            <button type="submit" name="add_expense" class="btn btn-success btn-block"><i class="fas fa-save mr-1"></i> Save</button>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12 mb-0">
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Description or notes (Optional)">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Expenses Directory Table -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="font-weight-bold mb-0 text-dark"><i class="fas fa-list mr-2"></i> Expenses List</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="card-table-header">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM expenses ORDER BY expense_date DESC";
                            if($result = mysqli_query($link, $sql)){
                                if(mysqli_num_rows($result) > 0){
                                    $counter = 1;
                                    while($row = mysqli_fetch_array($result)){
                                        echo "<tr>";
                                            echo "<td>" . $counter++ . "</td>";
                                            echo "<td class='font-weight-bold'>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td><span class='badge badge-info px-2 py-1'>" . htmlspecialchars($row['category']) . "</span></td>";
                                            echo "<td class='text-success font-weight-bold'>₱" . number_format($row['amount'], 2) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['expense_date']) . "</td>";
                                            echo "<td class='text-muted small'>" . htmlspecialchars($row['description']) . "</td>";
                                        echo "</tr>";
                                    }
                                    mysqli_free_result($result);
                                } else {
                                    echo "<tr><td colspan='6' class='text-center text-muted py-4'>Wala pang nakatalang expenses.</td></tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-danger py-4'>Error loading expenses.</td></tr>";
                            }
                            mysqli_close($link);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>