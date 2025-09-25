// Componente Modal de Alerta/Confirmación CADUxCOM
// Paleta: #89CF6D, #49874E, #AA5FC7, #FFFFFF

(function(window) {
    function showModalAlert({
        title = 'Alerta',
        message = '',
        confirmText = 'Aceptar',
        cancelText = null,
        color = '#49874E', // Color principal
        accent = '#AA5FC7', // Color secundario
        onConfirm = null,
        onCancel = null
    }) {
        // Eliminar modales previos
        const oldModal = document.getElementById('caduxcom-modal-alert');
        if (oldModal) oldModal.remove();

        // Crear overlay
        const overlay = document.createElement('div');
        overlay.id = 'caduxcom-modal-alert';
        overlay.style.position = 'fixed';
        overlay.style.top = '0';
        overlay.style.left = '0';
        overlay.style.width = '100vw';
        overlay.style.height = '100vh';
        overlay.style.background = 'rgba(80,80,80,0.25)';
        overlay.style.backdropFilter = 'blur(2px)';
        overlay.style.zIndex = '9999';
        overlay.style.display = 'flex';
        overlay.style.alignItems = 'center';
        overlay.style.justifyContent = 'center';
        overlay.style.transition = 'opacity 0.2s';
        overlay.style.opacity = '1';

        // Crear caja modal
        const modal = document.createElement('div');
        modal.style.background = '#FFFFFF';
        modal.style.borderRadius = '20px';
        modal.style.boxShadow = '0 8px 32px rgba(80,80,80,0.18)';
        modal.style.padding = '32px 32px 24px 32px';
        modal.style.minWidth = '320px';
        modal.style.maxWidth = '90vw';
        modal.style.textAlign = 'center';
        modal.style.position = 'relative';

        // Header estilo carrito: fondo con gradiente verde, texto blanco, sin icono, centrado, borde superior redondeado
        const header = document.createElement('div');
        header.style.background = 'linear-gradient(180deg, #89CF6D 0%, #49874E 100%)';
        header.style.color = '#FFFFFF';
        header.style.borderRadius = '24px 24px 0 0';
        header.style.padding = '32px 0 18px 0';
        header.style.fontSize = '2rem';
        header.style.fontWeight = 'bold';
        header.style.textAlign = 'center';
        header.textContent = title;
        modal.appendChild(header);

        // Mensaje mejorado
        const msg = document.createElement('div');
        msg.style.margin = '32px 0 36px 0';
        msg.style.fontSize = '1.18rem';
        msg.style.color = '#222';
        msg.style.fontFamily = 'Inter, Arial, sans-serif';
        msg.style.lineHeight = '1.6';
        msg.textContent = message;
        modal.appendChild(msg);

        // Botones con animación y sombra
        const btns = document.createElement('div');
        btns.style.display = 'flex';
        btns.style.justifyContent = cancelText ? 'space-between' : 'center';
        btns.style.gap = '22px';
        btns.style.marginTop = '10px';

        // Botones estilo carrito
        if (cancelText) {
            const btnCancel = document.createElement('button');
            btnCancel.textContent = cancelText;
            btnCancel.style.background = '#FFFFFF';
            btnCancel.style.color = '#444';
            btnCancel.style.border = '3px solid #AAA';
            btnCancel.style.borderRadius = '16px';
            btnCancel.style.padding = '16px 38px';
            btnCancel.style.fontWeight = 'bold';
            btnCancel.style.fontSize = '1.25rem';
            btnCancel.style.cursor = 'pointer';
            btnCancel.style.marginRight = '16px';
            btnCancel.style.boxShadow = '0 2px 8px rgba(170,95,199,0.08)';
            btnCancel.style.transition = 'all 0.18s';
            btnCancel.onmouseenter = () => btnCancel.style.background = '#F3F3F3';
            btnCancel.onmouseleave = () => btnCancel.style.background = '#FFFFFF';
            btnCancel.onclick = function() {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 200);
                if (onCancel) onCancel();
            };
            btns.appendChild(btnCancel);
        }

        const btnConfirm = document.createElement('button');
        btnConfirm.textContent = confirmText;
        btnConfirm.style.background = '#89CF6D';
        btnConfirm.style.color = '#FFFFFF';
        btnConfirm.style.border = 'none';
        btnConfirm.style.borderRadius = '16px';
        btnConfirm.style.padding = '16px 38px';
        btnConfirm.style.fontWeight = 'bold';
        btnConfirm.style.fontSize = '1.25rem';
        btnConfirm.style.cursor = 'pointer';
        btnConfirm.style.boxShadow = '0 2px 8px rgba(137,207,109,0.10)';
        btnConfirm.style.transition = 'all 0.18s';
        btnConfirm.onmouseenter = () => btnConfirm.style.background = '#49874E';
        btnConfirm.onmouseleave = () => btnConfirm.style.background = '#89CF6D';
        btnConfirm.onclick = function() {
            overlay.style.opacity = '0';
            setTimeout(() => overlay.remove(), 200);
            if (onConfirm) onConfirm && onConfirm();
        };
        btns.appendChild(btnConfirm);

        modal.appendChild(btns);
        overlay.appendChild(modal);
        document.body.appendChild(overlay);

        // Animación de entrada
        modal.animate([
            { transform: 'scale(0.95)', opacity: 0 },
            { transform: 'scale(1)', opacity: 1 }
        ], {
            duration: 220,
            easing: 'ease-out'
        });

        // Cerrar con Escape
        document.addEventListener('keydown', function escHandler(e) {
            if (e.key === 'Escape') {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 200);
                document.removeEventListener('keydown', escHandler);
                if (onCancel) onCancel();
            }
        });
    }

    // Exponer globalmente
    window.showModalAlert = showModalAlert;
})(window);

// Ejemplo de uso:
// showModalAlert({
//   title: 'Vaciar Carrito',
//   message: '¿Estás seguro de que quieres vaciar todo el carrito?',
//   confirmText: 'Aceptar',
//   cancelText: 'Cancelar',
//   color: '#49874E',
//   accent: '#AA5FC7',
//   onConfirm: function() { ... },
//   onCancel: function() { ... }
// });
