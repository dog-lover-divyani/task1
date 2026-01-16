// Element Selectors
const loginForm = document.getElementById("login");
const registerForm = document.getElementById("register");
const toggleBtnBackground = document.getElementById("btn");
const loginBtn = document.querySelectorAll(".toggle-btn")[0];
const registerBtn = document.querySelectorAll(".toggle-btn")[1];

/**
 * Switches the UI to the Registration form
 */
function register() {
    // Hide Login, Show Register
    loginForm.classList.remove("active-form");
    registerForm.classList.add("active-form");
    
    // Move the toggle background
    toggleBtnBackground.style.left = "120px";
    
    // Update button colors
    registerBtn.classList.add("active");
    loginBtn.classList.remove("active");
}

/**
 * Switches the UI to the Login form
 */
function login() {
    // Hide Register, Show Login
    registerForm.classList.remove("active-form");
    loginForm.classList.add("active-form");
    
    // Move the toggle background
    toggleBtnBackground.style.left = "5px";
    
    // Update button colors
    loginBtn.classList.add("active");
    registerBtn.classList.remove("active");
}

/**
 * Toggles password visibility for a specific input
 * @param {string} inputId - The ID of the password input
 * @param {HTMLElement} icon - The font-awesome icon element
 */
function togglePassword(inputId, icon) {
    const passwordInput = document.getElementById(inputId);
    
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        passwordInput.type = "password";
        icon.classList.replace("fa-eye", "fa-eye-slash");
    }
}

// --- Modal Logic ---
const modal = document.getElementById("forgotModal");

function openModal() {
    modal.style.display = "block";
}

function closeModal() {
    modal.style.display = "none";
}

// Close modal if user clicks outside of it
window.onclick = function(event) {
    if (event.target === modal) {
        closeModal();
    }
};

// --- Validation and Submission Logic ---

// Registration Form Handler
registerForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Existing validation
    const pass = document.getElementById('reg-pass').value;
    const confirm = document.getElementById('confirm-pass').value;
    const errorSpan = document.getElementById('reg-error');

    // Get the selected role
    const selectedRole = document.querySelector('input[name="user-role"]:checked').value;

    if (pass.length < 8) {
        errorSpan.innerText = "Password must be at least 8 characters.";
    } else if (pass !== confirm) {
        errorSpan.innerText = "Passwords do not match.";
    } else {
        errorSpan.innerText = "";
        console.log("Role Selected:", selectedRole); // For debugging
        alert(`Registration Successful as a ${selectedRole}!`);
        login(); 
    }
});

// Login Form Handler
loginForm.addEventListener('submit', function(e) {
    e.preventDefault();
    // Logic for authentication goes here
    alert("Logging in...");
});

// Initialize the UI on load
window.onload = login;