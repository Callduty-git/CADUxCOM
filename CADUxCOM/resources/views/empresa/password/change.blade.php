<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña de Empresa</title>
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-password.css') }}">
</head>
<body>
    <div class="main-content">
        <div class="container">
        <h2>Cambiar Contraseña de Empresa</h2>

        <div id="responseMessage" class="message-box is-hidden"></div>
        <ul id="errorList" class="error-list is-hidden"></ul>

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

    <!-- Footer -->
    <x-footer />
</body>
</html>