{{-- Componente de comentarios para productos --}}
<div class="comentarios-container" id="comentarios-container">
    {{-- Encabezado --}}
    <div class="comentarios-header">
        <h3>💬 Comentarios y Reseñas</h3>
        <div class="comentarios-count" id="comentarios-count">
            <span id="total-comentarios">0</span> comentarios
        </div>
    </div>

    {{-- Formulario de comentario (solo para usuarios autenticados) --}}
    @auth
        <div class="comentario-form">
            <form id="comentario-form" data-producto-id="{{ $producto->Id_Producto }}">
                @csrf
                <textarea 
                    name="contenido" 
                    id="comentario-contenido" 
                    placeholder="Escribe tu comentario sobre este producto..."
                    required
                    minlength="3"
                    maxlength="1000"
                ></textarea>
                <div class="comentario-form-actions">
                    <button type="button" class="btn-comentario btn-comentario-secondary" onclick="cancelarComentario()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-comentario btn-comentario-primary">
                        <span>📝</span> Publicar Comentario
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="comentario-form" style="text-align: center; padding: 2rem;">
            <p style="color: var(--gris-texto); margin-bottom: 1rem;">
                Debes iniciar sesión para comentar
            </p>
            <a href="{{ route('login', ['redirect' => request()->url()]) }}" class="btn-comentario btn-comentario-primary">
                <span>🔑</span> Iniciar Sesión
            </a>
        </div>
    @endauth

    {{-- Lista de comentarios --}}
    <div class="comentarios-list" id="comentarios-list">
        <div class="comentario-loading" id="comentarios-loading">
            <div class="spinner"></div>
        </div>
    </div>
</div>

{{-- Alertas de notificación --}}
<div id="comentario-alerts"></div>

<script>
console.log('Script de comentarios cargado');

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, iniciando sistema de comentarios');
    
    const productoId = {{ $producto->Id_Producto }};
    const comentariosContainer = document.getElementById('comentarios-container');
    const comentariosList = document.getElementById('comentarios-list');
    const comentariosCount = document.getElementById('total-comentarios');
    const comentarioForm = document.getElementById('comentario-form');
    const comentarioContenido = document.getElementById('comentario-contenido');
    const alertsContainer = document.getElementById('comentario-alerts');

    console.log('Elementos encontrados:', {
        comentariosContainer: !!comentariosContainer,
        comentariosList: !!comentariosList,
        comentariosCount: !!comentariosCount,
        comentarioForm: !!comentarioForm,
        comentarioContenido: !!comentarioContenido,
        alertsContainer: !!alertsContainer
    });

    // Variables globales
    let userPermissions = null;
    let productoEmpresaId = null;

    // Cargar comentarios al inicio
    cargarComentarios();

    // Manejar envío de formulario
    if (comentarioForm) {
        comentarioForm.addEventListener('submit', function(e) {
            e.preventDefault();
            enviarComentario();
        });
    }

    // Función para cargar comentarios
    async function cargarComentarios() {
        console.log('Cargando comentarios para producto:', productoId);
        try {
            const response = await fetch(`/comentarios/producto/${productoId}`);
            const data = await response.json();
            
            console.log('Respuesta de comentarios:', data);
            
            if (data.success) {
                userPermissions = data.permissions;
                productoEmpresaId = data.producto.empresa_id;
                mostrarComentarios(data.comentarios);
                comentariosCount.textContent = data.comentarios.length;
            } else {
                mostrarError('Error al cargar comentarios');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        }
    }

    // Función para mostrar comentarios
    function mostrarComentarios(comentarios) {
        console.log('Mostrando comentarios:', comentarios);
        const loading = document.getElementById('comentarios-loading');
        if (loading) loading.style.display = 'none';

        if (comentarios.length === 0) {
            comentariosList.innerHTML = `
                <div class="comentarios-empty">
                    <div class="comentarios-empty-icon">💭</div>
                    <h4>No hay comentarios aún</h4>
                    <p>Sé el primero en comentar sobre este producto</p>
                </div>
            `;
            return;
        }

        comentariosList.innerHTML = comentarios.map(comentario => crearHTMLComentario(comentario)).join('');
    }

    // Función para crear HTML de comentario
    function crearHTMLComentario(comentario) {
        const fecha = new Date(comentario.created_at).toLocaleDateString('es-ES', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        const autorNombre = comentario.user ? comentario.user.name : comentario.empresa.Nombre;
        const autorTipo = comentario.user ? 'user' : 'empresa';
        const autorBadge = comentario.user ? 'Usuario' : 'Empresa';
        const avatarLetra = autorNombre.charAt(0).toUpperCase();

        const puedeEliminar = verificarPermisosEliminacion(comentario);
        const puedeResponder = verificarPermisosRespuesta(comentario);
        const puedeEditar = verificarPermisosEdicion(comentario);

        let respuestasHTML = '';
        if (comentario.replies && comentario.replies.length > 0) {
            respuestasHTML = `
                <div class="comentario-replies">
                    ${comentario.replies.map(reply => crearHTMLComentario(reply)).join('')}
                </div>
            `;
        }

        return `
            <div class="comentario-item ${comentario.parent_id ? 'comentario-reply' : 'comentario-main'}" data-id="${comentario.id}">
                <div class="comentario-header">
                    <div class="comentario-author">
                        <div class="comentario-avatar">${avatarLetra}</div>
                        <div class="comentario-author-info">
                            <h4>${autorNombre}</h4>
                            <p>${fecha}</p>
                        </div>
                        <span class="comentario-badge comentario-badge-${autorTipo}">${autorBadge}</span>
                    </div>
                </div>
                
                <div class="comentario-content">
                    ${comentario.contenido}
                </div>
                
                <div class="comentario-actions">
                    ${puedeResponder ? `
                        <button class="btn-comentario-action btn-responder" onclick="mostrarFormularioRespuesta(${comentario.id})">
                            <span>💬</span> Responder
                        </button>
                    ` : ''}
                    ${puedeEditar ? `
                        <button class="btn-comentario-action btn-editar" onclick="mostrarFormularioEdicion(${comentario.id})">
                            <span>✏️</span> Editar
                        </button>
                    ` : ''}
                    ${puedeEliminar ? `
                        <button class="btn-comentario-action btn-eliminar" onclick="eliminarComentario(${comentario.id})">
                            <span>🗑️</span> Eliminar
                        </button>
                    ` : ''}
                </div>
                
                <div class="comentario-reply-form" id="reply-form-${comentario.id}">
                    <form onsubmit="enviarRespuesta(event, ${comentario.id})">
                        <textarea placeholder="Escribe tu respuesta..." required minlength="3" maxlength="1000"></textarea>
                        <div class="comentario-reply-form-actions">
                            <button type="button" class="btn-comentario btn-comentario-secondary" onclick="cancelarRespuesta(${comentario.id})">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-comentario btn-comentario-primary">
                                <span>📝</span> Responder
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="comentario-edit-form" id="edit-form-${comentario.id}">
                    <form onsubmit="enviarEdicion(event, ${comentario.id})">
                        <textarea placeholder="Edita tu comentario..." required minlength="3" maxlength="1000">${comentario.contenido}</textarea>
                        <div class="comentario-reply-form-actions">
                            <button type="button" class="btn-comentario btn-comentario-secondary" onclick="cancelarEdicion(${comentario.id})">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-comentario btn-comentario-primary">
                                <span>💾</span> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
                
                ${respuestasHTML}
            </div>
        `;
    }

    // Función para enviar comentario
    async function enviarComentario() {
        const contenido = comentarioContenido.value.trim();
        
        if (contenido.length < 3) {
            mostrarError('El comentario debe tener al menos 3 caracteres');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('contenido', contenido);
            formData.append('producto_id', productoId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await fetch('/comentarios', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data.message);
                comentarioContenido.value = '';
                cargarComentarios();
            } else {
                mostrarError(data.message || 'Error al publicar comentario');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        }
    }

    // Función para enviar edición
    async function enviarEdicion(event, comentarioId) {
        event.preventDefault();
        
        const form = event.target;
        const contenido = form.querySelector('textarea').value.trim();
        
        if (contenido.length < 3) {
            mostrarError('El comentario debe tener al menos 3 caracteres');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('contenido', contenido);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');

            const response = await fetch(`/comentarios/${comentarioId}`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data.message);
                cancelarEdicion(comentarioId);
                cargarComentarios();
            } else {
                mostrarError(data.message || 'Error al actualizar comentario');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        }
    }

    // Función para enviar respuesta
    async function enviarRespuesta(event, parentId) {
        event.preventDefault();
        
        const form = event.target;
        const contenido = form.querySelector('textarea').value.trim();
        
        if (contenido.length < 3) {
            mostrarError('La respuesta debe tener al menos 3 caracteres');
            return;
        }

        try {
            const formData = new FormData();
            formData.append('contenido', contenido);
            formData.append('producto_id', productoId);
            formData.append('parent_id', parentId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            const response = await fetch('/comentarios', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data.message);
                form.querySelector('textarea').value = '';
                cancelarRespuesta(parentId);
                cargarComentarios();
            } else {
                mostrarError(data.message || 'Error al publicar respuesta');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        }
    }

    // Función para eliminar comentario con alerta personalizada
    async function eliminarComentario(comentarioId) {
        const confirmed = await mostrarConfirmacion(
            'Eliminar Comentario',
            '¿Estás seguro de que quieres eliminar este comentario? Esta acción no se puede deshacer.'
        );
        
        if (!confirmed) return;

        try {
            const response = await fetch(`/comentarios/${comentarioId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                mostrarExito(data.message);
                cargarComentarios();
            } else {
                mostrarError(data.message || 'Error al eliminar comentario');
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarError('Error de conexión');
        }
    }

    // Función para mostrar confirmación personalizada
    function mostrarConfirmacion(titulo, mensaje) {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.className = 'comentario-confirm-overlay';
            
            overlay.innerHTML = `
                <div class="comentario-confirm-dialog">
                    <h3>${titulo}</h3>
                    <p>${mensaje}</p>
                    <div class="comentario-confirm-actions">
                        <button class="comentario-confirm-btn comentario-confirm-btn-secondary" onclick="cerrarConfirmacion(false)">
                            Cancelar
                        </button>
                        <button class="comentario-confirm-btn comentario-confirm-btn-primary" onclick="cerrarConfirmacion(true)">
                            Confirmar
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            // Función para cerrar la confirmación
            window.cerrarConfirmacion = function(resultado) {
                document.body.removeChild(overlay);
                delete window.cerrarConfirmacion;
                resolve(resultado);
            };
        });
    }

    // Funciones auxiliares
    function mostrarFormularioRespuesta(comentarioId) {
        const form = document.getElementById(`reply-form-${comentarioId}`);
        if (form) {
            form.classList.add('active');
        }
    }

    function cancelarRespuesta(comentarioId) {
        const form = document.getElementById(`reply-form-${comentarioId}`);
        if (form) {
            form.classList.remove('active');
            form.querySelector('textarea').value = '';
        }
    }

    function mostrarFormularioEdicion(comentarioId) {
        const form = document.getElementById(`edit-form-${comentarioId}`);
        if (form) {
            form.classList.add('active');
        }
    }

    function cancelarEdicion(comentarioId) {
        const form = document.getElementById(`edit-form-${comentarioId}`);
        if (form) {
            form.classList.remove('active');
        }
    }

    function cancelarComentario() {
        comentarioContenido.value = '';
    }

    function verificarPermisosEdicion(comentario) {
        if (!userPermissions) return false;
        
        // Administradores pueden editar cualquier comentario
        if (userPermissions.is_admin) return true;
        
        // Usuarios pueden editar sus propios comentarios
        if (userPermissions.user_type === 'user' && comentario.user_id === userPermissions.user_id) {
            return true;
        }
        
        // Empresas pueden editar sus propios comentarios
        if (userPermissions.user_type === 'empresa' && comentario.empresa_id === userPermissions.user_id) {
            return true;
        }
        
        return false;
    }

    function verificarPermisosEliminacion(comentario) {
        if (!userPermissions) return false;
        
        // Administradores pueden eliminar cualquier comentario
        if (userPermissions.is_admin) return true;
        
        // Usuarios pueden eliminar sus propios comentarios
        if (userPermissions.user_type === 'user' && comentario.user_id === userPermissions.user_id) {
            return true;
        }
        
        // Empresas pueden eliminar solo sus propias respuestas
        if (userPermissions.user_type === 'empresa' && comentario.empresa_id === userPermissions.user_id) {
            return comentario.parent_id !== null; // Solo respuestas, no comentarios principales
        }
        
        return false;
    }

    function verificarPermisosRespuesta(comentario) {
        if (!userPermissions) return false;
        
        // Solo las empresas pueden responder
        if (userPermissions.user_type !== 'empresa') return false;
        
        // Solo pueden responder a comentarios de sus productos
        return userPermissions.user_id === productoEmpresaId;
    }

    function mostrarExito(mensaje) {
        mostrarAlerta(mensaje, 'success');
    }

    function mostrarError(mensaje) {
        mostrarAlerta(mensaje, 'error');
    }

    function mostrarInfo(mensaje) {
        mostrarAlerta(mensaje, 'info');
    }

    function mostrarAlerta(mensaje, tipo) {
        const alert = document.createElement('div');
        alert.className = `comentario-alert comentario-alert-${tipo}`;
        alert.textContent = mensaje;
        
        document.body.appendChild(alert);
        
        setTimeout(() => {
            alert.style.animation = 'slideOutRight 0.5s ease-out';
            setTimeout(() => {
                if (document.body.contains(alert)) {
                    document.body.removeChild(alert);
                }
            }, 500);
        }, 4000);
    }
});

// Funciones globales para uso en onclick
function mostrarFormularioRespuesta(comentarioId) {
    const form = document.getElementById(`reply-form-${comentarioId}`);
    if (form) {
        form.classList.add('active');
    }
}

function cancelarRespuesta(comentarioId) {
    const form = document.getElementById(`reply-form-${comentarioId}`);
    if (form) {
        form.classList.remove('active');
        form.querySelector('textarea').value = '';
    }
}

function mostrarFormularioEdicion(comentarioId) {
    const form = document.getElementById(`edit-form-${comentarioId}`);
    if (form) {
        form.classList.add('active');
    }
}

function cancelarEdicion(comentarioId) {
    const form = document.getElementById(`edit-form-${comentarioId}`);
    if (form) {
        form.classList.remove('active');
    }
}

function cancelarComentario() {
    const textarea = document.getElementById('comentario-contenido');
    if (textarea) {
        textarea.value = '';
    }
}

function eliminarComentario(comentarioId) {
    if (!confirm('¿Estás seguro de que quieres eliminar este comentario?')) {
        return;
    }

    fetch(`/comentarios/${comentarioId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Recargar comentarios
            location.reload();
        } else {
            alert(data.message || 'Error al eliminar comentario');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error de conexión');
    });
}
</script>
