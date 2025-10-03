// Componente Modal de Alerta/Confirmación CADUxCOM
// Paleta: #89CF6D, #49874E, #AA5FC7, #FFFFFF

(function(window) {
    function ensureModalAlertStyles() {
        if (!document.querySelector('link[data-modal-alert-css]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '/css/modal-alert.css';
            link.setAttribute('data-modal-alert-css', 'true');
            document.head.appendChild(link);
        }
    }

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
        ensureModalAlertStyles();
        // Eliminar modales previos
        const oldModal = document.getElementById('caduxcom-modal-alert');
        if (oldModal) oldModal.remove();

        // Crear overlay
        const overlay = document.createElement('div');
        overlay.id = 'caduxcom-modal-alert';
        overlay.className = 'modal-alert-overlay';

        // Crear caja modal
        const modal = document.createElement('div');
        modal.className = 'modal-alert';

        // Header estilo carrito: fondo con gradiente verde, texto blanco, sin icono, centrado, borde superior redondeado
        const header = document.createElement('div');
        header.className = 'modal-alert__header';
        header.textContent = title;
        modal.appendChild(header);

        // Mensaje mejorado
        const msg = document.createElement('div');
        msg.className = 'modal-alert__message';
        msg.textContent = message;
        modal.appendChild(msg);

        // Botones con animación y sombra
        const btns = document.createElement('div');
        btns.className = 'modal-alert__buttons';
        if (!cancelText) {
            btns.style.justifyContent = 'center';
        }

        // Botones estilo carrito
        if (cancelText) {
            const btnCancel = document.createElement('button');
            btnCancel.textContent = cancelText;
            btnCancel.className = 'modal-alert__btn modal-alert__btn--cancel';
            btnCancel.onclick = function() {
                overlay.style.opacity = '0';
                setTimeout(() => overlay.remove(), 200);
                if (onCancel) onCancel();
            };
            btns.appendChild(btnCancel);
        }

        const btnConfirm = document.createElement('button');
        btnConfirm.textContent = confirmText;
        btnConfirm.className = 'modal-alert__btn modal-alert__btn--confirm';
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
