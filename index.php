<?php
include 'db.php';
session_start();

$message = "";
$msgClass = "";
$step = 1;
$resetEmail = "";
$fetchedQuestion = "";

/* ---------- REGISTER ---------- */
if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['user-role'];
    $question = mysqli_real_escape_string($conn, $_POST['security_question']);
    $answer = password_hash(strtolower(trim($_POST['security_answer'])), PASSWORD_DEFAULT);

    $checkEmail = "SELECT id FROM users WHERE email='$email'";
    if ($conn->query($checkEmail)->num_rows > 0) {
        $message = "Email already exists!";
        $msgClass = "error";
    } else {
        $sql = "INSERT INTO users (full_name,email,password,role,security_question,security_answer)
                VALUES ('$name','$email','$password','$role','$question','$answer')";
        if ($conn->query($sql)) {
            $message = "Registration successful! Please log in.";
            $msgClass = "success";
        }
    }
}

/* ---------- LOGIN ---------- */
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            header("Location: welcome.php");
            exit();
        } else {
            $message = "Invalid password.";
            $msgClass = "error";
        }
    } else {
        $message = "No account found.";
        $msgClass = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Auth</title>
<link rel="stylesheet" href="style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <?php if($message != ""): ?>
        <div id="toast" class="toast-notification <?php echo $msgClass; ?>">
            <div class="toast-content">
                <i class="fa-solid <?php echo ($msgClass == 'success') ? 'fa-circle-check' : 'fa-circle-exclamation'; ?>"></i>
                <span><?php echo $message; ?></span>
            </div>
            <div class="progress-bar"></div>
        </div>
    <?php endif; ?>

    <div class="auth-container">
        <div class="form-box">
            <div class="button-box">
                <div id="btn"></div>
                <button type="button" class="toggle-btn active" onclick="login()">Log In</button>
                <button type="button" class="toggle-btn" onclick="register()">Register</button>
            </div>

            <form id="login" method="POST" action="index.php" class="input-group active-form">
                <input type="email" name="email" class="input-field" placeholder="Email Address" required>
                <div class="password-wrapper">
                    <input type="password" name="password" class="input-field" id="login-pass" placeholder="Enter Password" required>
                    <i class="fa-solid fa-eye-slash toggle-eye" onclick="togglePassword('login-pass', this)"></i>
                </div>
                <div class="forgot-link">
                    <a href="#" onclick="openModal()">Forgot Password?</a>
                </div>
                <div class="checkbox-group">
                    <input type="checkbox" id="rem-pass" class="check-box">
                    <label for="rem-pass">Remember Password</label>
                </div>
                <button type="submit" name="login" class="submit-btn">Log In</button>
                <div class = "oauth-divider">
                    <span>---------------or</span>
                </div>
                <a href="google-login.php" class="submit-btn" style="text-align:center;">
                    <i class="fa-brands fa-google"></i> Continue with Google
                </a>
            </form>

            <form id="register" method="POST" action="index.php" class="input-group">
                <input type="text" name="full_name" class="input-field" placeholder="Full Name" required>
                <input type="email" name="email" class="input-field" placeholder="Email Address" required>
                
                <div class="password-wrapper">
                    <input type="password" name="password" class="input-field" id="reg-pass" placeholder="Password" required>
                    <i class="fa-solid fa-eye-slash toggle-eye" onclick="togglePassword('reg-pass', this)"></i>
                </div>

                <div class="password-wrapper">
                    <input type="password" class="input-field" id="confirm-pass" placeholder="Confirm Password" required>
                    <i class="fa-solid fa-eye-slash toggle-eye" onclick="togglePassword('confirm-pass', this)"></i>
                </div>

                <div class="role-container">
                    <p>Register as:</p>
                    <div class="role-selection">
                        <input type="radio" name="user-role" id="candidate" value="candidate" checked>
                        <label for="candidate" class="role-card">
                            <i class="fa-solid fa-user-tie"></i>
                            <span>Candidate</span>
                        </label>
                        <input type="radio" name="user-role" id="employer" value="employer">
                        <label for="employer" class="role-card">
                            <i class="fa-solid fa-building"></i>
                            <span>Employer</span>
                        </label>
                    </div>
                </div>

                <div class="role-container">
                    <p>Security Verification:</p>
                    <select name="security_question" class="input-field" required>
                        <option value="" disabled selected>Select a Security Question</option>
                        <option value="What is your pet's name?">What is your pet's name?</option>
                        <option value="What was your first car?">What was your first car?</option>
                        <option value="What city were you born in?">What city were you born in?</option>
                    </select>
                    <input type="text" name="security_answer" class="input-field" placeholder="Your Answer" required>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms" class="check-box" required>
                    <label for="terms">I agree to terms & conditions</label>
                </div>
                <button type="submit" name="register" class="submit-btn">Register</button>
            </form>
        </div>
    </div>

    <div id="forgotModal" class="modal" style="<?php echo ($step > 1) ? 'display:block;' : ''; ?>">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            
            <?php if ($step == 1): ?>
                <h2>Reset Password</h2>
                <p>Enter your email to begin.</p>
                <form method="POST">
                    <input type="email" name="email" class="input-field" placeholder="Email Address" required>
                    <button type="submit" name="verify_email" class="submit-btn">Continue</button>
                </form>

            <?php elseif ($step == 2): ?>
                <h2>Security Check</h2>
                <p>Question: <strong><?php echo $fetchedQuestion; ?></strong></p>
                <form method="POST">
                    <input type="hidden" name="email_to_reset" value="<?php echo $resetEmail; ?>">
                    <input type="text" name="security_answer" class="input-field" placeholder="Answer" required>
                    <button type="submit" name="verify_answer" class="submit-btn">Verify Answer</button>
                </form>

            <?php elseif ($step == 3): ?>
                <h2>New Password</h2>
                <p>Create a strong password for <?php echo $resetEmail; ?>.</p>
                <form method="POST">
                    <input type="hidden" name="email_to_reset" value="<?php echo $resetEmail; ?>">
                    <input type="password" name="new_password" class="input-field" placeholder="New Password" required>
                    <button type="submit" name="update_password" class="submit-btn" style="margin-top:10px;">Save Password</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="script (1).js"></script>
</body>
</html>