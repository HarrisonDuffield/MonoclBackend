<?php
require("C:\Users\hpd12\Desktop\MonoclGitHubRepo\MonoclBackend\MySQLConnectionFile.php");
require("PublicFunctions.php");
function LeaderBoardHandler(){
    while(isset($_GET['ClassLeaderBoard'])){
        LeaderBoardDisplayClass();
    }
    LeaderBoardDisplayTotal();
}

function LeaderBoardDisplayTotal(){
    $ConnectionFunction =ConnectionReturn();
    $QuestionListQuery = "Select UserPoints from userdetails ";
}
function  LeaderBoardDisplayClass(){
    $ConnectionFunction =ConnectionReturn();
    $test=$_SESSION["UserLoggedIn"];
    $UserClassRetirevalQuery = "Select ClassID from userdetails WHERE UserID = $test";
    $UserClassRetirevalExecution = mysqli_query($ConnectionFunction,$UserClassRetirevalQuery) or die(mysqli_error($ConnectionFunction));
    $UserClassID = "404";
    if($UserClassRetirevalExecution){// to assume a default
    foreach($UserClassRetirevalExecution as $row){
        $UserClassID = $row["ClassID"];
    }
    $UserPointsQuery = "Select * from userdetails WHERE ClassID = $UserClassID";
    $UserPointsRetrieval = mysqli_query($ConnectionFunction,$UserPointsQuery) or die(mysqli_error($ConnectionFunction));
    echo "<table>";
    echo "<tr>";
    echo "<th>";
    echo "User";
    echo "</th>";
    echo "<th>";
    echo "Points";
    echo "<th>";
    echo "<tr>";
    foreach($UserPointsRetrieval as $UserExport){
        echo "<tr>";
        $item = $UserExport['UserName'];
        $UserPoints =$UserExport['UserPoints'];
        echo "<td> $item</td>";
        echo "<td> $UserPoints</td>";
        echo"</tr>";
    }
    echo "<table>";
}
else{
    echo"What went wrong here then?";
}}

?>