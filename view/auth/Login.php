<div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                    <div class="d-flex justify-content-center py-4">
                        <a href="index.html" class="logo d-flex align-items-center w-auto">
                            <img src="assets/img/logo.png" alt="">
                            <span class="d-none d-lg-block">Student Attendance</span>
                        </a>
                    </div><!-- End Logo -->

                    <div class="card mb-3">

                        <div class="card-body">

                            <form class=" pt-4 pb-2 row g-3 needs-validation" id="login-form" novalidate>
                                <div class="col-12">
                                    <label for="username" class="form-label">Username</label>
                                    <div class="input-group has-validation">
                                        <input type="text" name="username" class="form-control" id="username" required>
                                        <div class="invalid-feedback">Please enter your username.</div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" required>
                                    <div class="invalid-feedback">Please enter your password!</div>
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Login</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $('#login-form').submit(function(e) {
        e.preventDefault()
        $('#login-form button[type="submit"]').attr('disabled',true).html('Logging in...');
        if ($(this).find('.alert-danger').length > 0) {
            $(this).find('.alert-danger').remove();
        }
        $.ajax({
            url: 'controller/ajax.php?action=login',
            method: 'POST',
            data: $(this).serialize(),
            error: err => {
                console.error(err)
                $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
            },
            success: function(resp) {
                if (resp == 1) {
                    location.href = 'index.php?page=dashboard';
                } else {
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
                    $('#login-form button[type="submit"]').removeAttr('disabled').html('Login');
                }
            }
        })
    })
</script>