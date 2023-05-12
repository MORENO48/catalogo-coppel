function validarLogin() {
    const correo = document.getElementById('inputEmail').value
    const password = document.getElementById('inputPassword').value
    let valid = true
  
    if (correo == null || correo == '') valid = false
  
    if (password == null || password == '') valid = false
  
    if (valid) login()
    else {
      document.getElementById('loginError').innerHTML = 'Parametros incompletos'
    }
  }
  
  async function login() {
    try {
  
      const data = {
        email: document.getElementById('inputEmail').value,
        password: document.getElementById('inputPassword').value
      }
      const response = await await axios.post(`api/login`, data)
  
      if (response.status === 200 && !response?.data?.estatus) {
        localStorage.setItem('token', response.data.token)
        window.location="home";
      } else {
        document.getElementById('loginError').innerHTML = response.data?.msj || 'Ha ocurrido un error en el grabado.'
      }
    } catch (error) {
      document.getElementById('loginError').innerHTML = error.data?.msj || 'Ha ocurrido un error en el grabado.'
    }
  }
  
document.addEventListener("DOMContentLoaded", function (event) {
  const buttonLogin = document.getElementById('btnIniciar')

  buttonLogin.addEventListener('click', (event) => {
    validarLogin()
  })
})