function solo_numeros(e) {
  // resivimos un eventos = e  por ejempo onkey o onkeyup
  var key = window.Event ? e.which : e.keyCode
  // condicional en linea que nos evalua lo que se esta digitando
  return ((key >= 48 && key <= 57) || (key == 8 || key == 9))

  // cada numero corresponde a un codigo de la tabla ascii, aqui indicamos que nos devuelva solo numeros, retroceso, tabulador
}
