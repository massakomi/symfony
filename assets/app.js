/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
//import './bootstrap';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything
require('bootstrap');


$('.add2basket').click(function() {
  $.get(`/basket/add?id=${this.dataset.id}`, function(res) {
      if (res.error) {
        alert(res.error)
      } else {
        this.innerHTML = 'В корзине'
      }
  }.bind(this));
})

function basketUpdate(id, quantity) {
  $.get(`/basket/update?id=${id}&quantity=${quantity}`, function(res) {
    location = '/basket'
  }.bind(this));
}

$('.basket-plus').click(function() {
  let id = $(this).parent().data('id')
  let q = $(this).parent().find('[name="quantity"]').val()
  basketUpdate(id,  parseInt(q) + 1)
})

$('[name="quantity"]').change(function() {
  let id = $(this).parent().data('id')
  basketUpdate(id, this.value)
})

$('.basket-minus').click(function() {
  let id = $(this).parent().data('id')
  let q = $(this).parent().find('[name="quantity"]').val()
  basketUpdate(id, parseInt(q) - 1)
})

// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');

/*$(document).ready(function() {
  $('[data-toggle="popover"]').popover();
});*/
