<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice Generator</title>
  <meta name="description" content="The small framework with powerful features">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="shortcut icon" type="image/png" href="/favicon.ico" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- STYLES -->

  <style>
    * {
      transition: background-color 300ms ease, color 300ms ease;
    }

    *:focus {
      background-color: rgba(221, 72, 20, .2);
      outline: none;
    }

    html,
    body {
      color: rgba(33, 37, 41, 1);
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji";
      font-size: 16px;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
      text-rendering: optimizeLegibility;
    }

    header {
      background-color: rgba(247, 248, 249, 1);
      padding: .4rem 0 0;
    }

    .menu {
      padding: .4rem 2rem;
    }

    header ul {
      border-bottom: 1px solid rgba(242, 242, 242, 1);
      list-style-type: none;
      margin: 0;
      overflow: hidden;
      padding: 0;
      text-align: right;
    }

    header li {
      display: inline-block;
    }

    header .logo {
      float: left;
      height: 44px;
      padding: .4rem .5rem;
      font-size: 26px;
      font-weight: bold;
    }

    section {
      margin: 0 auto;
      max-width: 1100px;
      padding: 2.5rem 1.75rem 3.5rem 1.75rem;
    }

    .further {
      background-color: rgba(247, 248, 249, 1);
      border-bottom: 1px solid rgba(242, 242, 242, 1);
      border-top: 1px solid rgba(242, 242, 242, 1);
    }

    footer {
      background-color: rgba(221, 72, 20, .8);
      text-align: center;
    }

    footer .copyrights {
      background-color: rgba(62, 62, 62, 1);
      color: rgba(200, 200, 200, 1);
      padding: .25rem 1.75rem;
    }
  </style>
</head>

<body>

  <!-- HEADER: MENU + HEROE SECTION -->
  <header>

    <div class="menu">
      <ul>
        <li class="logo">
          Invoice Generator
        </li>

      </ul>
    </div>

  </header>

  <!-- CONTENT -->

  <section>
    <?php if (!empty($alert)) : ?>
      <div class="alert alert-<?= $alert['class'] ?>" role="alert">
        <?= $alert['message'] ?>
      </div>
    <?php endif; ?>

    <?= form_open() ?>
    <?php if (!empty($productDetails)) : ?>
      <input type="hidden" name="id" value="<?= $productDetails['id'] ?>">
    <?php endif; ?>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Quantity</th>
          <th>Unit Price (in $)</th>
          <th>Tax</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <input type="text" name="name" value="<?= !empty($validation) ? set_value('name') : (!empty($productDetails) ? $productDetails['name'] : '') ?>" class="form-control">
            <?= !empty($validation) ? $validation->getError('name') : '' ?>
          </td>
          <td>
            <input type="text" name="quantity" value="<?= !empty($validation) ? set_value('quantity') : (!empty($productDetails) ? number_format($productDetails['quantity'], 2) : '') ?>" class="form-control">
            <?= !empty($validation) ? $validation->getError('quantity') : '' ?>
          </td>
          <td>
            <input type="text" name="price" value="<?= !empty($validation) ? set_value('price')  : (!empty($productDetails) ? number_format($productDetails['price'], 2) : '') ?>" class="form-control">
            <?= !empty($validation) ? $validation->getError('price') : '' ?>
          </td>
          <td>
            <select name="tax" class="form-select">
              <option value="0" <?= !empty($validation) ? set_select('tax', '0') : (!empty($productDetails) && $productDetails['tax'] == '0' ? 'selected' : '') ?>>0%</option>
              <option value="1" <?= !empty($validation) ? set_select('tax', '1') : (!empty($productDetails) && $productDetails['tax'] == '1' ? 'selected' : '') ?>>1%</option>
              <option value="5" <?= !empty($validation) ? set_select('tax', '5') : (!empty($productDetails) && $productDetails['tax'] == '5' ? 'selected' : '') ?>>5%</option>
              <option value="10" <?= !empty($validation) ? set_select('tax', '10') : (!empty($productDetails)  && $productDetails['tax'] == '10' ? 'selected' : '') ?>>10%</option>
            </select>
            <?= !empty($validation) ? $validation->getError('tax') : '' ?>
          </td>
          <td><button type="submit" class="btn btn-success"><?= !empty($productDetails) ? 'Update' : 'Add' ?></button></td>
        </tr>
      </tbody>
    </table>
    <?= form_close() ?>
    </form>
  </section>

  <div class="further">
    <section>
      <div class="text-end">
        <button type="button" id="generateInvoice" class="btn btn-primary">Generate Invoice</button>
      </div>

      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Product</th>
            <th class="text-center">Quantity</th>
            <th class="text-end">Price</th>
            <th class="text-center">Tax %</th>
            <th class="text-end">Total</th>
            <th class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($products)) :
            foreach ($products as $product) : ?>
              <tr>
                <td><?= $product['name'] ?></td>
                <td class="text-center"><?= number_format($product['quantity'], 2) ?></td>
                <td class="text-end"><?= number_format($product['price'], 2) ?></td>
                <td class="text-center"><?= $product['tax'] ?></td>
                <td class="text-end"><?= number_format($product['total_amount'], 2) ?></td>
                <td class="text-end">
                  <a href="<?= base_url('/?id=' . $product['id']) ?>" class="btn btn-info">Edit</a>
                  <a href="<?= base_url('delete-product/' . $product['id']) ?>" onclick="return confirm('Are you sure you want to delete this product?')" class="btn btn-danger">Delete</a>
                </td>
              </tr>
            <?php
            endforeach;
          else : ?>
            <tr>
              <td colspan="6" class="text-center">No Records Found</th>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
      <?php
      if ($pager->getPageCount() > 1) :
        echo $pager->links();
      endif;
      ?>
      <?php if (!empty($products)) : ?>
        <div class="col-md-6 offset-md-6">
          <table class="table">
            <tbody>
              <tr>
                <td>Subtotal without tax</td>
                <td colspan="2" class="text-end"><?= number_format($sumData['total_price'], 2) ?></td>
              </tr>
              <tr>
                <td>Subtotal with tax</td>
                <td colspan="2" class="text-end"><?= number_format($sumData['total_amount'], 2) ?></td>
              </tr>
              <tr>
                <td>Discount</td>
                <td>
                  <select name="discountType" id="discountType" class="form-select">
                    <option value="fixed" <?= !empty($discountDetails) && $discountDetails['discount_type'] == 'fixed' ? 'selected' : '' ?>>Fixed</option>
                    <option value="percentage" <?= !empty($discountDetails) && $discountDetails['discount_type'] == 'percentage' ? 'selected' : '' ?>>Percentage</option>
                  </select>
                </td>
                <td><input type="text" name="discountAmount" id="discountAmount" value="<?= !empty($discountDetails) ? number_format($discountDetails['discount_amount'], 2) : '0' ?>" class="form-control text-end"></td>
              </tr>
              <tr>
                <td>Total Amount</td>
                <td colspan="2" class="text-end" id="totalAmount"><?= !empty($discountDetails) ? number_format($discountDetails['discounted_total'], 2) : '0' ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      <?php endif; ?>

    </section>

  </div>

  <!-- FOOTER: DEBUG INFO + COPYRIGHTS -->

  <footer>
    <div class="copyrights">

      <p>Invoice Generator</p>

    </div>

  </footer>

  <script>
    var base_url = '<?= base_url() ?>';
    var totalWithTax = '<?= number_format($sumData['total_amount'], 2) ?>';

    $(document).ready(function() {
      calculateTotalAmount();

      $('#discountType').on('change', function() {
        if ($(this).val() == 'percentage' && $('#discountAmount').val() > 100) {
          $('#discountAmount').val(0);
        }

        calculateTotalAmount();
      });

      $('#discountAmount').on('blur', function() {
        if ($('#discountType').val() == 'percentage' && $(this).val() > 100) {
          $(this).val(0);
        } else if (!($(this).val() > 0)) {
          $(this).val(0);
        }

        calculateTotalAmount();
      });

      $('#generateInvoice').on('click', function() {
        $.ajax({
          url: base_url + '/submit-discount',
          type: 'post',
          dataType: 'json',
          data: {
            discountType: $('#discountType').val(),
            discountAmount: $('#discountAmount').val()
          },
          beforeSend: function() {
            $('#generateInvoice').html('Loading...');
            $('#generateInvoice').prop('disabled', true);
          },
          success: function(response) {
            if (response.code == 200) {
              window.location.assign(base_url + '/generate-invoice');
            } else {
              alert('Invoice generation failed');
              window.location.reload();
            }
          }
        })
      });
    });

    function calculateTotalAmount() {
      var discountType = $('#discountType').val();
      var discountAmount = parseFloat($('#discountAmount').val());

      var totalAmount = totalWithTax;
      if (discountType == 'percentage') {
        totalAmount -= (totalWithTax * discountAmount / 100)
      } else {
        totalAmount -= discountAmount;
      }

      $('#totalAmount').html(totalAmount.toFixed(2));
    }
  </script>


</body>

</html>