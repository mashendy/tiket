<?php
// Include your database connection file
include('config.php');

// Check if the 'id' parameter is present in the URL
if (isset($_GET['id_member'])) {
    // Get the 'id' parameter value from the URL
    $id = $_GET['id_member'];

    // Prepare the SQL query to delete the record with the given ID
    $sql = "DELETE FROM member WHERE id_member = ?";
    
    // Initialize the prepared statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameter to the query
        $stmt->bind_param("i", $id);
        
        // Execute the query
        if ($stmt->execute()) {
            // Successfully deleted
            echo "Record deleted successfully.";
        } else {
            // Failed to delete
            echo "Error deleting record: " . $conn->error;
        }

        // Close the prepared statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "No ID provided.";
}

// Close the database connection
$conn->close();
?>
