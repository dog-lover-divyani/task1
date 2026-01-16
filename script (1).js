const loginForm = document.getElementById("login");
const registerForm = document.getElementById("register");
const toggleBtnBackground = document.getElementById("btn");
const loginBtn = document.querySelectorAll(".toggle-btn")[0];
const registerBtn = document.querySelectorAll(".toggle-btn")[1];

function register() {
    loginForm.classList.remove("active-form");
    registerForm.classList.add("active-form");
    toggleBtnBackground.style.left = "120px";
    registerBtn.classList.add("active");
    loginBtn.classList.remove("active");
}

function login() {
    registerForm.classList.remove("active-form");
    loginForm.classList.add("active-form");
    toggleBtnBackground.style.left = "5px";
    loginBtn.classList.add("active");
    registerBtn.classList.remove("active");
}

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

// Modal Logic
const modal = document.getElementById("forgotModal");
function openModal() { modal.style.display = "block"; }
function closeModal() { modal.style.display = "none"; }
window.onclick = function(event) { if (event.target === modal) closeModal(); };

// Client-side Validation
registerForm.addEventListener('submit', function(e) {
    const pass = document.getElementById('reg-pass').value;
    const confirm = document.getElementById('confirm-pass').value;

    if (pass !== confirm) {
        e.preventDefault();
        alert("Passwords do not match!");
    }
});