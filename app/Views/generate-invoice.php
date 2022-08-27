<html>

<head>
  <title>Invoice</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <style>
    section {
      margin: 0 auto;
      max-width: 1100px;
      padding: 2.5rem 1.75rem 3.5rem 1.75rem;
    }
  </style>
</head>

<body onload="window.print()">
  <section>
    <h1 class="text-center">INVOICE</h1>
    <hr>
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Product</th>
          <th class="text-center">Quantity</th>
          <th class="text-end">Price</th>
          <th class="text-center">Tax %</th>
          <th class="text-end">Total</th>
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
            </tr>
          <?php
          endforeach;
        else : ?>
          <tr>
            <td colspan="6">No Records Found</th>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <?php if (!empty($products)) : ?>
      <div class="col-md-6 offset-md-6">
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td>Subtotal without tax</td>
              <td class="text-end"><?= number_format($sumData['total_price'], 2) ?></td>
            </tr>
            <tr>
              <td>Subtotal with tax</td>
              <td class="text-end"><?= number_format($sumData['total_amount'], 2) ?></td>
            </tr>
            <tr>
              <td>Discount</td>
              <td class="text-end"><?= $discountDetails['type'] == 'percentage' ? number_format($discountDetails['amount']) . '%' : number_format($discountDetails['amount'], 2) ?></td>
            </tr>
            <tr>
              <td>Total Amount</td>
              <td class="text-end"><?= number_format($discountDetails['discounted_total'], 2) ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    <?php endif; ?>

  </section>
</body>

</html>