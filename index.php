<?php

   $data = array();
   
   if(isset($_POST['lat']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
   
   $lat=$_POST['lat'];
   $lon=$_POST['lon'];

   $result = array('status'=>'Error','msg'=>'Invalid parameters');

 $appid='49a090f8fd32a555bd97635debc34855';
 $url ='https://api.openweathermap.org/data/2.5/weather?';
// your code to sanitize and assign (Ajax) post variables to your PHP variables
// if invalid:   exit(json_encode($result));

// make API request with $api_key
$request = $url . 'lat=' . $lat.'&lon=' . $lon . '&appid=' . $appid;
 $ch = curl_init($request);  
curl_setopt($ch,CURLOPT_FAILONERROR, TRUE);  // identify as error if http status code >= 400
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);  // returns as string
$api_response = curl_exec($ch);
if(curl_errno($ch) || curl_getinfo($ch, CURLINFO_HTTP_CODE) != 200 ) :
    $result['msg'] = 'Item not found or unable to get data. ' . curl_error($ch);
    curl_close($ch);
    exit(json_encode($result));
endif;
curl_close($ch);
$decodedData = json_decode($api_response, true);
// check for success and do any server side manipulation of $decodedData


$result = $decodedData; 

   
   
   echo json_encode($result);  
   die();      
    }
 ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    
<style>
html,body {
  
  background-image: url("background1.jpg");
  height: 100%

}
.top-buffer {
  margin-top:25%;
  padding:0;

  clear: both;
}
.bottom-buffer {
  margin-bottom:50px;
}
</style>
</head>
<body>

    <nav class="navbar bg-dark navbar-dark">
 
        <a class="navbar-brand" href="">Your Local Weather</a>

    </nav>

   <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3 top-buffer">
        
     
      <div class="card text-center">
<div class="card-header" id="button-header">
  <button id="celsius" class="btn btn-light float-md-right">&#x2103</button>
  <button id="fahrenheit" class="btn btn-dark float-md-right">&#x2109</button>
          
        </div>

        <div class="card-body" id="weather-card"> 
          
            <h3 id="location" class="card-title"></h3>
            <h1 id="temp"><sup>&#x2109</sup></h1>
            <h1 id="description"></h1>
            <img src="", alt="" id="weather-icon">
            
            
        </div>
      </div>
      </div>
     
 </div>
    </div> 

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>

  
<!-- Latest compiled and minified JavaScript 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>-->

 <script type = "text/javascript">

function getWeather(pos) {

$.ajax({
   method: "POST",
   dataType: "json",
   data: {lat: pos.coords.latitude,lon:pos.coords.longitude},
})
.done(function(data) {
weather = data;

var tempf=Math.floor(9/5*(weather.main.temp - 273.15)+32);
        var tempc=Math.floor(weather.main.temp - 273.15);
        $("#location").html(weather["name"]);
        $("#temp").prepend(tempf);
        $("#description").html(weather["weather"][0]["description"]);
        $("#weather-icon").html(weather["weather"][0]["description"]);
        $("#celsius").on("click", function() {
          $("#temp").html(tempc + '<sup>&#x2103</sup>');
          $("#celsius").removeClass("btn-light").addClass("btn-dark");
          $("#fahrenheit").removeClass("btn-dark").addClass("btn-light");
          
          
        });
        $("#fahrenheit").on("click", function() {
          $("#temp").html(tempf + '<sup>&#x2109</sup>');
          $("#celsius").removeClass("btn-dark").addClass("btn-light");
          $("#fahrenheit").removeClass("btn-light").addClass("btn-dark");
          
                    
        });
        var weatherID = weather["weather"][0]["id"];
        switch (true) {
          case weatherID >= 200 && weatherID < 233: //thunderstorm
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/11d@2x.png");
          break;
          case weatherID >= 300 && weatherID <= 321: //drizzle
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/09d@2x.png");
          break
          case weatherID >= 500 && weatherID <= 504: //light rain,moderate rain,heavy intensity rain,very heavy rain,extreme rain
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/10d@2x.png");
          break;
          case weatherID == 511: //freezing rain
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/13d@2x.png");
          break;
          case weatherID >= 520 && weatherID <= 531: //light intensity shower rain,shower rain,heavy intensity shower rain,ragged shower rain
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/09d@2x.png");
          break;
          case weatherID >= 600 && weatherID <=622 : //snow
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/13d@2x.png");
          break;
          case weatherID >= 701 && weatherID <= 781: //Atmosphere
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/50d@2x.png");
          break;
          case weatherID == 800 : //clear sky
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/01d@2x.png");
          break;
          case weatherID == 801: //few clouds
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/02d@2x.png");
          break;
          case weatherID == 802: //scattered clouds
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/03d@2x.png");
          break;
          case weatherID == 803 || weatherID == 804: //broken clouds,overcast clouds
          $("#weather-icon").attr("src", "http://openweathermap.org/img/wn/04d@2x.png");
          break;         

        }
});

}

    $(document).ready(function() {
      navigator.geolocation.getCurrentPosition(getWeather);
      

    });

</script>

</body>
</html>