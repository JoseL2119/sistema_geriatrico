<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="../assets/css/app.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            margin: 1rem;
        }

        .login-logo {
            max-height: 90px;
            display: block;
            margin: 0 auto 1rem;
        }
    </style>
</head>
<body>

    <div class="card-app login-card">
        <img src="../src/logo.PNG" alt="Logo del geriátrico" class="login-logo">

        <h5 class="text-center mb-4" style="color: var(--primary-700);">Iniciar sesión</h5>

        <?php if (($_GET['error'] ?? '') === '1'): ?>
            <div class="alert alert-danger">Usuario o contraseña incorrectos.</div>
        <?php endif; ?>

        <form method="POST" action="/auth">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <button type="submit" class="btn btn-primary btn-app w-100">Entrar</button>
        </form>
    </div>

</body>
</html>
