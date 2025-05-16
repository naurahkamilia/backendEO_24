<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #2193b0, #6dd5ed);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .custom-header {
            background-color: #4169E1; 
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .btn-custom {
            background-color: #4169E1;
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #4169E1; 
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #4169E1;
        }

        label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-header text-center custom-header">
            <h4 class="mb-0">Login Admin</h4>
        </div>
        <div class="card-body">
            <?php if(session('error')): ?>
                <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <form method="POST" action="<?php echo e(route('login')); ?>">
                <?php echo csrf_field(); ?>
                <div class="mb-3">
                    <label for="email">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                        <input type="email" id="email" name="email" class="form-control" required autofocus>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-custom w-100">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\coba_laravel\resources\views/auth/login.blade.php ENDPATH**/ ?>