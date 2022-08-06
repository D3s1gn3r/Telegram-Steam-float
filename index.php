<?php
    // redbean php library
    require 'rb.php';
    require 'db.php';
    $floatguns = R::getAll( 'SELECT * FROM floatguns' );
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <br><br>
    <div class="container">
        <div  style="display: inline-block;">
            <form action="">
                <input type="text" style="width: 400px;" id='gunName' placeholder='gunName'>
                <input type="text" id='fromFloat' placeholder='from'>
                <input type="text" id='toFloat' placeholder='to'>
                <br>
                <br>
                <input type="text" id='paintseed' placeholder='paintseed'>
                <select id='phase'>
                  <option value="-">-</option>
                  <option value="phase1">phase 1</option>
                  <option value="phase2">phase 2</option>
                  <option value="phase3">phase 3</option>
                  <option value="phase4">phase 4</option>
                  <option value="blackpearl">blackpearl</option>
                  <option value="ruby">ruby</option>
                  <option value="emerald">emerald</option>
                  <option value="sapphire">sapphire</option>
                </select>
                <input type="text" id='notes' placeholder='Notes'>
            </form>
        </div>
        <div  style="display: inline-block;">
            <input type="submit" value="insert" class="button">
        </div>
    </div>
    <br><br>
    <div class="container">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Name</th>
              <th scope="col">From</th>
              <th scope="col">To</th>
              <th scope="col">Paint Seed</th>
              <th scope="col">Phase</th>
              <th scope="col">Notes</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody>
            <?php
                $i = 1;
                foreach ($floatguns as $key => $value) {
                    $name = str_replace("%20", " ", $value['name']);
                    $name = str_replace("%7C", "|", $name);
                    echo '<tr>' .
                    '<th scope="row">' . $i . '</th>' .
                    '<td>' . $name .
                    '<td>' . $value['fromfloat'] .'</td>' .
                    '<td>' . $value['tofloat'] .'</td>' .
                    '<td>' . $value['paintseed'] .'</td>' .
                    '<td>' . $value['phase'] .'</td>' .
                    '<td><input class="notes" type="text" id="' . $value['id'] . '" value="' . $value['notes'] . '"></td>' .
                    '<td>' . '<input type="submit" value="x" class="del_button" ' . 'id = "' . $value['id'] . '">' . '</td>' .
                    '</tr>';
                    $i++;
                }
            ?>
          </tbody>
        </table>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script>
  $(function() {
    $(".button").click(function() {
      // name
      var name = $("#gunName").val();
      // float from
      var fromfloat = $("#fromFloat").val();
      // float to
      var tofloat = $("#toFloat").val();
      // paintseed
      var paintseed = $("#paintseed").val();
      // phase
      var phase = $("#phase").val();
      // notes
      var notes = $("#notes").val();

      $.ajax({
          type: "POST",
          url: "for_db_guns.php",
          data: {name:name, fromfloat:fromfloat, tofloat:tofloat, paintseed:paintseed, phase:phase, notes:notes},
          success: function(){
              location.reload()
          }
      });
    });
  });

  $(function() {
    $(".notes").change(function(){
      id = this.id;
      value = this.value;
      $.ajax({
        type: "POST",
        url: "changevalues_db.php",
        data: {id:id, value:value},
        success: function(){
          // location.reload()
        }
      });
      // alert(this.id);
      // alert(this.value);
    })
  })
  $(function() {
    $(".del_button").click(function() {
      id = this.getAttribute('id');
      $.ajax({
        type: "POST",
        url: "del_bean_guns.php",
        data: {id:id},
        success: function(){
          location.reload()
        }
          });
      });
  });
</script>
</body>
</html>