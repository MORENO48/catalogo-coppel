<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Catalogo | Coppel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ Vite::asset('resources/icons/css/fontello.css') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <main>
      <section id="sideBar">
        <button class="btn" id="btnCerrarSesion" type="button">cerrar sesion</button>
      </section>
      <section id="main-container">
        <section class="titulo">
          <h1>Articulos</h1>
        </section>
  
        <section class="wrapper-options">
          <div class="form wrapper-filtros">
            <div class="container-filtros">
              <div class="group-control">
                <button id="agregarArticulo" type="button" class="btn btn-primary">
                  <span>Agregar</span>
                </button>
              </div>
              <div class="group-control">
                <input class="form-control" placeholder="codigo" type="text" name="codigoArticulo" id="codigoBuscar" />
              </div>
              <div class="group-control">
                <input class="form-control" placeholder="nombre del articulo" type="text" name="nombreArticulo" id="nombreBuscar" />
              </div>
              <div class="group-control">
                <select class="form-control" name="categorias" id="categoriaBuscar">
                  <option value="-1" disabled selected>Cargando...</option>
                </select>
              </div>
              <div class="group-control">
                <button class="btn btn-cancel" type="button" id="btnBuscador">Buscar</button>
              </div>
              <div class="group-control">
                <button class="btn" type="button" id="btnLimpiador">Limpiar</button>
              </div>
            </div>

          </div>
        </section>
  
        <section class="wrapper-tabla">
          <div class="container-table">
            <table>
              <thead>
                <tr>
                  <th>Codigo</th>
                  <th>Nombre</th>
                  <th>Categoria</th>
                  <th>Fecha registro</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody id="bodyTableArticulos"></tbody>
            </table>
          </div>
        </section>
      </section>
    </main>

    <!-- MODAL AGREGAR -->
    <div id="modalAgregarArticulo" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal">Agregar articulo</h2>
          <button id="cerrarAgregarArticulo" class="btn close-modal">
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <div class="form">
            <div class="group-control codigo">
              <label for="">Codigo</label>
              <input
                id="codigoAgregarArticulo"
                type="number"
                class="form-control"
                placeholder="Ingresar codigo"
              />
              <p class="msg"></p>
            </div>

            <div class="group-control nombre">
              <label for="">Nombre</label>
              <input
                id="nombreAgregarArticulo"
                type="text"
                class="form-control"
                placeholder="Ingresar nombre"
              />
              <p class="msg"></p>
            </div>

            <div class="group-control categoria">
              <label for="">Categoria</label>
              <select id="categoriaAgregarArticulo" class="form-control">
                <option value="-1" disabled selected>Cargando...</option>
              </select>
              <p class="msg"></p>
            </div>

            <div class="group-control caracteristicas">
              <label for="">Caracteristicas</label>
              <div class="container-table">
                <table id="agregarCaracteristica">
                  <thead>
                    <tr>
                      <th>Caracteristica</th>
                      <th>Valor</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyAgregarCaracteristicas"></tbody>
                </table>
              </div>
              <button class="btn" id="btnAgregarCaracteristica" type="button">+</button>
              <p class="msg"></p>
            </div>
          </div>

          <div class="alerta">
            <p class="msg">Mensaje de error de prueba</p>
            <span class="cerrar-alerta">
              <i class="icon-cancel"></i>
            </span>
          </div>

          <div class="acciones">
            <button
              id="cancelarAgregarArticulo"
              type="button"
              class="btn btn-cancel"
            >
              <span>Cancelar</span>
            </button>
            <button
              id="aceptarAgregarArticulo"
              type="button"
              class="btn btn-primary"
            >
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div id="modalAgregarArticuloConfirmacion" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal">Confirmación</h2>
          <button
            id="cerrarAgregarArticuloConfirmacion"
            class="btn close-modal"
          >
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <p class="msg-confirmacion">
            ¿Está seguro que desea registrar el articulo?
          </p>

          <div class="alerta">
            <p class="msg"></p>
            <span class="cerrar-alerta">
              <i class="icon-cancel"></i>
            </span>
          </div>

          <div class="acciones">
            <button
              id="cancelarAgregarArticuloConfirmacion"
              type="button"
              class="btn btn-cancel"
            >
              <span>Cancelar</span>
            </button>
            <button
              id="aceptarAgregarArticuloConfirmacion"
              type="button"
              class="btn btn-primary"
            >
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL EDITAR -->
    <div id="modalEditarArticulo" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal">Editar articulo</h2>
          <button id="cerrarEditarArticulo" class="btn close-modal">
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <div class="form">
            <div class="group-control codigo">
              <label for="">Codigo</label>
              <input class="form-control" type="text" name="codigo" id="codigoEditarArticulo" disabled>
              <p class="msg"></p>
            </div>

            <div class="group-control nombre">
              <label for="">nombreArticulo</label>
              <input class="form-control" type="text" name="nombreArticulo" id="nombreEditarArticulo">
              <p class="msg"></p>
            </div>

            <div class="group-control categoria">
              <label for="">Categoria</label>
              <select id="categoriaEditarArticulo" class="form-control">
                <option value="-1" disabled selected>Cargando...</option>
              </select>
              <p class="msg"></p>
            </div>

            <div class="group-control caracteristicas">
              <label for="">Caracteristicas</label>
              <div class="container-table">
                <table id="agregarEditarCaracteristica">
                  <thead>
                    <tr>
                      <th>Caracteristica</th>
                      <th>Valor</th>
                      <th>Opciones</th>
                    </tr>
                  </thead>
                  <tbody id="tbodyEditarCaracteristicas"></tbody>
                </table>
              </div>

              <button class="btn" id="btnAgregarEditarCaracteristica" type="button">+</button>
              <p class="msg"></p>
            </div>
          </div>

          <div class="alerta">
            <p class="msg">Mensaje de error de prueba</p>
            <span class="cerrar-alerta">
              <i class="icon-cancel"></i>
            </span>
          </div>

          <div class="acciones">
            <button
              id="cancelarEditarArticulo"
              type="button"
              class="btn btn-cancel"
            >
              <span>Cancelar</span>
            </button>
            <button
              id="aceptarEditarArticulo"
              type="button"
              class="btn btn-primary"
            >
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <div id="modalEditarArticuloConfirmacion" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal">Confirmación</h2>
          <button id="cerrarEditarArticuloConfirmacion" class="btn close-modal">
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <p class="msg-confirmacion">
            ¿Está seguro que desea modificar el articulo?
          </p>

          <div class="alerta">
            <p class="msg"></p>
            <span class="cerrar-alerta">
              <i class="icon-cancel"></i>
            </span>
          </div>

          <div class="acciones">
            <button
              id="cancelarEditarArticuloConfirmacion"
              type="button"
              class="btn btn-cancel"
            >
              <span>Cancelar</span>
            </button>
            <button
              id="aceptarEditarArticuloConfirmacion"
              type="button"
              class="btn btn-primary"
            >
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ELIMINAR -->
    <div id="modalEliminarArticuloConfirmacion" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal">Confirmación</h2>
          <button
            id="cerrarEliminarArticuloConfirmacion"
            class="btn close-modal"
          >
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <p class="msg-confirmacion">
            ¿Está seguro que desea eliminar el articulo?
          </p>

          <div class="alerta">
            <p class="msg">Mensaje de error de prueba</p>
            <span class="cerrar-alerta">
              <i class="icon-cancel"></i>
            </span>
          </div>

          <div class="acciones">
            <button
              id="cancelarEliminarArticuloConfirmacion"
              type="button"
              class="btn btn-cancel"
            >
              <span>Cancelar</span>
            </button>
            <button
              id="aceptarEliminarArticuloConfirmacion"
              type="button"
              class="btn btn-primary"
            >
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL EXITO -->
    <div id="modalExito" class="wrapper-modal">
      <div class="mask"></div>
      <div class="container-modal">
        <div class="head-modal">
          <h2 class="title-modal"></h2>
          <button id="cerrarExito" class="btn close-modal">
            <i class="icon-cancel"></i>
          </button>
        </div>
        <div class="body-modal">
          <div class="vector">
            <i class="icon-ok"></i>
          </div>
          <p class="msg-exito"></p>
          <div class="acciones">
            <button id="aceptarExito" type="button" class="btn btn-primary">
              <span>Aceptar</span>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- LOAD -->
    <div id="loadGeneral" class="wrapper-load">
      <div class="cssload-container-general">
        <div class="cssload-internal">
          <div class="cssload-ballcolor cssload-ball_1">&nbsp;</div>
        </div>
        <div class="cssload-internal">
          <div class="cssload-ballcolor cssload-ball_2">&nbsp;</div>
        </div>
        <div class="cssload-internal">
          <div class="cssload-ballcolor cssload-ball_3">&nbsp;</div>
        </div>
        <div class="cssload-internal">
          <div class="cssload-ballcolor cssload-ball_4">&nbsp;</div>
        </div>
      </div>
    </div>
  </body>
</html>
