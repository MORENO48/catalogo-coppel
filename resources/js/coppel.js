const globalData = {}
const params = new URLSearchParams()

globalData.categorias = []
globalData.articulos = []
globalData.articulos = []
globalData.idArticuloEditarActual = 0
globalData.idArticuloEliminarActual = 0
const btnRow = Object.freeze({
  eliminar: 'btnEliminarRow',
  agregar: 'btnAgregarRow'
})
const headers = {
  "Authorization": `Bearer ${localStorage.getItem('token')}`,
  'Content-Type': "application/json",
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,PATCH,OPTIONS',
}

async function obtenerCategorias() {
  try {
    const response = await axios.get(`api/categoria`, { headers })

    if (response.status === 200) {
      globalData.categorias = response.data
    } else {
      globalData.categorias = []
    }

    pintarCategorias()
  } catch (error) {
    manejarError(error)
    globalData.categorias = []
    pintarCategorias()
  }
}

function pintarCategorias() {
  let htmlOption = '<option value="-1" disabled selected>Seleccionar</option>'

  htmlOption += globalData.categorias.map(value => {
    return `<option value="${value.id}">${value.nombre}</option>`
  }).join(' ')

  document.getElementById('categoriaBuscar').innerHTML = htmlOption
  document.getElementById('categoriaAgregarArticulo').innerHTML = htmlOption
  document.getElementById('categoriaEditarArticulo').innerHTML = htmlOption
}

async function obtenerArticulos() {
  try {
    const response = await axios.get(`api/articulo`, { params, headers })
    if (response.status === 200 && !response.data.estatus) {
      globalData.articulos = response.data
    } else {
      globalData.articulos = []
    }
    pintarArticulos()
  } catch (error) {
    manejarError (error)
    pintarArticulos()
  }
}

function pintarArticulos() {
  let htmlrows = ''
  htmlrows = globalData.articulos.map(value => {
    return `
      <tr>
        <td><p>${value.codigo}</p></td>
        <td><p>${value.nombre}</p></td>
        <td><p>${value.categoria.nombre}</p></td>
        <td><p>${getFecha(value.created_at)}</p></td>
        <td>
          <div class="opciones">
            <button class="btn-option option-editar-articulo" data-id="${value.id}"><i class="icon-pencil"></i></button>
            <button class="btn-option option-eliminar-articulo" data-id="${value.id}"><i class="icon-trash"></i></button>
          </div>
        </td>
      </tr>
    `
  }).join(' ')

  document.getElementById('bodyTableArticulos').innerHTML = htmlrows

  //  EDITAR
  document.querySelectorAll('.opciones .option-editar-articulo').forEach(item => {
    item.removeEventListener('click', null)
  })

  document.querySelectorAll('.opciones .option-editar-articulo').forEach(item => {
    item.addEventListener('click', event => {
      const modalEditarArticulo = document.getElementById('modalEditarArticulo')

      const idEditar = event.target.dataset.id
      globalData.idArticuloEditarActual = idEditar
      llenarModalEditar(+idEditar)

      modalEditarArticulo.classList.add('show')
    })
  })

  // ELIMINAR
  document.querySelectorAll('.opciones .option-eliminar-articulo').forEach(item => {
    item.removeEventListener('click', null)
  })

  document.querySelectorAll('.opciones .option-eliminar-articulo').forEach(item => {
    item.addEventListener('click', event => {
      const modalEliminarArticulo = document.getElementById('modalEliminarArticuloConfirmacion')

      const idEditar = event.target.dataset.id
      globalData.idArticuloEliminarActual = idEditar

      modalEliminarArticulo.classList.add('show')
    })
  })
}

function agregarTablaRows(nombreTabla, tipo = 1, caracteristica) {
  const table = document.getElementById(nombreTabla);
  let row = table.insertRow(-1); // We are adding at the end

  // Create table cells
  let c1 = row.insertCell(0);
  let c2 = row.insertCell(1);
  let c3 = row.insertCell(2);

  // Add data to c1 and c2
  c1.innerHTML = `<input class="form-control inputRow" name="caracteristicanombre${caracteristica?.id || ''}" input-table" type="text" />`
  c2.innerHTML = `<input class="form-control inputRow" name="caracteristicavalor${caracteristica?.id || ''}" input-table" type="text" />`
  c3.innerHTML = tipo === 1 ?
    `<div class="opciones"><button class="btn btn-option option-eliminar-caracteristica" data-id="${caracteristica?.id}" id="btnEliminarRow"><i class="icon-trash"></i></button></div>` :
    `<div class="opciones"><button class="btn btn-option option-agregar-caracteristica" data-id="${globalData.idArticuloEditarActual}" id="btnAgregarRow"><i class="icon-ok"></i></button></div>`

  if (caracteristica) {
    document.querySelector(`input[name="caracteristicanombre${caracteristica?.id}"]`).value = caracteristica?.nombre
    document.querySelector(`input[name="caracteristicavalor${caracteristica?.id}"]`).value = caracteristica?.valor
  }
}

// BUSCADOR
async function buscar() {
  const codigo = document.getElementById('codigoBuscar').value
  const nombre = document.getElementById('nombreBuscar').value
  const categoria = document.getElementById('categoriaBuscar').value

  if (codigo != "" && codigo != null) params.append('codigo', codigo)
  if (nombre != "" && nombre != null) params.append('nombre', nombre)
  if (categoria != null && parseInt(categoria) > 0) params.append('cat_id', categoria)

  obtenerArticulos()
}

// AGREGAR
async function validarConfirmarArticulo() {
  const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')
  const modalAgregarArticuloConfirmacion = document.getElementById('modalAgregarArticuloConfirmacion')
  let valido = true

  const categoria = document.getElementById('categoriaAgregarArticulo').value
  const nombre = document.getElementById('nombreAgregarArticulo').value
  const codigo = document.getElementById('codigoAgregarArticulo').value
  const { valid: validCaracteristicas } = await obtenerArryCaracteristicas("tbodyAgregarCaracteristicas", null)
  if (categoria == null || categoria == -1) {
    document.querySelector('#modalAgregarArticulo .group-control.categoria').classList.add('error')
    document.querySelector('#modalAgregarArticulo .group-control.categoria .msg').innerHTML = 'Seleccione una categoria'
    valido = false
  } else {
    document.querySelector('#modalAgregarArticulo .group-control.categoria').classList.remove('error')
  }

  if (!validCaracteristicas) {
    document.querySelector('#modalAgregarArticulo .group-control.caracteristicas').classList.add('error')
    document.querySelector('#modalAgregarArticulo .group-control.caracteristicas .msg').innerHTML = 'Los valores de los caracteristicas no pueden estar vacios'
    valido = false
  } else {
    document.querySelector('#modalAgregarArticulo .group-control.caracteristicas').classList.remove('error')
  }

  if (nombre == null || nombre == "") {
    document.querySelector('#modalAgregarArticulo .group-control.nombre').classList.add('error')
    document.querySelector('#modalAgregarArticulo .group-control.nombre .msg').innerHTML = 'Ingrese el nombre'
    valido = false
  } else {
    document.querySelector('#modalAgregarArticulo .group-control.nombre').classList.remove('error')
  }

  if (codigo == null || codigo <= 0) {
    document.querySelector('#modalAgregarArticulo .group-control.codigo').classList.add('error')
    document.querySelector('#modalAgregarArticulo .group-control.codigo .msg').innerHTML = 'Ingrese el codigo'
    valido = false
  } else {
    document.querySelector('#modalAgregarArticulo .group-control.codigo').classList.remove('error')
  }

  if (valido) {
    modalAgregarArticulo.classList.remove('show')
    modalAgregarArticuloConfirmacion.classList.add('show')
  }
}

async function guardarArticulo() {
  try {
    document.getElementById('modalAgregarArticuloConfirmacion').classList.remove('show')
    document.querySelector('#modalAgregarArticuloConfirmacion .alerta').classList.remove('show')
    document.getElementById('loadGeneral').classList.add('show')

    const categoria = document.getElementById('categoriaAgregarArticulo').value
    const nombre = document.getElementById('nombreAgregarArticulo').value
    const codigo = document.getElementById('codigoAgregarArticulo').value
    const { arrayCaracteristicas } = await obtenerArryCaracteristicas("tbodyAgregarCaracteristicas", null)

    const data = {
      cat_id: categoria,
      nombre,
      codigo,
      caracteristicas: arrayCaracteristicas
    }

    const response = await axios.post(`api/articulo`, data, { headers })

    if (response.status === 200 && !response.data.estatus) {
      obtenerArticulos()

      document.querySelector('#modalExito .msg-exito').innerHTML = 'Se creo correctamente el articulo.'

      document.getElementById('loadGeneral').classList.remove('show')
      document.getElementById('modalExito').classList.add('show')
      document.getElementById("tbodyAgregarCaracteristicas").innerHTML = '';
    } else {
      document.querySelector('#modalAgregarArticuloConfirmacion .alerta .msg').innerHTML = response.data?.msj || 'Ha ocurrido un error en el grabado.'
      document.getElementById('loadGeneral').classList.remove('show')
      document.querySelector('#modalAgregarArticuloConfirmacion .alerta').classList.add('show')
      document.getElementById('modalAgregarArticuloConfirmacion').classList.add('show')
    }
  } catch (error) {
    document.querySelector('#modalAgregarArticuloConfirmacion .alerta .msg').innerHTML = error.data?.msj || 'Ha ocurrido un error en el grabado.'
    document.getElementById('loadGeneral').classList.remove('show')
    document.querySelector('#modalAgregarArticuloConfirmacion .alerta').classList.add('show')
    document.getElementById('modalAgregarArticuloConfirmacion').classList.add('show')
  }
}

async function obtenerArryCaracteristicas(nombreTabla, rowElement) {
  const obtenerFila = document.getElementById(nombreTabla);
  let elementosFila = obtenerFila.getElementsByTagName("tr");
  const arrayCaracteristicas = []
  let rowValid = false
  let valid = true

  for (let i = 0; i < elementosFila.length; i++) {
    const nombre = elementosFila[i].childNodes[0].childNodes[0].value
    const valor = elementosFila[i].childNodes[1].childNodes[0].value
    const sameRow = elementosFila[i] == rowElement

    if (nombre === "" || valor === "") valid = false
    else {
      if (sameRow) rowValid = true
    }

    if (rowElement && sameRow) {
      arrayCaracteristicas.push({
        nombre,
        valor
      })
    } else if (!rowElement) {
      arrayCaracteristicas.push({
        nombre,
        valor
      })
    }

  }

  const finalValid = rowValid ? rowValid : valid

  return { arrayCaracteristicas, valid: finalValid }
}

// EDITAR
function llenarModalEditar(id) {
  let articuloEditar = {}

  for (const value of globalData.articulos) {
    if (id === value.id) {
      articuloEditar = value
      break
    }
  }

  document.getElementById('codigoEditarArticulo').value = articuloEditar.codigo
  document.getElementById('nombreEditarArticulo').value = articuloEditar.nombre
  document.getElementById('categoriaEditarArticulo').value = articuloEditar.categoria.id
  globalData.idArticuloEditarActual = articuloEditar.id

  for (const caracteristica of articuloEditar.caracteristicas) {
    agregarTablaRows("tbodyEditarCaracteristicas", 1, caracteristica)
  }
}

function validarFormularioEditarArticulo() {
  const modalEditarArticulo = document.getElementById('modalEditarArticulo')
  const modalEditarArticuloConfirmacion = document.getElementById('modalEditarArticuloConfirmacion')
  let valido = true

  const categoria = document.getElementById('categoriaEditarArticulo').value
  const nombre = document.getElementById('nombreEditarArticulo').value
  const codigo = document.getElementById('codigoEditarArticulo').value

  if (categoria == null || categoria == -1) {
    document.querySelector('#modalEditarArticulo .group-control.categoria').classList.add('error')
    document.querySelector('#modalEditarArticulo .group-control.categoria .msg').innerHTML = 'Seleccione una categoria'
    valido = false
  } else {
    document.querySelector('#modalEditarArticulo .group-control.categoria').classList.remove('error')
  }

  if (nombre == null || nombre == "") {
    document.querySelector('#modalEditarArticulo .group-control.nombre').classList.add('error')
    document.querySelector('#modalEditarArticulo .group-control.nombre .msg').innerHTML = 'Ingrese el nombre'
    valido = false
  } else {
    document.querySelector('#modalEditarArticulo .group-control.nombre').classList.remove('error')
  }

  if (codigo == null || codigo <= 0) {
    document.querySelector('#modalEditarArticulo .group-control.codigo').classList.add('error')
    document.querySelector('#modalEditarArticulo .group-control.codigo .msg').innerHTML = 'Ingrese el codigo'
    valido = false
  } else {
    document.querySelector('#modalEditarArticulo .group-control.codigo').classList.remove('error')
  }

  if (valido) {
    modalEditarArticulo.classList.remove('show')
    modalEditarArticuloConfirmacion.classList.add('show')
  }
}

async function guardarCaracteristica(tdElement) {
  const rowElement = tdElement.closest('tr')
  try {
    const { arrayCaracteristicas, valid } = await obtenerArryCaracteristicas("tbodyEditarCaracteristicas", rowElement)

    if (!valid) {
      document.querySelector('#modalEditarArticulo .group-control.caracteristicas').classList.add('error')
      document.querySelector('#modalEditarArticulo .group-control.caracteristicas .msg').innerHTML = 'El valor de la caracteristica no puede ser vacio.'
      return
    } else {
      document.querySelector('#modalEditarArticulo .group-control.caracteristicas').classList.remove('error')
    }

    const data = {
      nombre: arrayCaracteristicas[0].nombre,
      valor: arrayCaracteristicas[0].valor,
      articulo_id: globalData.idArticuloEditarActual
    }

    const response = await axios.post(`api/articulo/caracteristica`, data, { headers })

    if (response.status === 200 && !response.data.estatus) {
      tdElement.innerHTML = `<div class="opciones"><button data-id="${response?.data?.id}" class="btn btn-option option-eliminar-caracteristica" id="btnEliminarRow"><i class="icon-trash"></i></button></div>`
      obtenerArticulos()
    }

  } catch (error) {
    document.querySelector('#modalEditarArticulo .alerta .msg').innerHTML = error.data?.msj || 'Ha ocurrido un error en el grabado.'
  }
}

async function eliminarCaracteristica(idCaracteristica) {
  try {
    const response = await axios.delete(`api/articulo/caracteristica/${idCaracteristica}`, { headers })

    if (response.status === 200 && !response?.data?.estatus) {
      obtenerArticulos()
    } else {
      document.querySelector('#modalEditarArticulo .alerta .msg').innerHTML = response.data?.msj || 'Ha ocurrido un error al eliminar la caracteristicas.'
    }
  } catch (error) {
    document.querySelector('#modalEditarArticulo .alerta .msg').innerHTML = error.data?.msj || 'Ha ocurrido un error al eliminar la caracteristicas.'
  }
}

async function editarArticulo() {
  try {
    document.getElementById('modalEditarArticuloConfirmacion').classList.remove('show')
    document.querySelector('#modalEditarArticuloConfirmacion .alerta').classList.remove('show')
    document.getElementById('loadGeneral').classList.add('show')

    const categoria = document.getElementById('categoriaEditarArticulo').value
    const nombre = document.getElementById('nombreEditarArticulo').value

    const data = {
      cat_id: categoria,
      nombre
    }

    const response = await axios.put(`api/articulo/${globalData.idArticuloEditarActual}`, data, { headers })

    if (response.status === 200 && !response.data.estatus) {
      obtenerArticulos()

      document.querySelector('#modalExito .msg-exito').innerHTML = response.data?.msj || 'Se actualizó correctamente la articulo.'

      document.getElementById('loadGeneral').classList.remove('show')
      document.getElementById('modalExito').classList.add('show')
      document.getElementById("tbodyEditarCaracteristicas").innerHTML = '';
    } else {
      document.querySelector('#modalEditarArticuloConfirmacion .alerta .msg').innerHTML = response.data?.msj || 'Ha ocurrido un error al intentar actualizar la póliza.'
      document.getElementById('loadGeneral').classList.remove('show')
      document.querySelector('#modalEditarArticuloConfirmacion .alerta').classList.add('show')
      document.getElementById('modalEditarArticuloConfirmacion').classList.add('show')
    }
  } catch (error) {
    document.querySelector('#modalEditarArticuloConfirmacion .alerta .msg').innerHTML = error.data?.msj || 'Ha ocurrido un error al intentar actualizar la póliza.'
    document.getElementById('loadGeneral').classList.remove('show')
    document.querySelector('#modalEditarArticuloConfirmacion .alerta').classList.add('show')
    document.getElementById('modalEditarArticuloConfirmacion').classList.add('show')
  }
}

// ELIMINAR
async function eliminarArticulo() {
  try {
    document.getElementById('modalEliminarArticuloConfirmacion').classList.remove('show')
    document.querySelector('#modalEliminarArticuloConfirmacion .alerta').classList.remove('show')
    document.getElementById('loadGeneral').classList.add('show')

    const response = await axios.delete(`api/articulo/${globalData.idArticuloEliminarActual}`, { headers })

    if (response.status === 200 && !response.data.estatus) {
      obtenerArticulos()

      document.querySelector('#modalExito .msg-exito').innerHTML = response.data?.msj || 'Se eliminó correctamente la articulo.'

      document.getElementById('loadGeneral').classList.remove('show')
      document.getElementById('modalExito').classList.add('show')
    } else {
      document.querySelector('#modalEliminarArticuloConfirmacion .alerta .msg').innerHTML = response.data?.msj || 'Ha ocurrido un error al intentar eliminar la póliza.'
      document.getElementById('loadGeneral').classList.remove('show')
      document.querySelector('#modalEliminarArticuloConfirmacion .alerta').classList.add('show')
      document.getElementById('modalEliminarArticuloConfirmacion').classList.add('show')
    }
  } catch (error) {
    document.querySelector('#modalEliminarArticuloConfirmacion .alerta .msg').innerHTML = error.data?.msj || 'Ha ocurrido un error al intentar eliminar la póliza.'
    document.getElementById('loadGeneral').classList.remove('show')
    document.querySelector('#modalEliminarArticuloConfirmacion .alerta').classList.add('show')
    document.getElementById('modalEliminarArticuloConfirmacion').classList.add('show')
  }
}

document.addEventListener("DOMContentLoaded", function (event) {
  // CERRAR SESSION
  const buttonCerrarSession = document.getElementById('btnCerrarSesion')

  buttonCerrarSession.addEventListener('click', (event) => {
    localStorage.removeItem('token')
    window.location="login";
  })


  // BUSCADOR
  const buttonBuscar = document.getElementById('btnBuscador')

  buttonBuscar.addEventListener('click', (event) => {
    buscar()
  })

  // LIMPIAR
  const buttonLimpiar = document.getElementById('btnLimpiador')

  buttonLimpiar.addEventListener('click', (event) => {
    let codigo = document.getElementById('codigoBuscar')
    let nombre = document.getElementById('nombreBuscar')
    let categoria = document.getElementById('categoriaBuscar')
  
    codigo.value = ''
    nombre.value = ''
    categoria.value = -1
    
    params.delete('codigo')
    params.delete('codigo')
    params.delete('cat_id')
    obtenerArticulos()
  })

  // AGREGAR ARTICULO
  const buttonAgregarArticulo = document.getElementById('agregarArticulo')

  buttonAgregarArticulo.addEventListener('click', (event) => {
    const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')

    document.querySelector('#modalAgregarArticuloConfirmacion .alerta').classList.remove('show')
    document.getElementById('codigoAgregarArticulo').value = ''
    document.getElementById('categoriaAgregarArticulo').value = -1
    document.getElementById('nombreAgregarArticulo').value = ''

    modalAgregarArticulo.classList.add('show')
  })

  const buttonAgregarCaracteristca = document.getElementById('btnAgregarCaracteristica')
  buttonAgregarCaracteristca.addEventListener('click', (event) => {
    agregarTablaRows("tbodyAgregarCaracteristicas", 1)
  })

  const buttonAgregarEditarCaracteristica = document.getElementById('btnAgregarEditarCaracteristica')
  buttonAgregarEditarCaracteristica.addEventListener('click', (event) => {
    agregarTablaRows("tbodyEditarCaracteristicas", 2)
  })

  document.body.addEventListener('click', (event) => {
    if (event.target.id === btnRow.eliminar) {
      const idCaracteristica = parseInt(event.target.dataset.id)
      eliminarCaracteristica(idCaracteristica)

      event.target.closest("tr").remove();
    } else if (event.target.id === btnRow.agregar) {

      guardarCaracteristica(event.target.closest("td"))
    }
  })

  document.querySelector('#modalAgregarArticulo .group-control.categoria select').addEventListener('change', (event) => {
    document.querySelector('#modalAgregarArticulo .group-control.categoria').classList.remove('error')
  })

  document.querySelector('#modalAgregarArticulo .group-control.nombre input').addEventListener('change', (event) => {
    document.querySelector('#modalAgregarArticulo .group-control.nombre').classList.remove('error')
  })

  document.querySelector('#modalAgregarArticulo .group-control.codigo input').addEventListener('change', (event) => {
    document.querySelector('#modalAgregarArticulo .group-control.codigo').classList.remove('error')
  })

  const buttonCancelarAgregarArticulo = document.getElementById('cancelarAgregarArticulo')

  buttonCancelarAgregarArticulo.addEventListener('click', (event) => {
    const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')
    document.getElementById("tbodyAgregarCaracteristicas").innerHTML = '';
    modalAgregarArticulo.classList.remove('show')
  })

  const buttonCerrarAgregarArticulo = document.getElementById('cerrarAgregarArticulo')

  buttonCerrarAgregarArticulo.addEventListener('click', (event) => {
    const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')
    document.getElementById("tbodyAgregarCaracteristicas").innerHTML = '';
    modalAgregarArticulo.classList.remove('show')
  })

  const buttonAgregarArticuloModal = document.getElementById('aceptarAgregarArticulo')

  buttonAgregarArticuloModal.addEventListener('click', (event) => {
    validarConfirmarArticulo()
  })

  const buttonCancelarAgregarArticuloConfirmacion = document.getElementById('cancelarAgregarArticuloConfirmacion')

  buttonCancelarAgregarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')
    const modalAgregarArticuloConfirmacion = document.getElementById('modalAgregarArticuloConfirmacion')

    modalAgregarArticuloConfirmacion.classList.remove('show')
    modalAgregarArticulo.classList.add('show')
  })

  const buttonCerrarAgregarArticuloConfirmacion = document.getElementById('cerrarAgregarArticuloConfirmacion')

  buttonCerrarAgregarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalAgregarArticulo = document.getElementById('modalAgregarArticulo')
    const modalAgregarArticuloConfirmacion = document.getElementById('modalAgregarArticuloConfirmacion')

    modalAgregarArticuloConfirmacion.classList.remove('show')
    modalAgregarArticulo.classList.add('show')
  })

  const buttonAceptarAgregarArticuloConfirmacion = document.getElementById('aceptarAgregarArticuloConfirmacion')

  buttonAceptarAgregarArticuloConfirmacion.addEventListener('click', (event) => {
    guardarArticulo()
  })

  // EDITAR
  document.querySelectorAll('.opciones .option-editar-articulo').forEach(item => {
    item.addEventListener('click', event => {
      const modalEditarArticulo = document.getElementById('modalEditarArticulo')

      modalEditarArticulo.classList.add('show')
    })
  })

  const buttonCancelarEditarArticulo = document.getElementById('cancelarEditarArticulo')

  buttonCancelarEditarArticulo.addEventListener('click', (event) => {
    const modalEditarArticulo = document.getElementById('modalEditarArticulo')
    document.getElementById("tbodyEditarCaracteristicas").innerHTML = '';

    modalEditarArticulo.classList.remove('show')
  })

  const buttonCerrarEditarArticulo = document.getElementById('cerrarEditarArticulo')

  buttonCerrarEditarArticulo.addEventListener('click', (event) => {
    const modalEditarArticulo = document.getElementById('modalEditarArticulo')
    document.getElementById("tbodyEditarCaracteristicas").innerHTML = '';

    modalEditarArticulo.classList.remove('show')
  })

  const buttonEditarArticuloModal = document.getElementById('aceptarEditarArticulo')

  buttonEditarArticuloModal.addEventListener('click', (event) => {
    // validarConfirmarEditarArticulo()
    validarFormularioEditarArticulo()
  })

  const buttonCancelarEditarArticuloConfirmacion = document.getElementById('cancelarEditarArticuloConfirmacion')

  buttonCancelarEditarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalEditarArticulo = document.getElementById('modalEditarArticulo')
    const modalEditarArticuloConfirmacion = document.getElementById('modalEditarArticuloConfirmacion')

    modalEditarArticuloConfirmacion.classList.remove('show')
    modalEditarArticulo.classList.add('show')
  })

  const buttonCerrarEditarArticuloConfirmacion = document.getElementById('cerrarEditarArticuloConfirmacion')

  buttonCerrarEditarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalEditarArticulo = document.getElementById('modalEditarArticulo')
    const modalEditarArticuloConfirmacion = document.getElementById('modalEditarArticuloConfirmacion')

    modalEditarArticuloConfirmacion.classList.remove('show')
    modalEditarArticulo.classList.add('show')
  })

  const buttonAceptarEditarArticuloConfirmacion = document.getElementById('aceptarEditarArticuloConfirmacion')

  buttonAceptarEditarArticuloConfirmacion.addEventListener('click', (event) => {
    editarArticulo()
  })

  // ELIMINAR
  document.querySelectorAll('.opciones .option-eliminar-articulo').forEach(item => {
    item.addEventListener('click', event => {
      const modalEliminarArticulo = document.getElementById('modalEliminarArticuloConfirmacion')

      modalEliminarArticulo.classList.add('show')
    })
  })

  const buttonCancelarEliminarArticuloConfirmacion = document.getElementById('cancelarEliminarArticuloConfirmacion')

  buttonCancelarEliminarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalEliminarArticuloConfirmacion = document.getElementById('modalEliminarArticuloConfirmacion')

    modalEliminarArticuloConfirmacion.classList.remove('show')
  })

  const buttonCerrarEliminarArticuloConfirmacion = document.getElementById('cerrarEliminarArticuloConfirmacion')

  buttonCerrarEliminarArticuloConfirmacion.addEventListener('click', (event) => {
    const modalEliminarArticuloConfirmacion = document.getElementById('modalEliminarArticuloConfirmacion')

    modalEliminarArticuloConfirmacion.classList.remove('show')
  })

  const buttonCerrarExito = document.getElementById('cerrarExito')

  buttonCerrarExito.addEventListener('click', (event) => {
    const modalexito = document.getElementById('modalExito')

    modalexito.classList.remove('show')
  })

  const buttonAceptarExito = document.getElementById('aceptarExito')

  buttonAceptarExito.addEventListener('click', (event) => {
    const modalexito = document.getElementById('modalExito')

    modalexito.classList.remove('show')
  })

  const buttonAceptarEliminarArticuloConfirmacion = document.getElementById('aceptarEliminarArticuloConfirmacion')

  buttonAceptarEliminarArticuloConfirmacion.addEventListener('click', (event) => {
    eliminarArticulo()
  })
});

//AUXILIARES
function manejarError(error) {
  if (error.response.status == 401) {
    window.location="login";
  }
  console.log(error.message)
}

function checkToken() {
  var token = localStorage.getItem('token');
  if (!token) {
    window.location="login";
  }
}

function getFecha($fecha) {
  let x = new Date($fecha);
  return x.toLocaleString();
}

checkToken()
obtenerCategorias()
obtenerArticulos()