<?php
include('db.php');  // Include the database connection file

// Initialize variables for form handling
$name = '';
$email = '';
$update = false; // Boolean to track if we're in "Edit" mode
$id = 0;         // ID to track the user when editing or deleting records

// Insert new user
if (isset($_POST['save'])) {  // Check if the save button was clicked
    $name = $_POST['name'];   // Get the name input from the form
    $email = $_POST['email']; // Get the email input from the form

    $sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')"; // Prepare SQL query to insert a new user
    if (mysqli_query($conn, $sql)) {  // Execute the query and check if it was successful
        $_SESSION['message'] = "Record has been saved!"; // Set a success message in the session
        header('location: index.php');  // Redirect back to the index page
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($conn); // In case of an error, display it
    }
}

// Delete a user
if (isset($_GET['delete'])) {   // Check if the delete button was clicked
    $id = $_GET['delete'];      // Get the user ID from the URL
    $sql = "DELETE FROM users WHERE id=$id";  // Prepare the SQL query to delete the user by ID
    if (mysqli_query($conn, $sql)) { // Execute the delete query
        $_SESSION['message'] = "Record has been deleted!"; // Set a success message
        header('location: index.php'); // Redirect back to the index page
    }
}

// Edit a user
if (isset($_GET['edit'])) {    // Check if the edit button was clicked
    $id = $_GET['edit'];       // Get the user ID from the URL
    $update = true;            // Set the update flag to true, indicating we're in "Edit" mode
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id"); // Fetch the user from the database

    if ($result) {             // Check if the result is not empty
        $row = mysqli_fetch_array($result);  // Fetch the result as an associative array
        $name = $row['name'];   // Set the name variable with the user's name
        $email = $row['email']; // Set the email variable with the user's email
    }
}

// Update user information
if (isset($_POST['update'])) {   // Check if the update button was clicked
    $id = $_POST['id'];          // Get the user ID from the hidden form field
    $name = $_POST['name'];      // Get the updated name from the form
    $email = $_POST['email'];    // Get the updated email from the form

    $sql = "UPDATE users SET name='$name', email='$email' WHERE id=$id"; // Prepare SQL query to update user info
    if (mysqli_query($conn, $sql)) {  // Execute the query
        $_SESSION['message'] = "Record has been updated!"; // Set a success message
        header('location: index.php');  // Redirect back to the index page
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP CRUD App</title>
    <link rel="stylesheet" href="style.css"> <!-- Link to external CSS -->
</head>
<body>
    <?php if (isset($_SESSION['message'])): ?>
        <div class="msg">
            <?php
                echo $_SESSION['message'];
                unset($_SESSION['message']); // Clear the message after displaying it
            ?>
        </div>
    <?php endif ?>

    <form action="index.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $id; ?>"> <!-- Hidden ID field for editing -->
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" value="<?php echo $name; ?>" required> <!-- Name input -->
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo $email; ?>" required> <!-- Email input -->
        </div>
        <div class="input-group">
            <?php if ($update == true): ?>
                <button class="btn" type="submit" name="update" style="background-color: orange;">Update</button> <!-- Update button -->
            <?php else: ?>
                <button class="btn" type="submit" name="save">Save</button> <!-- Save button -->
            <?php endif ?>
        </div>
    </form>

    <?php
    $result = mysqli_query($conn, "SELECT * FROM users"); // Fetch all users from the database
    ?>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <?php while ($row = mysqli_fetch_assoc($result)): ?> <!-- Loop through each user -->
            <tr>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td>
                    <a href="index.php?edit=<?php echo $row['id']; ?>" class="edit_btn">Edit</a> <!-- Edit button -->
                    <a href="index.php?delete=<?php echo $row['id']; ?>" class="del_btn">Delete</a> <!-- Delete button -->
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
