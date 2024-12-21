<?php
session_start();
include_once "config.php";

if (isset($_POST['incoming_id']) && isset($_POST['last_message_id'])) {
  $outgoing_id = $_SESSION['unique_id'];
  $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
  $last_message_id = intval($_POST['last_message_id']); // Sanitize last message ID

  $output = [];
  $sql = "SELECT * FROM messages 
          WHERE ((incoming_msg_id = {$incoming_id} AND outgoing_msg_id = {$outgoing_id}) 
             OR (incoming_msg_id = {$outgoing_id} AND outgoing_msg_id = {$incoming_id})) 
             AND msg_id > {$last_message_id}
          ORDER BY msg_id";
  $query = mysqli_query($conn, $sql);

  if (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {
      $output[] = $row; // Collect each message
    }
  }

  // Return the response as JSON
  echo json_encode(['messages' => $output]);
}
?>
