<?php
include '../connect/connections.php';
//include '../../classes/User.php';

session_start();
$my_login = $_SESSION['login'];
$login = $_POST['login'];

if($my_login != $login) {
  $query = "SELECT * FROM `users` WHERE `nick` = '$login'";
  $result = $connect->query($query);

  $row = mysqli_fetch_row($result);
  if(count($row) > 0) {

      // By class User??;
      $_SESSION['other-login'] = $login;

      $data = array();
      $data["response"] = "true";
      $data["id"] = $row[0];
      $data["login"] = $row[1];
      // $data["email"] = $row[3];
      $data["status"] = $row[4];

      if($row[5] == null) {
        $data["follows"] = array();
      } else {
        $data["follows"] = unserialize($row[5]);
      }
      $friends = array();
      $ind = 1;
      foreach ($data["follows"] as $person) {
        $str = "
        <div class=\"friend-main\" id=\"other-friend-main-$ind\">
            <div class=\"friend-image\"></div>
            <div class=\"friend-other\">
                <div class=\"friend-name\">
                    $person
                </div>
                <div class=\"friend-description\">
                    Я рожден чтобы создать модальное окно..
                </div>
            </div>
        </div>";
        $ind++;
        $friends[] = array("div" => $str, "name" => $person);
      }
      $data["follows"] = $friends;

      if(unserialize($_SESSION['follows']) == null) {
        $data["inFollow"] = "false";
        $data["why"] = "no array";
        $_SESSION['follows'] = serialize(array());
      }
      else if(in_array($login, unserialize($_SESSION['follows']))) {
        $data["why"] = "in array";
        $data["inFollow"] = "true";
      }
      else {
        $data["inFollow"] = "false";
        $data["why"] = "not in array";
      }
      $data = json_encode($data);
      echo $data;
  }
  else {
      $data = array();
      $data["response"] = "false";
      $data = json_encode($data);
      echo $data;
  }
}

else {
    $data = array();
    $data["response"] = "you";
    $data["why"] = "your account";
    $data = json_encode($data);
    echo $data;
}
?>
