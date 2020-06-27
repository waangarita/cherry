function solo_numeros(e) {
  // resivimos un eventos = e  por ejempo onkey o onkeyup
  var key = window.Event ? e.which : e.keyCode
  // condicional en linea que nos evalua lo que se esta digitando
  return ((key >= 48 && key <= 57) || (key === 8 || key === 9))

  // cada numero corresponde a un codigo de la tabla ascii, aqui indicamos que nos devuelva solo numeros, retroceso, tabulador
}

function number_format(amount, decimals) {
  amount += '' // por si pasan un numero en vez de un string
  amount = parseFloat(amount.replace(/[^0-9\.]/g, '')) // elimino cualquier cosa que no sea numero o punto

  decimals = decimals || 0 // por si la variable no fue fue pasada

  // si no es un numero o es igual a cero retorno el mismo cero
  if (isNaN(amount) || amount === 0) {
    return parseFloat(0).toFixed(decimals)
  }

  // si es mayor o menor que cero retorno el valor formateado como numero
  amount = '' + amount.toFixed(decimals)

  var amount_parts = amount.split('.'),
    regexp = /(\d+)(\d{3})/

  while (regexp.test(amount_parts[0])) {
    amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2')
  }

  return amount_parts.join('.')
}

function searchImage(idFamily, e) {
  e.src = URL_IMAGES + '/products/no-image.jpg'

  setTimeout(function () {
    let img = idFamily + '_default.jpg'
    let url = URL_IMAGES + `products/${img}`
    fetch(url).then(function (res) {
      res.status === 404 ? e.src = URL_IMAGES + 'products/no-image.jpg' : e.src = URL_IMAGES + `products/${img}`
    }).catch(error => {
      console.log(error)
    })
  }, 500)
}

function no_caracteres(e) {
  // resivimos un eventos = e  por ejempo onkey o onkeyup
  var key = window.Event ? e.which : e.keyCode
  // condicional en linea que nos evalua lo que se esta digitando
  return ((key >= 48 && key <= 58) || (key >= 65 && key <= 90) || (key >= 97 && key <= 122) || (key === 8 || key === 9 || key === 32))
}

function validarBrand() {
  let codeBrand = $('#id_brand').val()
  let codeFamily = $('#code').val()

  codeBrand = codeBrand.substr(0, 2)
  codeFamily = codeFamily.substr(0, 2)

  if (codeBrand === codeFamily) {
    return true
  } else {
    $('#message').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> El codigo de la linea no coincide con la marca seleccionada </div>')
    return false
  }
}

function validarFamily() {
  let codeFamily = $('#id_family').val()
  let codeProduct = $('#code').val()

  codeProduct = codeProduct.substr(0, 4)

  if (codeProduct === codeFamily) {
    return true
  } else {
    $('#message').html('<div class="alert alert-danger"><i class="fa fa-times-circle"></i> El codigo del producto no coincide con la linea seleccionada </div>')
    return false
  }
}

function confirmSendOrder() {
  swal({
      title: ATTENTION,
      text: SEND_ORDER,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#81c784',
      confirmButtonText: ACCEPT,
      cancelButtonText: CANCEL,
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function (isConfirm) {
      document.getElementById('form_cart').submit()
      swal(SENDED, ORDER_SENDED, 'success')
    })
}

function confirmDuplicate() {
  swal({
      title: ATTENTION,
      text: DUPLICATE,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#81c784',
      confirmButtonText: ACCEPT,
      cancelButtonText: CANCEL,
      closeOnConfirm: false,
      closeOnCancel: true
    },
    isConfirm => {
      document.getElementById('form_detail_order').submit()
    })
}

function addCodeBrand() {
  var codeBrand = $('#id_brand').val()
  codeBrand = codeBrand.substr(0, 2)
  $('#code').val(codeBrand)
}

function add_cart(product, userId, e) {
  e.preventDefault()
  var cant = $('#' + product).val()

  if (cant === '') {
    $('#' + product).focus().css('border-color', 'red')
    return false
  } else {
    $('#' + product).focus().css('border-color', '')
  }

  $.ajax({
    url: API + 'saveProductCart.json',
    method: 'POST',
    data: {
      id_product: product,
      amount: cant,
      id_user: userId
    },
    beforeSend: function () {
      $('#btn' + product).html('<i style="font-size:15pt;" class="fa fa-spin fa-spinner text-primary"></i>')
    }
  }).done(function (res) {
    if (res.success === true) {
      $('#btn' + product).html(`<a class="success-btn-prod"><i class="fa fa-check-circle mr-5"></i>${ORDERED}</a>`)
      $(`div[data-code="${product}"]`).html('')
      getCartByUser(userId)
    } else {
      $('#btn' + product).html(`<a href="" onclick="add_cart('${product}',${userId}, event)"><i class="fa fa-cart-plus mr-5"></i>${ADD_CART}</a>`)
    }
  })
}

function getCartByUser(idUser) {
  $.ajax({
    url: API + `getCartByUser/${idUser}.json`,
    method: 'GET'
  }).done(function (res) {
    if (res.success === true) {
      let cart = ''
      let cart_side = ''
      let subtotal = 0
      let countCart = res.data.length

      if (countCart === 0) {
        cart += '<li class="text-center"> ' + NOT_FOUND + ' </li>'
        $('.cart-footer').hide()
      } else {
        $('.cart-footer').show()
        $.each(res.data, (i, product) => {
          cart += `<li data-id="${product.id}">
                    <a href="${ROOT}site/detail_product/${product.tbl_product.code}" class="product-image">
                      <img src="${ROOT+product.tbl_product.img.substr(1)}" alt="${product.tbl_product.code}">
                    </a>
                    <div class="product-details">
                      <div class="close-icon">
                        <a href="" onclick="delete_cart(${product.id}, '${DELETE} ${product.tbl_product.type_product} ?', event)" ><i class="fa fa-close"></i></a>
                      </div>
                      <p class="product-name">
                        <a href="${ROOT}site/detail_product/${product.tbl_product.code}">${product.tbl_product.type_product}</a>
                      </p>
                      <strong>${product.amount}</strong> x <span class="price text-primary">$ ${number_format(product.price, 2)}</span>
                    </div>
                  </li>`

          cart_side += `<li data-id="${product.id}" data-product="${product.tbl_product.code}" data-user="${idUser}">
                    <a href="${ROOT}site/detail_product/${product.tbl_product.code}" class="product-image">
                      <img src="${ROOT+product.tbl_product.img.substr(1)}" alt="${product.tbl_product.code}">
                    </a>
                    <div class="product-details">
                      <div class="close-icon">
                        <a href="" onclick="delete_cart(${product.id}, '${DELETE} ${product.tbl_product.type_product} ?', event)" ><i class="fa fa-close"></i></a>
                      </div>
                      <p class="product-name">
                        <a href="${ROOT}site/detail_product/${product.tbl_product.code}">${product.tbl_product.type_product}</a>
                      </p>
                      <strong>${product.amount}</strong> x <span class="price text-primary">$ ${number_format(product.price, 2)}</span>
                    </div>
                  </li>`

          subtotal += product.amount * product.price
        })
      }

      $('span[data-id="subtotalCart"]').html( `$ ${number_format(subtotal, 2)}`)
      $('#count_cart').html(`( ${countCart} )`)
      $('#items_cart').html(cart)
      $('ul[data-id="itemsCart_side"]').html(cart_side)
    }
  })
}

function delete_cart(id, msg, e) {
  e.preventDefault()
  swal({
      title: SURE,
      text: msg,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#81c784',
      confirmButtonText: ACCEPT,
      cancelButtonText: CANCEL,
      closeOnConfirm: false,
      closeOnCancel: true
    },
    isConfirm => {
      if (isConfirm) {
        $.ajax({
          url: API + `deleteProductCart/${id}.json`,
          method: 'POST'
        }).done(function (res) {
          if (res.success === true) {
            swal(DELETED, DELETED_PRODUCT, 'success')
            deleteItemCart(id)
          }
        })
      }
    })
}

function deleteItemCart(idProd){

  var product = $(`ul[data-id="itemsCart_side"] li[data-id="${idProd}"]`).data('product');
  var user = $(`ul[data-id="itemsCart_side"] li[data-id="${idProd}"]`).data('user');
  var amount = $(`div.hidden-xs ul[data-id="itemsCart_side"] li[data-id="${idProd}"] strong`).text();
  var price = $(`div.hidden-xs ul[data-id="itemsCart_side"] li[data-id="${idProd}"] span`).text();
  var subtotal = $('div.hidden-xs span[data-id="subtotalCart"]').text();

  subtotal = parseFloat(subtotal.replace(/[\$]/,''));
  price = parseFloat(price.replace(/[\$]/,''));

  subtotal -= amount * price;

  $(`*[data-id="${idProd}"]`).remove();

  $('#count_cart').html($("#items_cart").children().length);
  $('#btn' + product).html(`<a href="" onclick="add_cart('${product}',${user}, event)"><i class="fa fa-cart-plus mr-5"></i>${ADD_CART}</a>`);
  $('span[data-id="subtotalCart"]').html( `$ ${number_format(subtotal, 2)}`);
  $(`div[data-code="${product}"]`).html(`<span class="col-md-6 col-xs-6">Cant.</span><div class="col-md-6 col-xs-6"><input type="number" min="1" title="Requerido" class="form-control" name="${product}" id="${product}" onkeypress="javascript:return solo_numeros ( event )"></div>`);
}

function updatePrices(amount, id) {
  /*
    amount = cantidad del producto que se quiere
    id = id del registro que esta en tbl_cart
  */
  $.ajax({
    url: API + `updateAmount/${amount}/${id}.json`,
    method: 'POST'
  }).done(function (res) {
    if (res.success === true) {
      console.log(res)
      // Capturo el valor de cada producto por unidad
      let priceProduct = $(`#priceProduct${id}`).val()
      // Defino una variable que sera el total por producto , precio del producto * cantidad
      // La funcion number_format(precio, separador) toma un precio y formatea por la cantidad de decimales despues del punto
      let totalByProduct = `$ ${number_format(priceProduct * amount, 2)}`
      // Aqui seteamos en el html ese valor
      $(`#totalByProduct${id}`).html(totalByProduct)
      // Hay un campo hidden/oculto donde tambien vamos a guardar ese valor para ya despues hacer la suma total de la orden
      $(`#total${id}`).val(priceProduct * amount)

      // Hacemos la suma total de la orden ejecutando esta funcion
      totalOrder()
    }
  })
}

function totalOrder() {
  // el precio de los productos inicia en 0
  let products = 0
  /*
    los campos ocultos que guardan el total por producto tienen una clase llamada 'totalProduct'
    tomamos esa clase y la pasamos a una variable
  */
  let priceProduct = $('.totalProduct')

  // con un for recorremos todos los campos ocultos y sacamos el valor de cada uno de ellos y los vamos sumando y guardando en una variable
  for (let price of priceProduct) {
    products += parseFloat(price.defaultValue)
  }

  // labelTotalOrder es un H3 que esta mostrando el valor total de todo el pedido a este lo formateamos y le ponemos el signos $ antes
  $('#labelTotalOrder').html(`$${number_format(products, 2)}`)
}

function language(lan, e) {
  e.preventDefault()
  $.ajax({
    url: API + `changeLanguage/${lan}.json`,
    method: 'GET'
  }).done(function (res) {
    if (res.success) {
      location.reload()
    }
  })
}

// if (mq) {
//   $('.jp-previous').html('<<')
//   $('.jp-next').html('>>')
// }