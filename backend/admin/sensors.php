<?php require '../resources/php/db.php'; ?> <!-- Correct path to db.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sensor Admin Panel</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
      background-color: #f9f9f9;
    }
    h1, h2 {
      color: #333;
    }
    form {
      margin-bottom: 30px;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px #ddd;
    }
    input, textarea, button {
      display: block;
      margin-bottom: 10px;
      padding: 8px;
      width: 300px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    button {
      background-color: #2196F3;
      color: white;
      border: none;
      cursor: pointer;
    }
    button:hover {
      background-color: #0b7dda;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 10px #ddd;
    }
    th, td {
      padding: 10px;
      text-align: center;
      border-bottom: 1px solid #ddd;
    }
    th {
      background-color: #f2f2f2;
    }
    .btn {
      padding: 6px 10px;
      border-radius: 4px;
      text-decoration: none;
      color: white;
      margin: 2px;
      display: inline-block;
    }
    .on {
      background-color: #4CAF50;
    }
    .off {
      background-color: #f44336;
    }
    .delete {
      background-color: #777;
    }
  </style>
</head>
<body>

  <h1>Sensor Admin Panel</h1>

  <!-- Add Sensor Form -->
  <h2>Add New Sensor</h2>
  <form id="addSensorForm">
    <input type="text" name="name" placeholder="Sensor Name" required>
    <input type="text" name="latitude" placeholder="Latitude" required>
    <input type="text" name="longitude" placeholder="Longitude" required>
    <textarea name="description" placeholder="Description"></textarea>
    <button type="submit">Add Sensor</button>
  </form>

  <script>
  document.getElementById('addSensorForm').addEventListener('submit', function(e) {
    e.preventDefault(); // prevent full page reload

    const formData = new FormData(this);
    formData.append('action', 'add_sensor');

    fetch('../resources/php/adminsensors.php', {
      method: 'POST',
      body: formData
    })
    .then(res => res.text())
    .then(data => {
      alert(data); // ✅ Show success popup
      location.reload(); // 🔁 Refresh to show updated table
    })
    .catch(err => {
      alert("Error submitting form.");
      console.error(err);
    });
  });
  </script>

  <!-- Display Sensors -->
  <h2>Sensor List</h2>
  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Latitude</th>
        <th>Longitude</th>
        <th>Description</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = $conn->query("SELECT * FROM sensors ORDER BY created_at DESC");
      while ($row = $result->fetch_assoc()) {
        $status = $row['is_active'] ? 'ON' : 'OFF';
        $toggleText = $row['is_active'] ? 'Turn OFF' : 'Turn ON';
        $toggleClass = $row['is_active'] ? 'off' : 'on';

        echo "<tr>
          <td>{$row['name']}</td>
          <td>{$row['latitude']}</td>
          <td>{$row['longitude']}</td>
          <td>{$row['description']}</td>
          <td>$status</td>
          <td>{$row['created_at']}</td>
          <td>
            <a class='btn $toggleClass' href='../resources/php/adminsensors.php?toggle_id={$row['id']}&current={$row['is_active']}'>$toggleText</a>
            <a class='btn delete' href='../resources/php/adminsensors.php?delete_id={$row['id']}' onclick=\"return confirm('Delete this sensor?')\">Delete</a>
          </td>
        </tr>";
      }
      ?>
    </tbody>
  </table>

</body>
</html>
