

// Desactivar temporalmente hasta que solucionemos el problema principal
document.addEventListener('DOMContentLoaded', function() {
    // Buscar todos los botones de guardar tabla por tabla y desactivarlos
    const botonesGuardarTabla = document.querySelectorAll('.guardar-tabla');
    if (botonesGuardarTabla.length > 0) {
        botonesGuardarTabla.forEach(btn => {
            btn.style.display = 'none'; // Ocultar botones
        });
    }
});
