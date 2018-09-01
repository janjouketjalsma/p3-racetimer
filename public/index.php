<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>P3 RaceTimer</title>
  <link rel="stylesheet" href="css/bootstrap.min.css"/>
  <style>
    html, body{
      font-size:1em;
      overflow-x:hidden;
    }

    .lapRows:nth-child(1){
      font-size:1.5em;
    }

    .lapRows:nth-child(2){
      font-size:1.2em;
    }

    .lapColumns {
      -webkit-column-count: 2; /* Chrome, Safari, Opera */
      -moz-column-count: 2; /* Firefox */
      column-count: 2;
      height: -moz-calc(100vh - 150px);
      height: -webkit-calc(100vh - 150px);
      height: calc(100vh - 150px);
      -moz-column-fill: auto;
         column-fill: auto;
    }
    @media (max-width: 1000px) {
      .lapColumns {
        -webkit-column-count: 1; /* Chrome, Safari, Opera */
        -moz-column-count: 1; /* Firefox */
        column-count: 1;
      }
    }
  </style>
</head>

<body>
  <div class="container-fluid">
      <h1 class="display-1">Lap times</h1>
      <div class="lapColumns">
          <table class="table-striped">
            <thead>
              <th scope="col">Team</th>
              <th scope="col">Participant</th>
              <th scope="col">Time</th>
            </thead>
            <tbody class="finishedLaps-1 lapRows">
            </tbody>
          </table>
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
          $(' \
            <tr class="lapRow" style="opacity:0"> \
              <td>'+ lapData.team +'</td> \
              <td><span class="font-weight-bold">'+ lapData.participant +'</span></td> \
              <td><span class="text-monospace font-weight-bold">'+ getTimeString(lapData.lapTime / 1000) +'</span></td> \
            </tr>'
          ).prependTo($finishedLaps1).fadeTo(500, 1);
      }

      var getTimeString = function(timeInMs, excludeHundreds) {

        var delim = ":";
        var minutes = Math.floor(timeInMs / (1000 * 60));
        var seconds = Math.floor(timeInMs / 1000 % 60);
        var hundreds = timeInMs % 1000;

        minutes = minutes < 10 ? '0' + minutes : minutes;
        seconds = seconds < 10 ? '0' + seconds : seconds;
        var ret = minutes + delim + seconds
        if(!excludeHundreds){
          ret += delim + pad000(hundreds + "");
        }
        return ret;
      }

      var pad000 = function (str) {
        var pad = "000";
        return pad.substring(0, pad.length - str.length) + str;
      }
  </script>
</body>

</html>
