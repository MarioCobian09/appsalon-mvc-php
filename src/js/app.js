let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    usuarioId: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})

function iniciarApp() {
    tabs(); // Cambia la seccion cuando se precionen los tabs
    mostrarSeccion();
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // Consulta la API en el backend de PHP

    idCliente();

    nombreCliente(); // Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); // Añade la fecha de la cita
    seleccionarHora();

    mostrarResumen();
}

function mostrarSeccion() {

    // Ocultar la seccion que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar')
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar')
    }

    // Seleccionar la seccion con el paso...
    const pasoSelector = `#paso-${paso}`
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quitq la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual')
    if (tabAnterior) {
        tabAnterior.classList.remove('actual')
    }

    // Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`)
    tab.classList.add('actual')
}

function tabs() {
    const botones = document.querySelectorAll('.tabs button');
    
    botones.forEach(boton => {
        boton.addEventListener('click', (e) => {
            paso = parseInt(e.target.dataset.paso)
            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador() {
    const paginaAnterior = document.querySelector('#anterior')
    const paginaSiguiente = document.querySelector('#siguiente')

    if(paso === 1) {
        paginaAnterior.classList.add('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    } else if(paso === 3) {
        paginaSiguiente.classList.add('ocultar');
        paginaAnterior.classList.remove('ocultar');
        mostrarResumen();
    } else {
        paginaAnterior.classList.remove('ocultar');
        paginaSiguiente.classList.remove('ocultar');
    }
}

function paginaAnterior () {
    const paginaAnterior = document.querySelector('#anterior')
    paginaAnterior.addEventListener('click', function() {
        if(paso <= pasoInicial) return;
        paso--;
        mostrarSeccion();
        botonesPaginador();
    })
}

function paginaSiguiente () {
    const paginaSiguiente = document.querySelector('#siguiente')

    paginaSiguiente.addEventListener('click', function() {
        if(paso >= pasoFinal) return;
        paso++;
        mostrarSeccion();
        botonesPaginador();
    })
}

async function consultarAPI() {

    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json()

        mostrarServicios(servicios)
    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios) {
    servicios.forEach(servicio => {
        const {ser_id, ser_nombre, ser_precio} = servicio

        const nombreServicio = document.createElement('P')
        nombreServicio.classList.add('nombre-servicio')
        nombreServicio.textContent = ser_nombre

        const precioServicio = document.createElement('P')
        precioServicio.classList.add('precio-servicio')
        precioServicio.textContent = `$${ser_precio}`

        const serviciodiv = document.createElement('DIV')
        serviciodiv.classList.add('servicio')
        serviciodiv.dataset.idServicio = ser_id
        serviciodiv.onclick = function() {
            seleccionarServicio(servicio)
        }

        serviciodiv.appendChild(nombreServicio)
        serviciodiv.appendChild(precioServicio)

        document.querySelector('#servicios').appendChild(serviciodiv)
    })
}

function seleccionarServicio(servicio) {
    const {ser_id} = servicio
    const { servicios } = cita;

    // Identificar el elemento que le damos click
    const divServicio = document.querySelector(`[data-id-servicio="${ser_id}"]`)

    // comprobar si un servicio ya fue agregado o quitarlo
    if( servicios.some(agregado => agregado.ser_id === ser_id)) {
        cita.servicios = servicios.filter( agregado => agregado.ser_id !== ser_id)
        divServicio.classList.remove('seleccionado')
    } else {
        cita.servicios = [...servicios, servicio]
        divServicio.classList.add('seleccionado')
    }
}

function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value
}

function idCliente() {
    cita.usuarioId = document.querySelector('#id').value
}

function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha')

    inputFecha.addEventListener('input', (e) => {
        const dia = new Date(e.target.value).getUTCDay() // SE obtiene el numero de dia, comenzando en domingo como 0, lunes es 1...

        if([6,0].includes(dia)){ // Si en el dia 6 y 0 (sabado y domingo), se imprime una alerta
            e.target.value = ''
            mostrarAlerta('Fines de semana no permitidos', 'error', '#seccion-fecha')
        } else {                // Si no, solo se guarda en el arreglo
            cita.fecha = e.target.value
        }
    })
}

function seleccionarHora() {
    const inputHora = document.querySelector('#hora')

    inputHora.addEventListener('input', (e) => {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0]
        
        if(hora < 9 ||  hora > 18) {
            e.target.value = ''
            mostrarAlerta('Hora No Válida', 'error', '#seccion-hora')
        } else {
            cita.hora = e.target.value
        }
    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {

    // Previene que se generen mas de una alerta
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia) {
        alertaPrevia.remove();
    }

    // Creacion de la alerta
    const alerta = document.createElement('DIV')
    alerta.textContent = mensaje;
    alerta.classList.add('alerta')
    alerta.classList.add(tipo)
    
    const referencia = document.querySelector(elemento)
    referencia.appendChild(alerta)

    if(desaparece) {
        setTimeout(() => {
            alerta.remove();
        }, 3000)
    }
}

function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen')

    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild)
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Faltan datos de Servicios, Fecha u Hora', 'error', '.contenido-resumen', false)

        return;
    }

    const {nombre, fecha, hora, servicios} = cita;


    const headingServicios = document.createElement('H3')
    headingServicios.textContent = 'Resumen de Servicios'
    resumen.appendChild(headingServicios)


    servicios.forEach((servicio) => {
        const {ser_id, ser_nombre, ser_precio} = servicio
        const contenedorServicio = document.createElement('DIV')
        contenedorServicio.classList.add('contenedor-servicios')

        const textoServicio = document.createElement('P')
        textoServicio.textContent = ser_nombre

        const precioServicio = document.createElement('P')
        precioServicio.innerHTML = `<span>Precio:</span> ${ser_precio}`

        contenedorServicio.appendChild(textoServicio)
        contenedorServicio.appendChild(precioServicio)

        resumen.appendChild(contenedorServicio)
    })

    const headingCita = document.createElement('H3')
    headingCita.textContent = 'Resumen de Cita'
    resumen.appendChild(headingCita)

    const nombreCliente = document.createElement('P')
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`

    // Formatear la fecha en español
    const fechaObj = new Date(fecha)
    const mes = fechaObj.getMonth()
    const dia = fechaObj.getDate() + 2
    const year = fechaObj.getFullYear()

    const fechaUTC = new Date(Date.UTC(year, mes, dia))

    const opciones = { weekday: 'long', year:'numeric', month: 'long', day:'numeric' }
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones)

    const fechaCita = document.createElement('P')
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`

    const horaCita = document.createElement('P')
    horaCita.innerHTML = `<span>Hora:</span> ${hora}`

    resumen.appendChild(nombreCliente)
    resumen.appendChild(fechaCita)
    resumen.appendChild(horaCita)

    const botonReservar = document.createElement('BUTTON')
    botonReservar.classList.add('boton')
    botonReservar.textContent = 'Reservar Cita'
    botonReservar.onclick = reservarCita

    resumen.appendChild(botonReservar)
}

async function reservarCita() {

    const {usuarioId, fecha, hora, servicios} = cita

    const idServicios = servicios.map( servicio => servicio.ser_id)

    const datos = new FormData()
    datos.append('usuarioId', usuarioId)
    datos.append('fecha', fecha)
    datos.append('hora', hora)
    datos.append('servicios', idServicios)

    try {
        // Peticion hacia la api
        const url = '/api/citas'

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        })

        const resultado = await respuesta.json()

        if(resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita creada",
                text: "Tu cita fue creada correctamente",
                button: "Ok",
            }).then(() => {
                window.location.reload();
            })
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Opss...",
            text: "Hubo un error al guardar la cita.",
            button: "Ok",
        }).then(() => {
            window.location.reload();
        })
    }

    

    // console.log([...datos]); // Debido a que si agregamos datos directamente al FormData, no nos dejara ver que datos contiene, para ello, usamos este codigo
}