<?php
//<body>タグ直後編集用のテンプレートです。
//子テーマのカスタマイズ部分を最小限に抑えたい場合に有効なテンプレートとなります。
if ( !defined( 'ABSPATH' ) ) exit;
?>
<?php if (!is_user_administrator()) :
//管理者以外カウントしたくない場合は
//↓ここに挿入?>

<?php endif; ?>
<?php //ログインユーザーも含めてカウントする場合は以下に挿入 ?>
