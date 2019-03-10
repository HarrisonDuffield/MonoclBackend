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
    $UserPointsQuery = "Select * from userdetails";
    $UserNameArray = array();
    $UserPointsArray = array();
    $UserPointsRetrieval = mysqli_query($ConnectionFunction,$UserPointsQuery) or die(mysqli_error($ConnectionFunction));
    if($UserPointsRetrieval){
    echo "<tr>";
    echo "<th>";
    echo "User";
    echo "</th>";
    echo "<th>";
    echo "Points";
    echo "<th>";
    echo "</tr>";
    foreach($UserPointsRetrieval as $UserExport){
        array_push($UserNameArray,$UserExport['UserName']);
        array_push($UserPointsArray,$UserExport['UserPoints']);
    }   
    $ArrayOfArrays = DualArrayInsertionSort($UserNameArray,$UserPointsArray);
    $UserNameArray = $ArrayOfArrays[0];
    $UserPointsArray = $ArrayOfArrays[1];
    for($i=0;$i<count($UserNameArray);$i++){
        echo "<tr>";
        $item = $UserNameArray[$i];
        $UserPoints =$UserPointsArray[$i];
        echo "<td> $item</td>";
        echo "<td> $UserPoints</td>";
        echo"</tr>";
    }
    }   
    else{
    echo"What went wrong here then?";
    }
}
function  LeaderBoardDisplayClass($test){
    $UserNameArray = array();
    $UserPointsArray = array();
    $ConnectionFunction =ConnectionReturn();
    //$test=$_SESSION["UserLoggedIn"];
    $UserClassRetirevalQuery = "Select ClassID from userdetails WHERE UserID = $test";
    $UserClassRetirevalExecution = mysqli_query($ConnectionFunction,$UserClassRetirevalQuery) or die(mysqli_error($ConnectionFunction));
    $UserClassID = "404";
    if($UserClassRetirevalExecution){
    echo "<tr>";
    echo "<th>";
    echo "User";
    echo "</th>";
    echo "<th>";
    echo "Points";
    echo "<th>";
    echo "</tr>";// to assume a default
    foreach($UserClassRetirevalExecution as $row){
        $UserClassID = $row["ClassID"];
    }
    $UserPointsQuery = "Select * from userdetails WHERE ClassID = $UserClassID";
    $UserPointsRetrieval = mysqli_query($ConnectionFunction,$UserPointsQuery) or die(mysqli_error($ConnectionFunction));
    foreach($UserPointsRetrieval as $UserExport){
        array_push($UserNameArray,$UserExport['UserName']);
        array_push($UserPointsArray,$UserExport['UserPoints']);
    }   
    $ArrayOfArrays = DualArrayInsertionSort($UserNameArray,$UserPointsArray);
    $UserNameArray = $ArrayOfArrays[0];
    $UserPointsArray = $ArrayOfArrays[1];
    for($i=0;$i<count($UserNameArray);$i++){
        echo "<tr>";
        $item = $UserNameArray[$i];
        $UserPoints =$UserPointsArray[$i];
        echo "<td> $item</td>";
        echo "<td> $UserPoints</td>";
        echo"</tr>";
    }
    //echo "</table>";
}
else{
    echo"What went wrong here then?";
}}

?>