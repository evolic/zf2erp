var valid = {
  'vat_rate': false,
  'price_netto': false,
  'price_brutto': false
};

$(document).ready(function() {
  $( "select#vat_rate" ).change(function() {
    var field = $(this);
    var vat_rate = field.val();

    if ( !vat_rate ) {
      addErrorMessage(field, valueIsRequired);
      valid['vat_rate'] = false;
    } else {
      addSuccessMessage(field, OK);
      valid['vat_rate'] = true;
    }

    if (valid['vat_rate'] && valid['price_netto']) {
      var input = $('input#price_brutto');
      var price_netto = $('input#price_netto').val();
      updatePriceBrutto(vat_rate, price_netto, input);
    } else if (valid['vat_rate'] && valid['price_brutto']) {
      var input = $('input#price_netto');
      var price_brutto = $('input#price_brutto').val();
      updatePriceNetto(vat_rate, price_brutto, input);
    }
  });

  $( "input#price_netto" ).blur(function() {
    var field = $(this);
    var price = field.val();

    if ( !price ) {
      addErrorMessage(field, valueIsRequired);
      valid['price_netto'] = false;
    } else if (isNaN(parseFloat(price)) || parseFloat(price) <= 0.0) {
      addErrorMessage(field, fieldValueIsInvalid);
      valid['price_netto'] = false;
    } else {
      addSuccessMessage(field, OK);
      valid['price_netto'] = true;
    }

    if (valid['vat_rate'] && valid['price_netto']) {
      var input = $('input#price_brutto');
      var vat_rate = $('select#vat_rate').val();
      updatePriceBrutto(vat_rate, price, input);
    }
  });

  $( "input#price_brutto" ).blur(function() {
    var field = $(this);
    var price = field.val();

    if ( !price ) {
      addErrorMessage(field, valueIsRequired);
      valid['price_brutto'] = false;
    } else if (isNaN(parseFloat(price)) || parseFloat(price) <= 0.0) {
      addErrorMessage(field, fieldValueIsInvalid);
      valid['price_brutto'] = false;
    } else {
      addSuccessMessage(field, OK);
      valid['price_brutto'] = true;
    }

    if (valid['vat_rate'] && valid['price_brutto']) {
      var input = $('input#price_netto');
      var vat_rate = $('select#vat_rate').val();
      updatePriceNetto(vat_rate, price, input);
    }
  });
});

function updatePriceBrutto(vat_rate, price_netto, field) {
  var data = {
    'product[vat_rate]': vat_rate,
    'product[price_netto]': price_netto
  };

  getPrice(data, field);
}

function updatePriceNetto(vat_rate, price_brutto, field) {
  var data = {
    'product[vat_rate]': vat_rate,
    'product[price_brutto]': price_brutto
  };

  getPrice(data, field);
}

function getPrice(data, field) {
  $.ajax({
    url: calculatePriceUrl,
    data: data,
    type: 'POST'
  })
  .done(function(response, textStatus, jqXHR) {
    if (response.success) {
      field.val(response.price);
      addSuccessMessage(field, response.message);
    } else {
      addErrorMessage(field, response.message);
    }
  })
  .fail(function(jqXHR, textStatus, errorThrown) {
    switch (jqXHR.status) {
      case 400:
        var response = jQuery.parseJSON(jqXHR.responseText);
        if (!response.success) {
          console.log(response.messages);
          for (var fieldset in response.messages) {
            for (var field_name in response.messages[fieldset]) {
              var field = $('#'+field_name);
              var messages = response.messages[fieldset][field_name];
              addErrorMessages(field, messages);
            }
          }
        }
        break;
      default:
    }
  });
}

function addSuccessMessage(field, message) {
  addMessage(field, message, 'has-success');
}

function addErrorMessage(field, message) {
  addMessage(field, message, 'has-error');
}

function addMessage(field, message, type) {
  var parent = field.parent();

  clearMessage(parent);
  parent.addClass(type);

  parent.append('<ul>');
  parent.find('ul').append('<li>'+message+'</li>');
}

function addErrorMessages(field, messages) {
  addMessages(field, messages, 'has-error');
}

function addMessages(field, messages, type) {
  var parent = field.parent();

  clearMessage(parent);
  parent.addClass(type);

  parent.append('<ul>');
  for (var i in messages) {
    parent.find('ul').append('<li>'+messages[i]+'</li>');
  }
}

function clearMessage(parent) {
  if (parent.hasClass('has-success')) {
    parent.removeClass('has-success');
  }
  if (parent.hasClass('has-error')) {
    parent.removeClass('has-error');
  }

  parent.find('ul').remove();
}
