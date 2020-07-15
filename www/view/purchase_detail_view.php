<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴詳細</title>
  <link rel="stylesheet" href="">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<h1>購入履歴詳細</h1>

<div class="container">

<p><?php print h($total['history_id']); ?></p>
<p><?php print h($total['purchase_datetime']); ?></p>
<p><?php print h(number_format($total['total'])); ?>円</p>

<table class="table table-bordered">
  <thead class="thead-light">
    <tr>
      <th>商品名</th>
      <th>購入時の商品価格</th>
      <th>購入数</th>
      <th>小計</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($subtotal as $value){ ?>
    <tr>
      <td><?php print h($value['name']); ?></td>
      <td><?php print h(number_format($value['price'])); ?>円</td>
      <td><?php print h($value['amount']); ?></td>
      <td><?php print h(number_format($value['total']));?>円</td>
    </tr>
    <?php } ?>
  </tbody>  
</table>
</div>
</body>
</html>