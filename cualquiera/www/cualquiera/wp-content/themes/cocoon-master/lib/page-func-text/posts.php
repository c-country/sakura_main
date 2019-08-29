<?php //内容の保存
/**
 * Cocoon WordPress Theme
 * @author: yhira
 * @link: https://wp-cocoon.com/
 * @license: http://www.gnu.org/licenses/gpl-2.0.html GPL v2 or later
 */
if ( !defined( 'ABSPATH' ) ) exit;

if (!empty($_POST['title']) && !empty($_POST['text']) && !empty($_POST['action'])) {
  global $wpdb;

  if ($_POST['action'] == 'new') {
    $result = insert_function_text_record($_POST);

    //_v($wpdb->insert_id);
    //編集モードに変更
    if ($result) {
      global $wpdb;
      $_GET['action'] = 'edit';
      $_GET['id'] = $wpdb->insert_id;
      generate_notice_message_tag(__( 'テキストを新規作成しました。', THEME_NAME ));
    }
    //_v($result);
  } else {
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';
    if ($id) {
      $result = update_function_text_record($id, $_POST);
      if ($result) {
        generate_notice_message_tag(__( 'テキストを更新しました。', THEME_NAME ));
      }
    }

  }
  //設定保存メッセージ
  if ($result) {
    //generate_notice_message_tag(__( '内容を保存しました。', THEME_NAME ));
  } else {
    generate_notice_message_tag(__( 'データベースに保存されませんでした。', THEME_NAME ));
  }
} else {
  $message = '';
  if (empty($_POST['title'])) {
    $message .= __( 'タイトルが入力されていません。', THEME_NAME ).'<br>';
  }
  if (empty($_POST['text'])) {
    $message .= __( '内容が入力されていません。', THEME_NAME ).'<br>';
  }
  if (empty($_POST['action'])) {
    $message .= __( '入力内容が不正です。', THEME_NAME ).'<br>';
  }
  generate_error_message_tag($message);
}
