<?php
session_start();
if (!isset($_SESSION['unique_id'])) {
    header("location: login.php");
    exit();
}

include_once "header.php";
include_once "php/config.php";

// Get user_id from URL
$user_id = mysqli_real_escape_string($conn, $_GET['user_id']);
$sql = mysqli_query($conn, "SELECT * FROM users WHERE unique_id = {$user_id}");

if (mysqli_num_rows($sql) > 0) {
    $row = mysqli_fetch_assoc($sql);
} else {
    header("location: users.php");
    exit();
}
?>

<body>
  <div class="wrapper">
    <section class="chat-area">
      <header>
        <a href="users.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="php/images/<?php echo htmlspecialchars($row['img']); ?>" alt="User Image">
        <div class="details">
          <span><?php echo htmlspecialchars($row['fname'] . " " . $row['lname']); ?></span>
          <p><?php echo htmlspecialchars($row['status']); ?></p>
        </div>
      </header>

      <div class="chat-box" id="chat-box">
        <!-- Messages will be dynamically loaded here -->
      </div>

      <form action="#" class="typing-area" id="typing-area">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($user_id); ?>" hidden>
        
        <!-- Dropdown for sender's language selection -->
        <!-- <select name="sender_language" class="language-dropdown" id="sender-language-dropdown">
          <option value="en" selected>English</option>
          <option value="es">Spanish</option>
          <option value="fr">French</option>
          <option value="de">German</option>
          <option value="hi">Hindi</option>
          <option value="zh">Chinese</option>
        </select> -->

        <input type="text" name="message" class="input-field" placeholder="Type a message..." autocomplete="off" id="message">
        <button type="submit"><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <!-- Google Translate Widget -->
  <div id="google_translate_element"></div>
  <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
  <script>
    // Initialize Google Translate Widget
    function googleTranslateElementInit() {
      new google.translate.TranslateElement({
        pageLanguage: 'en',
        includedLanguages: 'en,es,fr,de,hi,zh',
        layout: google.translate.TranslateElement.InlineLayout.SIMPLE
      }, 'google_translate_element');
    }
  </script>

  <script src="javascript/chat.js"></script>
</body>
</html>


