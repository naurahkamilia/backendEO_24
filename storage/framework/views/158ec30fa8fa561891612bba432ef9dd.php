<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Peserta Dashboard</title>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
        }
        header {
            background-color: green;
            padding: 1em;
            color: white;
            display: flex;
            justify-content: space-between;
        }
        .logout-btn {
            background-color: red;
            color: white;
            border: none;
            padding: 0.5em 1em;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <header>
        <h2>Peserta Dashboard</h2>
        <form action="<?php echo e(route('logout')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <button class="logout-btn" type="submit">Logout</button>
        </form>
    </header>
    <main style="padding: 2em;">
        <h1>Selamat datang, <?php echo e(Auth::user()->name); ?>!</h1>
        <p>Ini adalah halaman dashboard peserta.</p>
    </main>
</body>
</html>
<?php /**PATH C:\laragon\www\coba_laravel\resources\views/peserta/dashboard.blade.php ENDPATH**/ ?>