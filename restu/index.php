<?php
include 'db_connection.php';

$nameErr = $dobErr = $emailErr = $mobileErr = $genderErr = $termsErr = $passwordErr = $confirmPasswordErr = $otpErr = "";
$name = $dob = $email = $mobile = $gender = $terms = $password = $confirmPassword = $otp = "";
$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $valid = true;

    if (empty($_POST["name"])) {
        $nameErr = "* Name is required";
        $valid = false;
    } else {
        $name = test_input($_POST["name"]);
    }

    if (empty($_POST["dob"])) {
        $dobErr = "* Date of Birth is required";
        $valid = false;
    } else {
        $dob = test_input($_POST["dob"]);
    }

    if (empty($_POST["email"])) {
        $emailErr = "* Email is required";
        $valid = false;
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
            $valid = false;
        }
    }

    if (empty($_POST["mobile"])) {
        $mobileErr = "* Mobile No. is required";
        $valid = false;
    } else {
        $mobile = test_input($_POST["mobile"]);
    }

    if (empty($_POST["password"])) {
        $passwordErr = "* Password is required";
        $valid = false;
    } elseif (strlen($_POST["password"]) < 8) {
        $passwordErr = "* Password must be at least 8 characters";
        $valid = false;
    } else {
        $password = test_input($_POST["password"]);
    }

    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "* Confirm Password is required";
        $valid = false;
    } else {
        $confirmPassword = test_input($_POST["confirm_password"]);
        if ($confirmPassword !== $password) {
            $confirmPasswordErr = "* Passwords do not match";
            $valid = false;
        }
    }

    if (empty($_POST["gender"])) {
        $genderErr = "* Gender is required";
        $valid = false;
    } else {
        $gender = test_input($_POST["gender"]);
    }

    if (empty($_POST["terms"])) {
        $termsErr = "* You must agree to the terms and conditions";
        $valid = false;
    } else {
        $terms = test_input($_POST["terms"]);
    }

    if (isset($_POST["otp-submit"])) {
        if (empty($_POST["otp-1"]) || empty($_POST["otp-2"]) || empty($_POST["otp-3"]) || empty($_POST["otp-4"])) {
            $otpErr = "* OTP is required";
            $valid = false;
        } else {
            $otp = $_POST["otp-1"] . $_POST["otp-2"] . $_POST["otp-3"] . $_POST["otp-4"];
        }
    }

    if ($valid) {
        $query = "INSERT INTO users (name, dob, email, mobile, gender, password) VALUES ('$name', '$dob', '$email', '$mobile', '$gender', '$password')";
        if ($conn->query($query) === TRUE) {
            $message = "Your data has been successfully inserted into the database!";
            $messageType = "success";
            ?>
                <meta http-equiv = "refresh" content = "0; url = http://localhost/restu/index.html#" />
            <?php
        } else {
            $message = "Error: " . $query . "<br>" . $conn->error;
            $messageType = "error";
        }
    } else {
        $message = "Failed to insert data into the database!";
        $messageType = "error";
    }
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Server Side Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .form-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            box-sizing: border-box;
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container label {
            display: block;
            margin-bottom: 5px;
        }
        .form-container input[type="text"],
        .form-container input[type="email"],
        .form-container input[type="date"],
        .form-container input[type="tel"],
        .form-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .radio-container, .checkbox-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .radio-container {
            justify-content: space-between;
        }
        .form-container .radio-label, .checkbox-label {
            cursor: pointer;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
            display: flex;
            align-items: center;
        }
        .form-container input[type="radio"], .form-container input[type="checkbox"] {
            display: none;
        }
        .form-container .radio-label:before, .checkbox-label:before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 5px;
            transition: border-color 0.3s, background-color 0.3s;
        }
        .form-container .radio-label:before {
            border: 2px solid #ccc;
            border-radius: 50%;
        }
        .form-container input[type="radio"]:checked + .radio-label:before {
            border-color: #28a745;
            background-color: #28a745;
        }
        .form-container .checkbox-label:before {
            border: 2px solid #ccc;
            border-radius: 4px;
        }
        .form-container input[type="checkbox"]:checked + .checkbox-label:before {
            border-color: #28a745;
            background-color: #28a745;
            content: '\2713';
            color: white;
            text-align: center;
            line-height: 16px;
        }
        .form-container .error {
            color: red;
            font-size: 12px;
        }
        .form-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-container input[type="submit"]:hover {
            background-color: #218838;
        }
        .message {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            padding: 5px 10px;
            border-radius: 4px;
            display: none;
        }
        .message.success {
            background-color: #28a745;
            color: white;
        }
        .message.error {
            background-color: #dc3545;
            color: white;
        }
        .login-signup-links {
            text-align: center;
            margin-top: 10px;
        }
        .login-signup-links a {
            color: #007bff;
            text-decoration: none;
            margin: 0 5px;
        }
        .login-signup-links a:hover {
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .form-container {
                padding: 15px;
            }
            .radio-container, .checkbox-container {
                flex-direction: column;
                align-items: flex-start;
            }
            .radio-label, .checkbox-label {
                margin-bottom: 5px;
            }
        }
        @media (max-width: 480px) {
            .form-container {
                padding: 10px;
            }
            .form-container h2 {
                font-size: 18px;
            }
            .form-container input[type="submit"] {
                font-size: 14px;
                padding: 8px;
            }
        }
        .otp-fields {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .otp-fields input {
            width: 18%;
            padding: 10px;
            margin: 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-container" id="signup-form">
    <h2>Signup</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo $name;?>">
        <span class="error"><?php echo $nameErr;?></span>

        <label for="dob">Date of Birth:</label>
        <input type="date" id="dob" name="dob" value="<?php echo $dob;?>">
        <span class="error"><?php echo $dobErr;?></span>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $email;?>">
        <span class="error"><?php echo $emailErr;?></span>

        <label for="mobile">Mobile No.:</label>
        <input type="tel" id="mobile" name="mobile" value="<?php echo $mobile;?>">
        <span class="error"><?php echo $mobileErr;?></span>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo $password;?>">
        <span class="error"><?php echo $passwordErr;?></span>

        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" value="<?php echo $confirmPassword;?>">
        <span class="error"><?php echo $confirmPasswordErr;?></span>

        <label for="gender">Gender:</label>
        <div class="radio-container">
            <input type="radio" id="male" name="gender" value="male" <?php if (isset($gender) && $gender=="male") echo "checked";?>>
            <label class="radio-label" for="male">Male</label>
            
            <input type="radio" id="female" name="gender" value="female" <?php if (isset($gender) && $gender=="female") echo "checked";?>>
            <label class="radio-label" for="female">Female</label>
            
            <input type="radio" id="other" name="gender" value="other" <?php if (isset($gender) && $gender=="other") echo "checked";?>>
            <label class="radio-label" for="other">Other</label>
        </div>
        <span class="error"><?php echo $genderErr;?></span>
        
        <div class="checkbox-container">
            <input type="checkbox" id="terms" name="terms" <?php if (isset($terms) && $terms=="on") echo "checked";?>>
            <label class="checkbox-label" for="terms">I agree to the terms and conditions</label>
        </div>
        <span class="error"><?php echo $termsErr;?></span>
        
        <input type="submit" value="Submit">
    </form>
    <div class="login-signup-links">
        <a href="#" onclick="showLogin()">Already have an account? Login</a>
    </div>
    <div class="message <?php echo $messageType; ?>" id="message"><?php echo $message; ?></div>
</div>

<!-- Add Login Form and Forgot Password Form similarly -->

<script>
    // Your JavaScript code
</script>
</body>
</html>