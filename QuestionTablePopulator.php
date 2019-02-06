 <?php
       $TopicDisplayArray = array();
       $QuestionDisplayArray = array();
       $AnswerPercentageArray = array();
       $AnswerListArray = array();
       session_start();
       $servername = "localhost:3306";
       $account = "PHPConnection2";
       $dbname = "monoclmain";
       $password="PHPPassword12";
       $ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
       function QuestionDisplayArray($TopicClickedOn){
           $QuestionListQuery = "Select QuestionID from QuestionTable Where Topic = '$TopicClickedOn'";
           $QuestionListExecution = mysqli_query($ConnectionFunction,$QuestionListQuery);
           if($QuestionListExecution){
               foreach($QuestionListExecution as $row){
                   if(in_array($row['QuestionID'],$QuestionDisplayArray())){
                       echo "item already in the array";
                       
                   }
                   else{
                       array_push($QuestionDisplayArray,$row['QuestionID']);
                   }
               }
           }
           else{
               echo "Connection Failure";
       }
       }
       function TopicListRetreival(){
       $TopicListQuery = "SELECT Topic FROM QuestionTable";
       $TopicListExecution= mysqli_query($ConnectionFunction, $TopicListQuery);
       if($TopicListExecution){
           foreach($TopicListExecution as $row){
                if(in_array($row['TopicListExecution'],$TopicDisplayArray)){
                   echo "item already in the array";
                }
                else{
                  
                    array_push($TopicDisplayArray,$row['TopicListExecution']);
                }
            }
       
        }
        else{
            echo "Connection Error";
        }
       }
       function AnswerListRetreival($UserIDToUse,$QuestionID){
           $AnswerListQuery = "SELECT * FROM answertable WHERE UserID = '$UserIDToUse', QuestionID = '$QuestionID'";
           $AnswerListExecution =mysqli_query($ConnectionFunction,$AnswerListQuery);
           if($AnswerListExecution){
               foreach($AnswerListExecution as $row){
                   if(in_array($row['AnswerID'],$AnswerListArray())){
                       echo "item already in the array";
                       
                   }
                   else{
                       array_push($AnswerListArray,$row['AnswerID']);
                   }
               }
           }
           else{
               echo "Connection Failure";
       }
           
           
       }
       function AnsweredPercentageRetrieval(){
           TopicListRetrieval();
           $NumeratorOfAnsweredQuestionsInTopic = 0;
           $DenominatorOfTotalQuestionsInTopic = 0;
           $UserIDToUse = $_SESSION["UserLoggedIn"];
           for($i =0;$i<count($TopicDisplayArray);$i++){
               $NumeratorOfAnsweredQuestionsInTopic = 0;
               $DenominatorOfTotalQuestionsInTopic = 0;
               QuestionDisplayArray($TopicDisplayArray[$i]);
               foreach($QuestionDisplayArray as $Questions){
                   AnswerListRetrieval($Questions,$UserIDToUse);
                   if(count($AnswerListArray>=1)){
                       $NumeratorOfAnsweredQuestionsInTopic++;
                       $DenominatorOfTotalQuestionsInTopic++;
                       unset($AnswerListArray);
                       $AnswerListArray = array();
                   }
                   else{
                      $DenominatorOfTotalQuestionsInTopic++;
                      unset($AnswerListArray);
                      $AnswerListArray = array();
                   }
                                
            }
            unset($QuestionDisplayArray);
            $QuestionDisplayArray = array();
           $PercentageToAdd = ($NumeratorOfAnsweredQuestionsInTopic/$DenominatorOfTotalQuestionsInTopic);
           array_push($AnswerPercentageArray,$PercentageToAdd);
           //order of operation topics gathered , questions in each topic gathered,
           // for each question see if the user has answered, answered/total questions = percentage for each topic
       }
       }
       function TopicTableOrginisation(){
           echo "<Table>";
           echo "<tr>";
           echo "<th> Topic </th>";
           AnsweredPercentageRetrieval();
           echo "<th> Answer Percentage</th>";
           echo "</tr>";
           for($e=0;$e<(count($TopicDisplayArray));$e++){
               echo "<tr>";
               echo "<td> '$TopicDisplayArray[$e]' </td>";
               echo "<td> '$AnswerPercentageArray[$e]'</td>";
               echo "</tr>";               
           }
          echo "</Table>";
       }
       
?>