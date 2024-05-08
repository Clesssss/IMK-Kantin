<?php
    session_start();
    
    if (isset($_POST['store_session'])) {
    include('connection.php');
    
    $query = "SELECT user_id, pass FROM user WHERE username=:username";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $_POST['user'], PDO::PARAM_STR);
    $stmt->execute();
    
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row && $row['pass'] === $_POST['password']) {
        // Store user ID in the session
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['user_name'] = $_POST['user'];
        // Redirect to the index.php page or wherever you want
        header('location: index.php');
        exit();
    } else {
        $warning = 'Login Gagal!';
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <title>Login</title>
    <style>
        html, body {
            height: 100%;
        }

        .form-signin {
            max-width: 330px;
            padding: 1rem;
        }

        .form-signin .form-floating:focus-within {
            z-index: 2;
        }   

        .form-signin input[type="email"] {
            margin-bottom: -1px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
        }

        .form-signin input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
    </style>
</head>
<body class="d-flex align-items-center py-4 bg-body-tertiary">
    <main class="form-signin w-100 m-auto">
      <a href="index.php" class="btn btn-sm btn-outline-secondary mb-3">
        <i class="bi bi-arrow-left"></i> Back
      </a>
  <form action="login.php" method="POST">
    <h1 class="h3 mb-3 fw-normal">Log in</h1>
    <div class="form-floating">
      <input type="text" class="form-control" name="user" id="floatingInput" placeholder="Username">
      <label for="floatingInput">Username</label>
    </div>
    <div class="form-floating">
      <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>
    <h1 class="h6 my-3 fw-normal">Create Account? <a href="signup.php">Click Here</a></h1>
    <button class="btn btn-primary w-100 py-2" type="submit" name="store_session"value="Login">Log in</button>
  </form>
  <?php
    if(isset($warning))
      {
  ?>
    <div class="alert alert-danger" role="alert">
      <b><?php echo $warning; ?></b>
    </div>
  <?php
      }
  ?>
</main>
    
</body>
</html>