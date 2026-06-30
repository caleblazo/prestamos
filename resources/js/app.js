import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
    const departamentoSelect = document.getElementById("departamento_id");
    const provinciaSelect = document.getElementById("provincia_id");
    const distritoSelect = document.getElementById("distrito_id");

    departamentoSelect.addEventListener("change", async (e) => {
        const departamentoId = e.target.value;
        provinciaSelect.innerHTML = '<option value="">Seleccione...</option>';
        distritoSelect.innerHTML = '<option value="">Seleccione...</option>';

        if (departamentoId) {
            const response = await fetch(`/provincias/${departamentoId}`);
            const provincias = await response.json();

            provincias.forEach((provincia) => {
                provinciaSelect.innerHTML += `<option value="${provincia.id}">${provincia.nombre}</option>`;
            });
        }
    });

    provinciaSelect.addEventListener("change", async (e) => {
        const provinciaId = e.target.value;
        distritoSelect.innerHTML = '<option value="">Seleccione...</option>';

        if (provinciaId) {
            const response = await fetch(`/distritos/${provinciaId}`);
            const distritos = await response.json();

            distritos.forEach((distrito) => {
                distritoSelect.innerHTML += `<option value="${distrito.id}">${distrito.nombre}</option>`;
            });
        }
    });
});

document.getElementById("editarBtn").addEventListener("click", function () {
    const seleccionado = document.querySelector(
        'input[name="cliente_id"]:checked'
    );
    if (seleccionado) {
        window.location.href = `/clientes/${seleccionado.value}/editar`;
    } else {
        alert("Por favor seleccione un cliente.");
    }
});
