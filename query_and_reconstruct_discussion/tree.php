<!DOCTYPE html>
<html>
<head>
  <title>Welcome To NLP-Preferences Database</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js">
  </script>
  <!-- <script src="resize.js"></script> -->


  <link rel="stylesheet" type="text/css" href="query.css" />
  <?php
  require_once "bootstrap.php";
  session_start();
  if ( ! isset($_SESSION["user_id"]) ) {
    die('Not logged in');
  }
  ?>
</head>
<body>
  <div class="container">
    <header>
      <h4> Welcome to NLP Preferences Database</h4>
      <p style="font-size:20px">
        <a href="query.php">Query Portal</a> |
        <a href="tree.php">Discussion Tree</a> |
        <a href="logout.php">Logout</a>
      </p>
    </header>

    <?php
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mydb',
    'root', 'ubcfm123');
    // See the "errors" folder for details...
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->prepare("SELECT * FROM post ORDER BY post_timestamp");
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $arr = array();
    foreach ($data as $row) {
      $arr[] = $row;
    }

    ?>

  </div>

  <div id="postArea">
  </div>
</div>
<script>
  var phpArr = <?php echo json_encode($arr); ?>;
  var posts = new Array();

  phpArr.forEach(function(arrayItem) {
    // if it's post, the post_reply_to_post_id is null
    if (arrayItem['post_reply_to_post_id'] == null) {
      $('#postArea').append("<div class='post' id='"+ arrayItem['post_id'] +"' style='padding: 50px; margin:30px; border: 1px solid #E0E0E0; overflow: auto; max-height: 1000px; font-family: Arial, Helvetica, sans-serif;'>"
      + "<h3>" + arrayItem['post_thread_title'] + "</h3><br>"
      + "<p style='color:#7c7c7d;'>" + arrayItem['post_author_username'] + "  |  score: " + arrayItem['post_score'] + "  |  " + arrayItem['post_timestamp'] + "  |  post_id: " + arrayItem['post_id'] + "  |  post_reply_to_post_id: " + arrayItem['post_reply_to_post_id']  + "</p>"
      + "<p id='thread_post'>" + arrayItem['post_body'] + "</p>"
      + "</div>");
      // posts.push(arrayItem);
    } else {
      $('#' + arrayItem['post_reply_to_post_id']).append("<div class='post' id='" + arrayItem['post_id'] +"' style='padding: 10px; margin-left:30px; font-family: Arial, Helvetica, sans-serif; border-left: 1px dotted #7c7c7d;'>"
      + "<p style='color:#7c7c7d;'>" + arrayItem['post_author_username'] + "  |  score: " + arrayItem['post_score'] + "  |  " + arrayItem['post_timestamp'] + "  |  post_id: " + arrayItem['post_id'] + "  |  post_reply_to_post_id: " + arrayItem['post_reply_to_post_id']  + "</p>"
      + arrayItem['post_body']
      + "</div>")
    }
  })



</script>
</body>
</html>
