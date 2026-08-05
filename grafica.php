<?php
require_once "config/ConnectionFerro.php";

?>

<html>
  <head>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Topping');
        data.addColumn('number', 'Slices');
        data.addRows([
            <?php
            
            $sql = "SELECT e.*,
                ma.tipo AS material, mi.nombre AS mina, em.nombre AS empresa
                FROM excavacion_cd_piar e
                INNER JOIN material_excavado ma ON e.id_tipo_material = ma.id
                INNER JOIN minas mi ON e.id_mina = mi.id
                INNER JOIN empresa em ON e.id_empresa = em.id
                ORDER BY e.fecha DESC";

            $stmt = ConnectionFerro::getConnection()->prepare($sql);

            try{
                $stmt->execute();
            } catch(PDOException $e) {
                echo $e;
            }

            $lista = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // foreach($lista as $excavacion){
            //     echo "['". $excavacion['mina'] ."', " . $excavacion['cantidad']. "],";
            // }

            $losBarrancos = "";
            $losBarrancosCantidad = 0;
            $sanJoaquin = "";
            $sanJoaquinCantidad = 0;

            foreach($lista as $excavacion){
                if($excavacion['mina'] == "Los Barrancos"){
                    $losBarrancos = "Los Barrancos";
                    $losBarrancosCantidad += intval($excavacion['cantidad']);
                }

                else if($excavacion['mina'] == "San Joaquin"){
                    $sanJoaquin = "San Joaquin";
                    $sanJoaquinCantidad += intval($excavacion['cantidad']);
                }
            }

            echo "['". $losBarrancos ."', " . $losBarrancosCantidad. "],";
            echo "['". $sanJoaquin ."', " . $sanJoaquinCantidad. "],";
            ?>
        ]);

        // Set chart options
        var options = {'title':'Minas donde se realiza la excavación',
                       'width':700,
                       'height':600};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
  </body>
</html>