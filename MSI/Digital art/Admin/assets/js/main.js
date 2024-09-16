// add hovered class to selected list item
let list = document.querySelectorAll(".navigation li");

function activeLink() {
  list.forEach((item) => {
    item.classList.remove("hovered");
  });
  this.classList.add("hovered");
}

list.forEach((item) => item.addEventListener("mouseover", activeLink));

// Menu Toggle
let toggle = document.querySelector(".toggle");
let navigation = document.querySelector(".navigation");
let main = document.querySelector(".main");

toggle.onclick = function () {
  navigation.classList.toggle("active");
  main.classList.toggle("active");
};

$(document).ready(function () {
  // Read products
  function loadProducts() {
      $.ajax({
          url: 'ajax/read.php',
          method: 'GET',
          success: function (data) {
              if (data.error) {
                  alert('Error: ' + data.error);
                  return;
              }

              let products = data;
              let table = '';
              products.forEach(function (product) {
                  table += `<tr>
                      <td>${product.p_id}</td>
                      <td>${product.product_name}</td>
                      <td>${product.product_price}</td>
                      <td><button class="edit-btn" data-id="${product.p_id}">Edit</button></td>
                      <td><button class="delete-btn" data-id="${product.p_id}">Delete</button></td>
                  </tr>`;
              });
              $('#productTable tbody').html(table);
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
          }
      });
  }
  loadProducts();

  // Add product
  $('#addProduct').on('submit', function (e) {
      e.preventDefault();
      $.ajax({
          url: 'ajax/add.php',
          method: 'POST',
          data: $(this).serialize(),
          success: function (response) {
              alert(response);
              loadProducts();
              $('#addProduct')[0].reset();
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
          }
      });
  });

  // Edit product
  $(document).on('click', '.edit-btn', function () {
      let id = $(this).data('id');
      let row = $(this).closest('tr');
      let name = row.find('td:eq(1)').text();
      let price = row.find('td:eq(2)').text();

      $('#product_id').val(id);
      $('#product_name').val(name);
      $('#product_price').val(price);
      $('#updateProduct').show();
      $('#addProduct').hide();
  });

  // Update product
  $('#updateProduct').on('submit', function (e) {
      e.preventDefault();
      $.ajax({
          url: 'ajax/update.php',
          method: 'POST',
          data: $(this).serialize(),
          success: function (response) {
              alert(response);
              loadProducts();
              $('#updateProduct')[0].reset();
              $('#updateProduct').hide();
              $('#addProduct').show();
          },
          error: function (jqXHR, textStatus, errorThrown) {
              alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
          }
      });
  });

  // Delete product
  $(document).on('click', '.delete-btn', function () {
      if (confirm('Are you sure you want to delete this product?')) {
          let id = $(this).data('id');
          $.ajax({
              url: 'ajax/delete.php',
              method: 'POST',
              data: { p_id: id },
              success: function (response) {
                  alert(response);
                  loadProducts();
              },
              error: function (jqXHR, textStatus, errorThrown) {
                  alert('AJAX error: ' + textStatus + ' : ' + errorThrown);
              }
          });
      }
  });
});
