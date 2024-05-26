<?php
session_start();
include('./db_connect.php');

// Check if there is an active session
if (!isset($_SESSION['login_id'])) {
    echo 'There is no active session';
} else {
    $id = $_SESSION['login_id'];
    echo $id;
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Default user type
$login_type = $_SESSION['login_type'] ?? null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Add your head content here -->
    <title>User Signup</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
        }
        .card {
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #007bff;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        .btn-signup {
            background-color: #28a745;
            border-color: #28a745;
            color: #fff;
        }
        .btn-signup:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <form action="register-auth.php" id="manage_user" method="POST">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">
                <div class="row">
                    <div class="col-md-6 border-right">
                        <b class="text-muted">Personal Information</b>
                        <div class="form-group">
                            <label for="" class="control-label">First Name</label>
                            <input type="text" name="firstname" class="form-control form-control-sm" required value="<?php echo isset($firstname) ? htmlspecialchars($firstname) : '' ?>">
                        </div>
                        
                        <div class="form-group">
                            <label for="" class="control-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? htmlspecialchars($lastname) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Contact No.</label>
                            <input type="text" name="contact" class="form-control form-control-sm" required value="<?php echo isset($contact) ? htmlspecialchars($contact) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Address</label>
                            <textarea name="address" id="" cols="30" rows="4" class="form-control" required><?php echo isset($address) ? htmlspecialchars($address) : '' ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="control-label">Avatar</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="customFile" name="img" onchange="displayImg(this, $(this))">
                                <label class="custom-file-label" for="customFile">Choose file</label>
                            </div>
                        </div>
                        <div class="form-group d-flex justify-content-center">
                            <img src="<?php echo isset($avatar) ? '../assets/uploads/' . htmlspecialchars($avatar) : '' ?>" alt="" id="cimg" class="img-fluid img-thumbnail">
                        </div>
                        <b class="text-muted">System Credentials</b>
                        <?php if ($login_type == 1): ?>
                            <div class="form-group">
                                <label for="" class="control-label">User Role</label>
                                <select name="type" id="type" class="custom-select custom-select-sm">
                                    <option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Admin</option>
                                    <option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Registrar</option>
                                    <option value="3" <?php echo isset($type) && $type == 3 ? 'selected' : '' ?>>User</option>
                                </select>
                            </div>
                        <?php else: ?>
                            <input type="hidden" name="type" value="2">
                        <?php endif; ?>
                        <div class="form-group">
                            <label class="control-label">Email</label>
                            <input type="email" class="form-control form-control-sm" name="email" required value="<?php echo isset($email) ? $email : '' ?>">
                            <small id="#msg"></small>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Password</label>
                            <input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required" : '' ?>>
                            <small><i><?php echo isset($id) ? "Leave this blank if you don't want to change your password" : '' ?></i></small>
                        </div>
                        <div class="form-group">
                            <label class="label control-label">Confirm Password</label>
                            <input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
                            <small id="pass_match" data-status=''></small>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-lg-12 text-right justify-content-center d-flex">
    <button class="btn btn-primary mr-2" type="submit" >Save</button>
    <button class="btn btn-secondary" type="button" onclick="location.href='index.php?page=user_list'">Cancel</button>
</div>



            </form>
        </div>
    </div>
</div>
<style>
    img#cimg {
        max-height: 15vh;
    }
</style>
<script>
function displayImg(input, _this) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#cimg').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
</body>
</html>
