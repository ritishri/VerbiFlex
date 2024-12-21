(() => {
  const form = document.querySelector("#typing-area");
  const inputField = form.querySelector(".input-field");
  const sendButton = form.querySelector("button");
  const chatBox = document.querySelector("#chat-box");
  const languageDropdown = document.getElementById("language-dropdown");
  
  const incomingId = form.querySelector(".incoming_id").value;
  let lastMessageId = 0; // To track the last loaded message

  // Function to send the message
  form.onsubmit = (e) => {
    e.preventDefault();
    const message = inputField.value.trim();

    if (message) {
      const xhr = new XMLHttpRequest();
      xhr.open("POST", "php/insert-chat.php", true);
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
      xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
          inputField.value = ""; // Clear the input field
          loadMessages(); // Load the new messages immediately
        }
      };
      xhr.send(`incoming_id=${incomingId}&message=${message}`);
    }
  };

  // Function to load new messages
  function loadMessages() {
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "php/get-chat.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = () => {
      if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
        const response = JSON.parse(xhr.responseText); // Assume JSON response
        const messages = response.messages;
        
        // Append only new messages
        messages.forEach((message) => {
          if (message.msg_id > lastMessageId) {
            appendMessage(message); // Append only new messages
            lastMessageId = message.msg_id; // Update the last message ID
          }
        });

        scrollToBottom();
        applyGoogleTranslate(); // Apply translation only on new messages
      }
    };
    xhr.send(`incoming_id=${incomingId}&last_message_id=${lastMessageId}`);
  }

  // Function to append a new message to the chat box
  function appendMessage(message) {
    let chatHtml = '';
    if (message.outgoing_msg_id === incomingId) {
      chatHtml = `<div class="chat outgoing"><div class="details"><p>${message.msg}</p></div></div>`;
    } else {
      chatHtml = `<div class="chat incoming">
                    <div class="details"><p>${message.msg}</p></div>
                  </div>`;
    }
    chatBox.insertAdjacentHTML("beforeend", chatHtml); // Append to the chat box
  }

  // Automatically load new messages every second
  setInterval(loadMessages, 1000);

  function scrollToBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
  }

  // Handle language change
  let currentLanguage = "en"; // Default language
  languageDropdown.addEventListener("change", () => {
    const selectedLanguage = languageDropdown.value;
    if (selectedLanguage !== currentLanguage) {
      const translateElement = document.querySelector(".goog-te-combo");
      if (translateElement) {
        translateElement.value = selectedLanguage;
        translateElement.dispatchEvent(new Event("change"));
      }
      currentLanguage = selectedLanguage;
    }
  });

  function applyGoogleTranslate() {
    const translateElement = document.querySelector(".goog-te-combo");
    if (translateElement && currentLanguage !== "en") {
      translateElement.value = currentLanguage;
      translateElement.dispatchEvent(new Event("change"));
    }
  }
})();
