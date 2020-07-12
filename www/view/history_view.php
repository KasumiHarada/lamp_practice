<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入履歴一覧</title>
  <link rel="stylesheet" href="">
</head>
<body>
<?php include VIEW_PATH . 'templates/header_logined.php'; ?>
<h1>購入履歴</h1>
<div class="container">
  <table class="table table-bordered">
    <thead class="thead-light">
      <tr>
        <th>注文番号</th>
        <th>購入日時</th>
        <th>購入時の価格</th>
        <th>詳細</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($results as $result){ ?>
      <tr>
        <td><?php print h($result['history_id']); ?></td>
        <td><?php print h($result['purchase_datetime']); ?></td>
        <td><?php print h(number_format($result['total'])); ?>円</td>
        <td>
          <form method ="GET" action ="purchase_detail.php">
            <input type ="submit" value="詳細">
            <input type ="hidden" name="history_id" value="<?php print $result['history_id']; ?>">
          </form>
        </td>
      </tr>
      <?php } ?>
    </tbody>  
  </table>
</div>

</body>
</html>