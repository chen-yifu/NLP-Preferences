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
    <img id = "myimage" src="new-er.png" alt="ER Diagram" width="100%" height="100%">
    <p>
      <form  method="get">
        <label for="qr">Type your SQL query here</label><br>
        <textarea cols="100%" rows="5" name="query"  id="qr"></textarea><br>
        <input type="submit" value="Enter">
      </form>
      <form method="post" action="export.php" align="center">
        <input type="submit" name="export" value="CSV Export (doesn't work yet)" class="btn btn-success" />
      </form>
    </p>
    <div>
      Your query was: "<?=$_GET['query']?>":

      <?php
      $num_cols = 0;
      $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=mydb',
      'root', 'ubcfm123');
      // See the "errors" folder for details...
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $stmt = $pdo->prepare("SELECT * FROM author;");
      $stmt->execute();
      if(isset($_GET['query'])) {
        $stmt = $pdo->prepare($_GET['query']);
        $stmt->execute();
        echo '<table border="1px solid black" width="300%" id="result_table">'."\n";

        $print_col = TRUE;
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "the result contains <b>".count($data)." rows.</b>";
        $i = 0;
        foreach ($data as $row) {
          if ($print_col) {
            echo "<thead><tr>";
            foreach($row as $col_name => $val)
            {
              echo "<th class='resizable_head".strval($i)."'>".$col_name."</th>";
              $i = $i + 1;
            }
            $num_cols = $i;
            echo "</tr></thead>";
            $print_col = FALSE;
          }
          echo "<tr>";
          $i = 0;
          foreach($row as $col_name => $val) {
            echo "<td class='resizable_body".strval($i)."'>".$val."</td>";
            $i = $i + 1;
          }
          echo "</tr>";
          // echo '<pre>'; print_r($row); echo '</pre>';
        }
        echo "</table><br><br><br>";
      } else {
        echo "please enter a query";
      };

      ?>

    </div>
    <!-- <div style="margin-right: 0; padding-bottom:100px;">
      Example Queries (still needs work, parameters coloured in blue):
      <hr></hr>
      <p><b>Sentences that contain at least two different aspects that can be linked to a specific drug, along with the aspects, associated opinions/polarities, forum ID, post ID, timestamp, and user demographics/characteristics if available.</b></p>
      <p>select describes_id, describes_treatment_name, describes_aspect_name, describes_opinion_word_name, opinion_word_polarity, sentence_body, post_timestamp, author_username from describes as d1, opinion_word as o1, sentence, author, post, forum
        where d1.describes_sentence_id = sentence.sentence_id
        and d1.describes_opinion_word_name = o1.opinion_word_name
        and sentence.sentence_post_id = post.post_id
        and author.author_username = post.post_author_username
        and post.post_forum_name = forum.forum_name
        and describes_treatment_name = <font color="blue">“Ocrevus”</font>
        and describes_sentence_id
        in (select * from (SELECT describes_sentence_id
        FROM describes
        GROUP BY describes_sentence_id
        HAVING COUNT(*) > 1) as a);
      </p>
      <hr></hr>
      <p><b>Sentiment for DrugA (<font color="blue">Ocrevus</font>) associated with mentions by  <font color="blue"> female </font> patients <font color="blue"> over age 40 </font>, along with timestamp, forum ID, and user demographics/characteristics if available.  </b></p>
      <p>select  describes_aspect_name, describes_opinion_word_name, sentence_body, post_timestamp, author_flair, author_age, author_gender, forum_name
        from describes, sentence, post, author, forum
        where describes.describes_sentence_id = sentence.sentence_id
        and post.post_id = sentence.sentence_post_id
        and author.author_username = post.post_author_username
        and forum_name = post.post_forum_name
        and author.author_gender = <font color="blue">"F"</font>
        and author.author_age <font color="blue"> > 40</font>
        and sentence.sentence_body like <font color="blue">"%Ocrevus%"</font>
      </p>
      <hr></hr>
      <p><b>Volume of posts with at least one mention of DrugB (<font color="blue">Ocrevus</font>) over time in ForumX (<font color="blue">r/MultipleSclerosis</font>), independently of whether the drug or specific reference was extracted as an aspect by ABSApp </b></p>
      <p>select extract(year from post.post_timestamp) as year, extract(month from post.post_timestamp) as month, extract(day from post.post_timestamp) as day, count(*) as mention_count
        from sentence, post, forum
        where sentence.sentence_post_id = post.post_id
        and forum.forum_name = <font color="blue">"r/MultipleSclerosis"</font>
        and sentence_body like <font color="blue">"%Ocrevus%"</font>
        group by  extract(day from post.post_timestamp), extract(month from post.post_timestamp), extract(year from post.post_timestamp)
        order by month, day
      </p>
      <hr></hr>
      <p><b>RA forum users who mentioned at least one drug that was extracted as an aspect by ABSApp in at least one of their posts, along with a list of all the drugs each user mentioned and associated sentiments (if available).</b></p>
      <p>
        select SUM(opinion_word_polarity), author_username, treatment_name from opinion_word, describes, treatment, sentence, post, author
        where opinion_word_name = describes_opinion_word_name and sentence_id = describes_sentence_id and post_id = sentence_post_id and author_username = post_author_username and treatment_name = describes_treatment_name and treatment_name != "None"
        group by author_username, treatment_name
        order by author_username
      </p>
    </div>
  </div> -->
  <script>
  var num_cols = <?php echo json_encode($num_cols, JSON_HEX_TAG); ?>;
  // console.log($(".resizable_head0").width());
  var total_width_b = 0
  var total_width_h = 0
  var total_width_max = 0
  var total_width = 0
  for (i = 0; i < num_cols; i++) {
    if ($(".resizable_body"+i).width() > 400 || $(".resizable_head"+i).width() > 400) {
      $(".resizable_head"+i).width(400);
      $(".resizable_body"+i).width(400);
    } else if ($(".resizable_body"+i).width() > $(".resizable_head"+i).width()) {
      // console.log($(".resizable_body"+i).width());
      $(".resizable_head"+i).width($(".resizable_body"+i).width());
      total_width_max += $(".resizable_body"+i).width();
    } else {
      $(".resizable_body"+i).width($(".resizable_head"+i).width());
      total_width_max += $(".resizable_head"+i).width();
    }
    total_width_b += $(".resizable_body"+i).width();
    total_width_h += $(".resizable_head"+i).width();
  }
  // $("td").attr('width', "30");
  console.log($("#result_table"));
  $("tbody").width($("thead").width());
  console.log($("tbody").width());
  console.log($("thead").width());
  total_width = total_width_b + num_cols * 20 + (num_cols + 1) * 2
  console.log(total_width);
  console.log("total width h "+total_width_h);
  console.log(total_width_max);
  $("#result_table").width(total_width);
  $("thead").width(total_width);
  $("tbody").width(total_width);




</script>
</body>
</html>
