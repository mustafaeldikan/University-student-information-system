<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

    <?php
    // Veritabanı bağlantısı
    session_start();
    if (isset($_SESSION['user_id'])) {
        // Log the user out by destroying the session
        session_destroy();
        header("Location: sign.php");  // Redirect to the same page after logout
        exit();
    }
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "uni";

    $connect = new mysqli($servername, $username, $password, $dbname);
    if ($connect->connect_error) {
        die("Connection failed: " . $connect->connect_error);
    }

    // Kayıt işlemi
    function signUp($connect) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Şifreyi hash'le (güvenlik için)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Veritabanına ekleme
        $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_password')";

        if ($connect->query($sql) === TRUE) {
            // Başarıyla kayıt işlemi yapıldıktan sonra yönlendirme
            header("Location: sign.php");
            exit();
        } else {
            echo "<p>Hata: " . $sql . "<br>" . $connect->error . "</p>";
        }
    }

    // Giriş işlemi
    function signIn($connect) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Kullanıcıyı veritabanında ara
        $sql = "SELECT * FROM users WHERE email='$email'";
        $result = $connect->query($sql);
    
        if ($result->num_rows > 0) {
            // Kullanıcı bulundu, şifreyi doğrula
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Set session variables after successful login
                $_SESSION['user_id'] = $row['id'];  // You can store the user ID or any unique info
                $_SESSION['email'] = $row['email']; // Store email or other data if needed
    
                // Redirect to index.php after successful login
                header("Location: index.php");
                exit();
            } else {
                echo "<p>Incorrect password!</p>";
            }
        } else {
            echo "<p>User not found!</p>";
        }
    }

    // Formdan gelen verilere göre fonksiyonları çağır
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['sign_up'])) {
            signUp($connect);
        } elseif (isset($_POST['sign_in'])) {
            signIn($connect);
        }
    }

    $connect->close();
    ?>

    <h2>Welcome to my Student information system</h2>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="sign.php" method="POST">
                <h1>Create Account</h1>
                <div class="social-container">
                    <a href="https://www.facebook.com/share/LmW3m6N2UmGKPm7P/?mibextid=qi2Omg " class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/mustafa.dikan.official?igsh=eDVhcmtpMm0ydmpr" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/mustafa-eldikan-41bb30207?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3B5e%2B7HlX6TZaD1Q09cJdNiQ%3D%3D" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <input type="text" name="name" placeholder="User name" required />
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <button type="submit" style='cursor:pointer;' name="sign_up">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="sign.php" method="POST">
                <h1>Sign in</h1>
                <div class="social-container">
                    <a href="https://www.facebook.com/share/LmW3m6N2UmGKPm7P/?mibextid=qi2Omg " class="social"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://www.instagram.com/mustafa.dikan.official?igsh=eDVhcmtpMm0ydmpr" class="social"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.linkedin.com/in/mustafa-eldikan-41bb30207?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3B5e%2B7HlX6TZaD1Q09cJdNiQ%3D%3D" class="social"><i class="fab fa-linkedin-in"></i></a>
                </div>
                <input type="email" name="email" placeholder="Email" required />
                <input type="password" name="password" placeholder="Password" required />
                <a href="#">Forgot your password?</a>
                <button type="submit" style='cursor:pointer;' name="sign_in">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" style='cursor:pointer;' id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, User!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" style='cursor:pointer;' id="signUp">Sign Up</button>
                </div>
            </div>
        </div>
    </div>

    <footer>
    <p>
		Created with <i class="fa fa-heart"></i> by
		<a target="_blank" href="https://www.linkedin.com/in/mustafa-eldikan-41bb30207?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3B5e%2B7HlX6TZaD1Q09cJdNiQ%3D%3D" >Mustafa</a>
    </footer>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add("right-panel-active");
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove("right-panel-active");
        });
        
    </script>
</body>
</html>
