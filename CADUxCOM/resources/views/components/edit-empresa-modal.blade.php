<div id="editModal" class="modal" style="display:none;">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fas fa-user-edit" style="margin-right:8px;"></i>
                Editar Perfil de Empresa
            </h3>
            <span class="close" id="editModalClose">&times;</span>
        </div>

        <div class="modal-body">
            @php($empresaLocal = isset($empresa) ? $empresa : Auth::guard('empresa')->user())
            <form id="editProfileFormEmpresa" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-row">
                        <div class="form-field">
                            <label>Nombre</label>
                            <input type="text" name="Nombre" class="input-control" value="{{ $empresaLocal->Nombre ?? '' }}" required>
                        </div>
                        <div class="form-field">
                            <label>Correo</label>
                            <input type="email" name="email" class="input-control" value="{{ $empresaLocal->email ?? $empresaLocal->Email ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Dirección</label>
                            <input type="text" name="Direccion" class="input-control" value="{{ $empresaLocal->Direccion ?? '' }}">
                        </div>
                        <div class="form-field">
                            <label>Teléfono</label>
                            <input type="text" name="Contacto" class="input-control" value="{{ $empresaLocal->Contacto ?? '' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>NIT</label>
                            <input type="text" name="NIT" class="input-control" value="{{ $empresaLocal->NIT ?? '' }}">
                        </div>
                        <div class="form-field">
                            <label>Ubicación</label>
                            <input type="text" name="Ubicacion" class="input-control" value="{{ $empresaLocal->Ubicacion ?? '' }}">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field">
                            <label>Municipio</label>
                            <input type="text" name="Municipio" class="input-control" value="{{ $empresaLocal->Municipio ?? '' }}">
                        </div>
                        <div class="form-field">
                            <label>Foto</label>
                            <input type="file" name="Foto" id="empresaLogoInput" class="input-control" accept="image/*">
                            <div class="logo-preview" id="empresaLogoPreview">
                                @if($empresaLocal && $empresaLocal->Foto)
                                    <img src="{{ asset('storage/' . $empresaLocal->Foto) }}" alt="Logo empresa" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">
                                @else
                                    <span>Sin logo</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-field" style="grid-column: span 2;">
                            <label>Certificado Cámara de Comercio</label>
                            <input type="file" name="Certificado_Camara_de_comercio" class="input-control" accept="image/*,application/pdf">
                        </div>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="save-btn" id="editModalCancel">Cancelar</button>
                    <button type="submit" class="save-btn">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('editModal');
    const closeBtn = document.getElementById('editModalClose');
    const cancelBtn = document.getElementById('editModalCancel');
    const form = document.getElementById('editProfileFormEmpresa');
    const logoInput = document.getElementById('empresaLogoInput');
    const logoPreview = document.getElementById('empresaLogoPreview');

    function hideModal() { if (modal) modal.style.display = 'none'; }
    function showModal() { if (modal) modal.style.display = 'block'; }

    // Cerrar con ESC y click fuera
    document.addEventListener('keydown', function(e) { if (e.key === 'Escape') hideModal(); });
    window.addEventListener('click', function(e) { if (e.target === modal) hideModal(); });
    closeBtn && closeBtn.addEventListener('click', hideModal);
    cancelBtn && cancelBtn.addEventListener('click', hideModal);

    // Preview de logo
    if (logoInput && logoPreview) {
        logoInput.addEventListener('change', function() {
            const file = this.files && this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                logoPreview.innerHTML = '';
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Logo empresa';
                img.style.width = '100%';
                img.style.height = '100%';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '8px';
                logoPreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    }

    // Envío del formulario via fetch con fallback
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(form);

            fetch("{{ route('empresa.perfil.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(res => { if (!res.ok) throw new Error('Error en la actualización'); return res.json(); })
            .then(data => {
                if (typeof showModalAlert === 'function') {
                    showModalAlert({
                        title: '¡Éxito!',
                        message: 'Perfil actualizado correctamente ✅',
                        confirmText: 'Aceptar',
                        color: '#49874E',
                        accent: '#AA5FC7',
                        onConfirm: () => location.reload()
                    });
                } else {
                    alert('Perfil actualizado correctamente');
                    location.reload();
                }
            })
            .catch(err => {
                console.error(err);
                if (typeof showModalAlert === 'function') {
                    showModalAlert({
                        title: 'Error',
                        message: 'Hubo un problema al actualizar el perfil ❌',
                        confirmText: 'Cerrar',
                        color: '#AA5FC7',
                        accent: '#49874E'
                    });
                } else {
                    alert('Hubo un problema al actualizar el perfil');
                }
            });
        });
    }

    // Exponer función para abrir el modal desde el header
    window.openEditModal = function() { showModal(); };
});
</script>