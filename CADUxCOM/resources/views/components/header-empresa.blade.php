<!-- FontAwesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<header class="main-header">
    <div class="left-section">
        <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
        <span class="logo-text">CADUxCOM</span>
    </div>

    <div class="right-section">
        <div class="dropdown">
            <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="header-icon" id="cuUserIcon">

            <div class="dropdown-menu" id="cuUserMenu">
                <!-- Info de empresa -->
                @php
                    $empresa = Auth::guard('empresa')->user();
                @endphp

                <div class="dropdown-user-info">
                    @if($empresa && $empresa->Foto)
                        <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Empresa" class="dropdown-company-icon">
                    @else
                        <img src="{{ asset('images/icon-company.png') }}" alt="Empresa" class="dropdown-company-icon">
                    @endif
                    <p class="dropdown-company-name">Empresa {{ $empresa->Nombre ?? 'Invitado' }}</p>
                </div>

                <!-- Opciones -->
                <div class="dropdown-options">
                    <a href="#" class="dropdown-menu-link" onclick="openEditModal()">
                        <i class="fas fa-user-edit"></i>
                        Editar Perfil
                    </a>
                    
                    <a href="#" class="dropdown-menu-link">
                        <i class="fas fa-question-circle"></i>
                        Ayuda
                    </a>
                    
                    <a href="#" class="dropdown-menu-link">
                        <i class="fas fa-comment"></i>
                        Comentar
                    </a>

                    <!-- Eliminar cuenta -->
                    <form method="POST" action="{{ route('empresa.eliminar') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-menu-button danger" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')">
                            <i class="fas fa-trash-alt"></i>
                            Eliminar cuenta
                        </button>
                    </form>

                    <!-- Cerrar sesión -->
                    <form method="POST" action="{{ route('empresa.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-menu-button">
                            <i class="fas fa-sign-out-alt"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.getElementById('cuUserIcon');
    const userMenu = document.getElementById('cuUserMenu');

    if(userIcon) {
        userIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(event) {
            if (!userMenu.contains(event.target) && !userIcon.contains(event.target)) {
                userMenu.classList.remove('show');
            }
        });
    }
});

// Función para abrir el modal de editar perfil
function openEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) {
        modal.style.display = 'block';
    }
}
</script>
