<?php
  session_start();
  include_once "config.php";

  if (isset($_POST['incoming_id']) && isset($_POST['message'])) {
    $outgoing_id = $_SESSION['unique_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert the new message into the database
    $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg) VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')");

    if ($sql) {
      echo "Message sent!";
    }
  }
?>

