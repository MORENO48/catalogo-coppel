<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Coppel | Login</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet" />
  @vite(['resources/css/app.css','resources/js/inicial.js'])
</head>
<body>
  <section>
    <div class="login">
      <div class="contenedor-principal">
        <div class="container-login pt-md-5">
          <div class="icon d-flex align-items-center justify-content-center">
            <img src="{{ Vite::asset('resources/images/og_coppel.webp') }}">
          </div>
          <form class="form-floating login-form">
            <div class="group-control">
              <label for="correo">Correo electrónico</label>
              <input
                id="inputEmail"
                type="email"
                class="form-control"
                placeholder="Correo electrónico"
                id="correo"
                required
              />
            </div>
            <div class="group-control">
              <label for="password">Contraseña</label>
              <input
                id="inputPassword"
                type="password"
                class="form-control rounded-left"
                placeholder="Contraseña"
                id="password"
                required
              />
            </div>
            <div class="form-group">
              <button type="button" class="btn btn-primary submit" id="btnIniciar">
                Iniciar sesión
              </button>
            </div>
          </form>
          <div id="loginError"></div>
          <div class="footer">
            <table>
              <tbody>
                <tr>
                  <td>
                    <p>© 2023 Coppel</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>