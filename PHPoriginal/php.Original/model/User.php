<?php
 require_once("DB.php");

class User extends DB {
  ////////////////////////////////////////////////////////
    //新規登録画面
  ////////////////////////////////////////////////////////
  //登録　INSERT(新規ユーザー登録)
  public function add($arr) {
    $sql = "INSERT INTO users (name,kana,mail,password)
    VALUES (:name,:kana,:mail,:password)";
    $stmt = $this->connect->prepare($sql);
    //パスワードをハッシュ化
    $hash_pass = password_hash($arr['password'], PASSWORD_DEFAULT);
    $params = array (
      ':name'=>$arr['name'],
      ':kana'=>$arr['kana'],
      ':mail'=>$arr['mail'],
      ':password'=>$hash_pass
    );
    $stmt->execute($params);
  }
  //入力チェック　validate(新規登録)
  public function validate($arr) {
    $message = array();
    //ユーザー名
    if(empty($arr['name'])) {
      $message['name'] = '氏名を入力してください。';
    } else if (mb_strlen($arr['name']) > 10) {
      $message['name'] = "氏名は10文字以内で入力してください<br>";
    }
    //フリガナ
    if(empty($arr['kana'])) {
      $message['kana'] = 'フリガナを入力してください。';
    } else if (mb_strlen($arr['kana']) > 10) {
      $message['kana'] = "フリガナは10文字以内で入力してください<br>";
    }
    //メールアドレス
    if(empty($arr['mail'])) {
      $message['mail'] = 'メールアドレスを入力してください。';
    } else if ( !filter_var($arr['mail'],FILTER_VALIDATE_EMAIL) ){
      $message['mail'] = "メールアドレスが不正です。<br>";
    }
    //お問い合わせ内容
    if(empty($arr['password'])) {
      $message['password'] = 'パスワードを入力してください。';
    } else if (!preg_match('/^(?=.*?[a-z])(?=.*?\d)[a-z\d]{8,100}$/i',$arr['password'])) {
      $message['password'] = "パスワードが不正です。<br>";
    }
    return $message;
  }
  ////////////////////////////////////////////////////////////
  //ログイン認証
  ///////////////////////////////////////////////////////////
  public function login($arr) {
    $sql = 'SELECT * FROM users WHERE mail = :mail ';
    $stmt = $this->connect->prepare($sql);
    $params = array (
      ':mail'=>$arr['mail']
    );
    $stmt->execute($params);
    $res = $stmt->fetch();
    // $result = $stmt->rowCount();
    return $res;
  }
  ///////////////////////////////////////////////////////////
    //パスワードリセット
  ///////////////////////////////////////////////////////////
  public function reset($arr) {
    $sql = 'SELECT * FROM users WHERE mail = :mail ';
    $stmt = $this->connect->prepare($sql);
    $params = array (
      ':mail'=>$arr['mail']
    );
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }
  public function newpass($arr,$pass) {
    $sql = "UPDATE users SET password = :password WHERE mail = :mail";
    $stmt = $this->connect->prepare($sql);
    //パスワードをハッシュ化
    $hash_pass = password_hash($pass, PASSWORD_DEFAULT);
    $params = array (
      ':mail'=>$arr['mail'],
      ':password'=>$hash_pass
      // ':password'=>$pass
    );
    $stmt->execute($params);
  }

///////////////////////////////////////////////////////////
  //ユーザー管理
///////////////////////////////////////////////////////////
  //参照　SELECT(ユーザー情報)
   public function findAll() {
      $sql = "SELECT * FROM users WHERE delflag=1";
      $stmt = $this->connect->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
   }
   // 削除　DELETE(ユーザー情報)
   public function delete($id = null) {
     if(isset($id)) {
       $sql = "UPDATE users SET delflag=0 WHERE id = :id";
       $stmt = $this->connect->prepare($sql);
       $params = array(':id'=>$id);
       $stmt->execute($params);
     }
   }
 ///////////////////////////////////////////////////////////
   //お気に入り機能
 ///////////////////////////////////////////////////////////
 //参照
 public function favorite($arr) {
    $sql = 'SELECT i.id, i.title, i.age_id, i.category_id, i.image, i.comment FROM ideas i JOIN favorites f ON i.id = f.idea_id
            JOIN users u ON u.id = f.user_id WHERE f.user_id=:id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$arr);
    $stmt->execute($params);
    $favo = $stmt->fetchAll();
    return $favo;
 }
 // //お気に入りの重複チェック

 public function favoriteAdd($user,$idea){
   $sql = 'SELECT * FROM favorites WHERE user_id=? AND idea_id=?';
   $stmt = $this->connect->prepare($sql);
   $param = array($user,$idea);
   $stmt->execute($param);
   $res = $stmt->fetch();
   return $res;
 }

/////////////////////////////////////////////////////////////
  //お悩み相談ページ
/////////////////////////////////////////////////////////////
 //登録　INSERT
public function trouble($arr) {
  $sql = "INSERT INTO troubles (user_id,name,title,category,trouble,create_at)
  VALUES (:user_id,:name,:title,:category,:trouble,:create_at)";
  $stmt = $this->connect->prepare($sql);
  date_default_timezone_set('Asia/Tokyo');
  $now = new DateTime();
  $date_now = $now->format('Y-m-d H:i:s');
  $params = array (
    ':user_id'=>$arr['user_id'],
    ':name'=>$arr['name'],
    ':title'=>$arr['title'],
    ':category'=>$arr['category'],
    ':trouble'=>$arr['tro_comment'],
    ':create_at'=>$date_now
  );
  $stmt->execute($params);
}

 // 削除　DELETE
public function troubledel($id = null) {
  if(isset($id)) {
    $sql = 'DELETE comments,troubles FROM troubles LEFT JOIN comments ON comments.trouble_id = troubles.id WHERE troubles.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$id);
    $stmt->execute($params);
  }
}
   //参照
   public function troubleAll() {
      $sql = 'SELECT * FROM troubles';
      $stmt = $this->connect->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetchAll();
      return $result;
   }
   //入力チェック
     public function validate3($arr) {
       $message = array();
       //タイトル
         if(empty($arr['title'])) {
           $message['title'] = 'タイトルを入力してください。';
         }
       //対象年齢選択
         if(empty($arr['name'])) {
           $message['name'] = 'お名前を入力してください。';
         }
       //カテゴリー選択
         if(empty($arr['category'])) {
           $message['category'] = 'カテゴリーを選択してください。';
         }
       //アイデア内容
         if(empty($arr['tro_comment'])) {
           $message['tro_comment'] = '相談内容を入力してください。';
         }
         return $message;
        }

/////////////////////////////////////////////////////////
  //コメント機能
/////////////////////////////////////////////////////////
//参照(条件付き)　SELECT
public function findById($id) {
  $sql = 'SELECT * FROM troubles WHERE id = :id';
  $stmt = $this->connect->prepare($sql);
  $params = array(':id' => $id);
  $stmt->execute($params);
  $result = $stmt->fetch();
  return $result;
}
//登録　INSERT
public function comment($arr) {
  $sql = "INSERT INTO comments (user_id,trouble_id,name,comment,create_at)
  VALUES (:user_id,:trouble_id,:name,:comment,:create_at)";
  $stmt = $this->connect->prepare($sql);
  date_default_timezone_set('Asia/Tokyo');
  $now = new DateTime();
  $date_now = $now->format('Y-m-d H:i:s');
  $params = array (
    ':user_id'=>$arr['user_id'],
    ':trouble_id'=>$arr['trouble_id'],
    ':name'=>$arr['name'],
    ':comment'=>$arr['comment'],
    ':create_at'=>$date_now
  );
  $stmt->execute($params);
}
//参照
public function commentAll($arr) {
   $sql = 'SELECT c.name, c.comment, c.create_at FROM comments c JOIN troubles t ON c.trouble_id = t.id WHERE t.id=:id';
   $stmt = $this->connect->prepare($sql);
   $params = array(':id'=>$arr);
   $stmt->execute($params);
   $result2 = $stmt->fetchAll();
   return $result2;
}
//コメント数をカウント
public function countcom() {
  $sql = 'SELECT COUNT(comment) AS Comment FROM comments c JOIN troubles t ON c.trouble_id = t.id GROUP BY t.id';
  $stmt = $this->connect->prepare($sql);
  $stmt->execute();
  $result3 = $stmt->fetchAll();
  return $result3;
}
//入力チェック
  public function validate4($arr) {
    $message = array();
    //名前
      if(empty($arr['name'])) {
        $message['name'] = 'お名前を入力してください。';
      }
    //コメント
      if(empty($arr['comment'])) {
        $message['comment'] = 'コメントを入力してください。';
      }
      return $message;
     }

/////////////////////////////////////////////////////////
  //アイデアページ
/////////////////////////////////////////////////////////
  //登録　INSERT
  public function idea($arr, $filename) {
   $sql = "INSERT INTO ideas (user_id,title,age_id,category_id,image,comment)
           VALUES (:user_id,:title,:age_id,:category_id,:image,:comment)";
   $stmt = $this->connect->prepare($sql);

   $params = array (
     ':user_id'=>$arr['user_id'],
     ':title'=>$arr['title'],
     ':age_id'=>$arr['age'],
     ':category_id'=>$arr['category'],
     ':image'=>$filename,
     ':comment'=>$arr['idea_comment']
   );
   $stmt->execute($params);
  }

//入力チェック
  public function validate2($arr) {
    $message = array();

    //ファイルの取得
    $file = $_FILES['image'];
    $filename = basename($file['name']);
    $tmp_path = $file['tmp_name'];
    $file_err = $file['error'];
    $filesize = $file['size'];
    $upload_dir = 'php.Original/css/img/';
    $save_path = $upload_dir.$filename;

  //ファイル
  //ファイルサイズが１MBか
    if($filesize > 1048756 || $file_err == 2) {
      $message['image'] = "ファイルサイズは1MB未満にしてください。<br>";
    }
  //ファイルがあるか
    if(is_uploaded_file($tmp_path)) {
    } else {
      $message['image'] =  "ファイルが選択されていません。<br>";
    }
//タイトル
  if(empty($arr['title'])) {
    $message['title'] = 'タイトルを入力してください。';
  }
//対象年齢選択
  if(!isset($arr['age'])) {
    $message['age'] = '対象年齢を選択してください。';
  }
//カテゴリー選択
  if(empty($arr['category'])) {
    $message['category'] = 'カテゴリーを選択してください。';
  }
//アイデア内容
  if(empty($arr['idea_comment'])) {
    $message['idea_comment'] = 'アイデアの内容を入力してください。';
  }
  return $message;
 }
 ////////////////////////////////////////////////////////
   //Myアイデアページ
 /////////////////////////////////////////////////////////
 //参照
 public function myidea($arr) {
    $sql = 'SELECT i.id, i.title, i.age_id, i.category_id, i.image, i.comment FROM ideas i JOIN users u ON i.user_id = u.id WHERE u.id=:id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id'=>$arr);
    $stmt->execute($params);
    $idea = $stmt->fetchAll();
    return $idea;
 }

//削除
public function myideadel($id = null) {
 if(isset($id)) {
   $sql = "DELETE FROM ideas WHERE id = :id";
   $stmt = $this->connect->prepare($sql);
   $params = array(':id'=>$id);
   $stmt->execute($params);
 }
}

//編集
public function update($arr, $filename) {
  $sql = "UPDATE ideas SET title = :title, age_id = :age_id, category_id = :category_id,
          image = :image, comment = :comment WHERE id = :id";
  $stmt = $this->connect->prepare($sql);
  $params = array (
    ':id'=>$arr['id'],
    ':title'=>$arr['title'],
    ':age_id'=>$arr['age'],
    ':category_id'=>$arr['category'],
    ':image'=>$filename,
    ':comment'=>$arr['idea_comment']
  );
  $stmt->execute($params);
}
//参照(条件付き)　SELECT
public function findidea($id) {
  $sql = 'SELECT * FROM ideas WHERE id = :id';
  $stmt = $this->connect->prepare($sql);
  $params = array(':id' => $id);
  $stmt->execute($params);
  $result = $stmt->fetch();
  return $result;
}


////////////////////////////////////////////////////////
  //各カテゴリー
/////////////////////////////////////////////////////////
 //0歳児おもちゃ
 //参照
 public function zero_o() {
    $sql = 'SELECT * FROM ideas WHERE category_id="1" AND age_id="0" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }

 //0歳児製作
 //参照
 public function zero_s() {
    $sql = 'SELECT * FROM ideas WHERE category_id="2" AND age_id="0" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //0歳児絵本
 //参照
 public function zero_e() {
    $sql = 'SELECT * FROM ideas WHERE category_id="3" AND age_id="0" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //１歳児おもちゃ
 //参照
 public function one_o() {
    $sql = 'SELECT * FROM ideas WHERE category_id="1" AND age_id="1" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //１歳児製作
 //参照
 public function one_s() {
    $sql = 'SELECT * FROM ideas WHERE category_id="2" AND age_id="1" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //１歳児絵本
 //参照
 public function one_e() {
    $sql = 'SELECT * FROM ideas WHERE category_id="3" AND age_id="1" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //２歳児おもちゃ
 //参照
 public function two_o() {
    $sql = 'SELECT * FROM ideas WHERE category_id="1" AND age_id="2" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //２歳児製作
 //参照
 public function two_s() {
    $sql = 'SELECT * FROM ideas WHERE category_id="2" AND age_id="2" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }
 //２歳児絵本
 //参照
 public function two_e() {
    $sql = 'SELECT * FROM ideas WHERE category_id="3" AND age_id="2" ';
    $stmt = $this->connect->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    return $result;
 }

}
