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
           $QuestionListQuery = "Select QuestionID from questiontable where Topic = $TopicClickedOn";
           
           global $ConnectionFunction;
           $QuestionListExecution = mysqli_query($ConnectionFunction,$QuestionListQuery);
           echo "This one worsks question";
           if($QuestionListExecution){
               foreach($QuestionListExecution as $row){
                   global $QuestionDisplayArray;
                   if(in_array($row['QuestionID'],$QuestionDisplayArray)){
                       echo "item already in the array";
                       
                   }
                   else{
                       array_push($QuestionDisplayArray,$row['QuestionID']);
                   }
               }
           }
           else{
               echo mysqli_error($ConnectionFunction);
       }
       }
       function TopicListRetreival(){
       $TopicListQuery = "SELECT Topic FROM QuestionTable";
       
       global $ConnectionFunction;
       $TopicListExecution= mysqli_query($ConnectionFunction, $TopicListQuery);
       echo "Topic query works";
       if($TopicListExecution){
           foreach($TopicListExecution as $row){
               global $TopicDisplayArray;
                if(in_array($row['Topic'],$TopicDisplayArray)){
                   echo "item already in the array";
                }
                else{
                  
                    array_push($TopicDisplayArray,$row['Topic']);
                }
            }
       
        }
        else{
            echo  mysqli_error($ConnectionFunction);
        }
       }
       function AnswerListRetreival($UserIDToUse,$QuestionID){
           global $ConnectionFunction;
           $AnswerListQuery = "SELECT * FROM answertable WHERE QuestionID = '$QuestionID' AND UserID = '$UserIDToUse'";
           $AnswerListExecution =mysqli_query($ConnectionFunction,$AnswerListQuery);
           echo "Asnwer lsit qorks";
           if($AnswerListExecution){
               foreach($AnswerListExecution as $row){
                   global $AnswerListArray;
                   if(in_array($row['AnswerID'],$AnswerListArray)){
                       echo "item already in the array";
                       
                   }
                   else{
                       array_push($AnswerListArray,$row['AnswerID']);
                   }
               }
           }
           else{
               echo  mysqli_error($ConnectionFunction);
       }
           
           
       }
       function AnsweredPercentageRetrieval($UserIDToUse){
           global $AnswerPercentageArray;
           TopicListRetreival();
           $NumeratorOfAnsweredQuestionsInTopic = 0;
           $DenominatorOfTotalQuestionsInTopic = 0;
           global $TopicDisplayArray,$QuestionDisplayArray,$AnswerListArray; 
           for($i =0;$i<count($TopicDisplayArray);$i++){
               $NumeratorOfAnsweredQuestionsInTopic = 0;
               $DenominatorOfTotalQuestionsInTopic = 0;
               QuestionDisplayArray($TopicDisplayArray[$i]);
               foreach($QuestionDisplayArray as $Questions){
                   AnswerListRetreival($Questions,$UserIDToUse);
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
       function TopicTableOrganisation(){
           echo "<Table>";
           echo "<tr>";
           echo "<th> Topic </th>";
           AnsweredPercentageRetrieval($_SESSION['UserLoggedIn']);
           echo "<th> Answer Percentage</th>";
           echo "</tr>";
           global $TopicDisplayArray,$AnswerPercentageArray;
           for($e=0;$e<(count($TopicDisplayArray));$e++){
               echo "<tr>";
               echo "   <td> $TopicDisplayArray[$e] </td>";
               echo "   <td> $AnswerPercentageArray[$e]</td>";
               echo "</tr>";               
           }
          echo "</Table>";
       }
       
?>