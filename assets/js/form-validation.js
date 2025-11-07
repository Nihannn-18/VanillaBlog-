
  //FRONTEND FORM VALIDATION (Vanilla JS)
   

document.addEventListener("DOMContentLoaded", () => {

  // Helper function: show error message below input
  const showError = (input, message) => {
    
    const existing = input.parentElement.querySelector(".error-msg");
    if (existing) existing.remove();

    // Create new error element
    const msg = document.createElement("div");
    msg.classList.add("error-msg");
    msg.textContent = message;
    input.insertAdjacentElement("afterend", msg);

    
    input.style.borderColor = "var(--danger)";
  };

  // Helper: clear any previous error
  const clearError = (input) => {
    const msg = input.parentElement.querySelector(".error-msg");
    if (msg) msg.remove();
    input.style.borderColor = "";
  };

  
     //REGISTER FORM VALIDATION
    
  const registerForm = document.querySelector("form input[name='confirm']")?.closest("form");

  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      const username = registerForm.querySelector("input[name='username']");
      const email = registerForm.querySelector("input[name='email']");
      const password = registerForm.querySelector("input[name='password']");
      const confirm = registerForm.querySelector("input[name='confirm']");

      let valid = true;

      // Username - min 3 chars
      if (username.value.trim().length < 3) {
        showError(username, "Username must be at least 3 characters long");
        valid = false;
      } else {
        clearError(username);
      }

      // Email regex
      if (!email.value.match(/^[^@]+@[^@]+\.[a-z]{2,}$/i)) {
        showError(email, "Please enter a valid email address");
        valid = false;
      } else {
        clearError(email);
      }

      // Password - min 6 chars
      if (password.value.length < 6) {
        showError(password, "Password must be at least 6 characters long");
        valid = false;
      } else {
        clearError(password);
      }

      // Confirm password
      if (password.value !== confirm.value) {
        showError(confirm, "Passwords do not match");
        valid = false;
      } else {
        clearError(confirm);
      }

      if (!valid) e.preventDefault();
    });
  }

   //LOGIN FORM VALIDATION

  const loginForm = document.querySelector("form input[name='password']")?.closest("form");
  if (loginForm && !loginForm.querySelector("input[name='confirm']")) {
    loginForm.addEventListener("submit", (e) => {
      const email = loginForm.querySelector("input[name='email']");
      const password = loginForm.querySelector("input[name='password']");
      let valid = true;

      if (!email.value.match(/^[^@]+@[^@]+\.[a-z]{2,}$/i)) {
        showError(email, "Invalid email format");
        valid = false;
      } else {
        clearError(email);
      }

      if (password.value.trim().length === 0) {
        showError(password, "Password is required");
        valid = false;
      } else {
        clearError(password);
      }

      if (!valid) e.preventDefault();
    });
  }

  
      //BLOG POST CREATE / EDIT VALIDATION
     
  const postForm = document.querySelector("form textarea[name='content']")?.closest("form");

  if (postForm) {
    postForm.addEventListener("submit", (e) => {
      const title = postForm.querySelector("input[name='title']");
      const content = postForm.querySelector("textarea[name='content']");
      let valid = true;

      if (title.value.trim().length < 5) {
        showError(title, "Title must be at least 5 characters long");
        valid = false;
      } else {
        clearError(title);
      }

      if (content.value.trim().length < 20) {
        showError(content, "Content must be at least 20 characters long");
        valid = false;
      } else {
        clearError(content);
      }

      if (!valid) e.preventDefault();
    });
  }
});
