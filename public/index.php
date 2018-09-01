<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>P3 RaceTimer</title>
  <link rel="stylesheet" href="css/bootstrap.min.css"/>
  <style>
    html, body{
      font-size:1em;
    }
  </style>
</head>

<body>
  <div class="container-fluid">
      <h1 class="display-1">Lap times</h1>
      <div class="row">
        <div class="col-lg">
          <ul class="list-group list-group-flush finishedLaps-1">

          </ul>
        </div>
        <div class="col-lg">
          <ul class="list-group list-group-flush finishedLaps-2">

          </ul>
        </div>
      </div>
  </div>
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/bootstrap.bundle.min.js"></script>
  <script src="js/autobahn.js"></script>
  <script>
      var $finishedLaps1 = $(".finishedLaps-1");
      var $finishedLaps2 = $(".finishedLaps-2");
      var conn = new ab.Session('ws://localhost:8080',
          function() {
              conn.subscribe('PASSING', function(topic, data) {
                  // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                  console.log('New passing', data);
              });

              conn.subscribe('FINISHED_LAP', function(topic, data) {
                  // This is where you would add the new article to the DOM (beyond the scope of this tutorial)
                  console.log('New finished lap', data);
                  insertLap(data);

              });
          },
          function() {
              console.warn('WebSocket connection closed');
          },
          {'skipSubprotocolCheck': true}
      );

      function insertLap(lapData) {
          $finishedLaps1.prepend('<li class="list-group-item"><div class="d-flex justify-content-between"><div>'+ lapData.team +'</div><div>'+ lapData.participant +'</div><div>'+ getTimeString(lapData.lapTime / 1000) +'</div></div></li>');
      }

      var getTimeString = function(timeInMs) {
        var delim = ":";
        var minutes = Math.floor(timeInMs / (1000 * 60));
        var seconds = Math.floor(timeInMs / 1000 % 60);
        var hundreds = timeInMs % 1000;

        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        return minutes + delim + seconds + delim + pad000(hundreds + "");
      }

      var pad000 = function (str) {
        var pad = "000";
        return pad.substring(0, pad.length - str.length) + str;
      }
  </script>
</body>

</html>
