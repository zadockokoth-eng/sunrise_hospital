<?php
/**
 * admin.php - Task 6: view, search and delete appointment records.
 * (Same logic as before, new look.)
 */
require "db_connect.php";

// ---- Delete (?delete=ID) ----
if (isset($_GET["delete"])) {
    $deleteId = (int) $_GET["delete"];
    $deleteStmt = $conn->prepare("DELETE FROM appointments WHERE appointment_id = ?");
    $deleteStmt->bind_param("i", $deleteId);
    $deleteStmt->execute();
    $deleteStmt->close();
    header("Location: admin.php?deleted=1");
    exit;
}

// ---- Search (?q=word) by patient name or department ----
$searchTerm = isset($_GET["q"]) ? trim($_GET["q"]) : "";
if ($searchTerm !== "") {
    $stmt = $conn->prepare(
        "SELECT * FROM appointments
         WHERE patient_name LIKE ? OR department LIKE ?
         ORDER BY appointment_date DESC"
    );
    $likeTerm = "%" . $searchTerm . "%";
    $stmt->bind_param("ss", $likeTerm, $likeTerm);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM appointments ORDER BY appointment_date DESC");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin - Manage Appointments</title>
  <link rel="stylesheet" href="../css/style.css" />
</head>
<body>

  <header>
    <div class="logo">Sunrise <span>Community</span> Hospital</div>
    <div class="tagline">Admin area</div>
    <nav>
      <ul>
        <li><a href="../index.html">Back to Website</a></li>
        <li><a href="admin.php" class="active">Appointments</a></li>
        <li><a href="api.php">JSON API</a></li>
      </ul>
    </nav>
  </header>

  <div class="title-strip"><h1>Manage Appointments</h1></div>

  <section class="section">
    <div class="container">
      <?php if (isset($_GET["deleted"])): ?>
        <div class="success-box" style="display:block;">Appointment record deleted.</div>
      <?php endif; ?>

      <form class="search-bar" method="GET" action="admin.php">
        <input type="text" name="q" placeholder="Search by patient name or department"
               value="<?php echo htmlspecialchars($searchTerm); ?>" />
        <button type="submit" class="btn">Search</button>
      </form>

      <div class="table-wrap">
        <table>
          <thead>
            <tr>
              <th>ID</th><th>Patient Name</th><th>National ID</th><th>Gender</th>
              <th>Phone</th><th>Email</th><th>Department</th><th>Date</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
              <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $row["appointment_id"]; ?></td>
                  <td><?php echo htmlspecialchars($row["patient_name"]); ?></td>
                  <td><?php echo htmlspecialchars($row["national_id"]); ?></td>
                  <td><?php echo htmlspecialchars($row["gender"]); ?></td>
                  <td><?php echo htmlspecialchars($row["phone_number"]); ?></td>
                  <td><?php echo htmlspecialchars($row["email"]); ?></td>
                  <td><?php echo htmlspecialchars($row["department"]); ?></td>
                  <td><?php echo htmlspecialchars($row["appointment_date"]); ?></td>
                  <td>
                    <a class="delete-link"
                       href="admin.php?delete=<?php echo $row['appointment_id']; ?>"
                       onclick="return confirm('Delete this appointment?');">Delete</a>
                  </td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="9">No appointments found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <footer>
    <div class="container"><p class="copyright">&copy; 2026 Sunrise Community Hospital. All rights reserved.</p></div>
  </footer>
</body>
</html>
<?php $conn->close(); ?>
