<?php
session_start();
include 'includes/header.php';
include '../core/init.php';
?>

<div class="container p-3">
    <div class="card">
        <div class="card-header">
            <h3 class="p-2 h3-responsive">Forgot Password</h3>
        </div>
        <form action="reset_password.php" method="post">
            <div class="card-body">
                <div class="md-form form-sm">
                    <input type="text" id="email" class="form-control form-control-sm" name="email" required>
                    <label for="email">Email</label>
                </div>
                <div class="float-right">
                    <button type="submit" name="reset_password" class="btn btn-black" style="border-radius: 10em; background: #1c2a48">Request Password Reset</button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
