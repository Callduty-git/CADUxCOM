<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña de Empresa</title>
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        h2 {
            color: #AA5FC7;
            margin-bottom: 25px;
            font-size: 2em;
            font-weight: 700;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #4b5563;
        }
        input[type="password"] {
            width: calc(100% - 20px);
            padding: 12px 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
        }
        button[type="submit"] {
            background-color: #AA5FC7;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1em;
            font-weight: 600;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }
        button[type="submit"]:hover {
            background-color: #8e4cb3;
        }
        .message-box {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            font-weight: 500;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .error-list {
            list-style-type: none;
            padding: 0;
            text-align: left;
            margin-top: 10px;
        }
        .error-list li {
            color: #dc3545;
            margin-bottom: 5px;
            font-size: 0.9em;
        }
        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #AA5FC7;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #8e4cb3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Cambiar Contraseña de Empresa</h2>

        <div id="responseMessage" class="message-box" style="display: none;"></div>
        <ul id="errorList" class="error-list" style="display: none;"></ul>

        <form id="changePasswordForm" method="POST" action="{{ route('empresa.password.update') }}">
            @csrf
            
            <div class="form-group">
                <label for="current_password">Contraseña actual:</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password">Nueva contraseña:</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>
            
            <div class="form-group">
                <label for="new_password_confirmation">Confirmar nueva contraseña:</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
            
            <button type="submit">Cambiar contraseña</button>
        </form>
        <a href="{{ route('empresa.dashboard') }}" class="back-link">Volver al panel</a>
    </div>

    <script>
        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const responseMessageDiv = document.getElementById('responseMessage');
            const errorListUl = document.getElementById('errorList');

            responseMessageDiv.style.display = 'none';
            responseMessageDiv.className = 'message-box';
            responseMessageDiv.textContent = '';
            errorListUl.style.display = 'none';
            errorListUl.innerHTML = '';

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : ''
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.indexOf('application/json') !== -1) {
                    return response.json().then(data => ({ status: response.status, data: data }));
                } else {
                    return { status: response.status, data: { success: false, message: 'Respuesta inesperada del servidor.' } };
                }
            })
            .then(({ status, data }) => {
                if (data.success) {
                    responseMessageDiv.textContent = data.message;
                    responseMessageDiv.classList.add('success-message');
                    responseMessageDiv.style.display = 'block';
                    form.reset();
                } else {
                    responseMessageDiv.textContent = data.message;
                    responseMessageDiv.classList.add('error-message');
                    responseMessageDiv.style.display = 'block';

                    if (data.errors) {
                        for (const key in data.errors) {
                            if (data.errors.hasOwnProperty(key)) {
                                const li = document.createElement('li');
                                li.textContent = data.errors[key][0];
                                errorListUl.appendChild(li);
                            }
                        }
                        errorListUl.style.display = 'block';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                responseMessageDiv.textContent = 'Hubo un problema de conexión o del servidor ❌.';
                responseMessageDiv.classList.add('error-message');
                responseMessageDiv.style.display = 'block';
            });
        });
    </script>
</body>
</html>