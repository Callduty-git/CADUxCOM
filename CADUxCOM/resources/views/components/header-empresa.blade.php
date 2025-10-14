<!-- FontAwesome para iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    .main-header{display:flex;justify-content:space-between;align-items:center;background:#7acb6d;padding:10px 16px;position:sticky;top:0;z-index:900}
    .left-section{display:flex;align-items:center;gap:10px}
    .logo{height:40px;width:auto}
    .logo-text{font-weight:700;color:#0f3d0f}
    .right-section{display:flex;align-items:center;gap:16px}
    .header-icon{width:36px;height:36px;cursor:pointer}
    .dropdown{position:relative}
    .dropdown-menu{position:absolute;top:48px;right:0;background:#fff;border-radius:10px;box-shadow:0 8px 20px rgba(0,0,0,.15);width:280px;display:none}
    .dropdown-menu.show{display:block}
    .dropdown-user-info{display:flex;gap:12px;padding:12px;border-bottom:1px solid #eee}
    .dropdown-company-icon,.default-avatar{width:48px;height:48px;border-radius:50%;object-fit:cover;flex:0 0 48px}
    .default-avatar{display:flex;align-items:center;justify-content:center;background:#f2f2f2;color:#777}
    .user-details p{margin:0}
    .dropdown-company-name{font-weight:600;color:#222}
    .dropdown-company-email{font-size:12px;color:#666}
    .dropdown-options{display:flex;flex-direction:column;padding:8px}
    .dropdown-menu-link,.dropdown-menu-button{display:flex;align-items:center;gap:10px;padding:10px 12px;border-radius:8px;text-decoration:none;color:#222;background:transparent;border:none;width:100%;text-align:left}
    .dropdown-menu-link:hover,.dropdown-menu-button:hover{background:#f6f6f6}
    .dropdown-menu-button.danger{color:#b00020}
    .menu-icon{width:20px;display:flex;align-items:center;justify-content:center}
</style>

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
                    <div class="user-avatar">
                        @if($empresa && $empresa->Foto)
                            <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Empresa" class="dropdown-company-icon">
                        @else
                            <div class="default-avatar">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                    </div>
                    <div class="user-details">
                        <p class="dropdown-company-name">{{ $empresa->Nombre ?? 'Empresa Invitado' }}</p>
                        <p class="dropdown-company-email">{{ $empresa->email ?? 'email@empresa.com' }}</p>
                    </div>
                </div>

                <!-- Opciones -->
                <div class="dropdown-options">
                    <a href="#" class="dropdown-menu-link" onclick="openEditModal()">
                        <div class="menu-icon">
                            <i class="fas fa-user-edit"></i>
                        </div>
                        <span>Editar Perfil</span>
                    </a>
                    
                    <a href="{{ route('help') }}" class="dropdown-menu-link">
                        <div class="menu-icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <span>Ayuda</span>
                    </a>
                    
                    

                    <div class="dropdown-divider"></div>

                    <!-- Eliminar cuenta -->
                    <form method="POST" action="{{ route('empresa.eliminar') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-menu-button danger" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')">
                            <div class="menu-icon">
                                <i class="fas fa-trash-alt"></i>
                            </div>
                            <span>Eliminar cuenta</span>
                        </button>
                    </form>

                    <!-- Cerrar sesión -->
                    <form method="POST" action="{{ route('empresa.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-menu-button">
                            <div class="menu-icon">
                                <i class="fas fa-sign-out-alt"></i>
                            </div>
                            <span>Cerrar sesión</span>
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
