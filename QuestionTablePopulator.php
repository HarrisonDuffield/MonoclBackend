 <?php
       $TopicDisplayArray = array();
       $QuestionDisplayArray = array();
       $AnswerPercentageArray = array();
       $AnswerListArray = array();
       $Language = array();
       $PercentageForCircle = 0;
       session_start();
       $servername = "localhost:3306";
       $account = "PHPConnection2";
       $dbname = "monoclmain";
       $password="PHPPassword12";
       $ConnectionFunction = mysqli_connect($servername, $account, $password, $dbname);
       function QuestionDisplayFunction($TopicClickedOn,$Language){
           
           $QuestionListQuery = "Select QuestionID from questiontable where Topic = '$TopicClickedOn' AND Language = '$Language' ";
           global $ConnectionFunction;
           $QuestionListExecution = mysqli_query($ConnectionFunction,$QuestionListQuery);
           if($QuestionListExecution){
               foreach($QuestionListExecution as $row){
                   global $QuestionDisplayArray;
                   if(in_array($row['QuestionID'],$QuestionDisplayArray)){
                                             
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
       if($TopicListExecution){
           
           foreach($TopicListExecution as $row){
               global $TopicDisplayArray;
                if(in_array($row['Topic'],$TopicDisplayArray)){
                   
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
           
           if($AnswerListExecution){
               foreach($AnswerListExecution as $row){
                   global $AnswerListArray;
                   if(in_array($row['AnswerID'],$AnswerListArray)){
                       
                       
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
           for($r=0;$r<count($AnswerListArray);$r++){
               echo $AnswerListArray[$r];
           }
           
           for($i =0;$i<count($TopicDisplayArray);$i++){
               $NumeratorOfAnsweredQuestionsInTopic = 0;
               $DenominatorOfTotalQuestionsInTopic = 0;
               global $Language;
               QuestionDisplayFunction($TopicDisplayArray[$i],$Language[0]);
               for($x = 0;$x<count($QuestionDisplayArray);$x++){
                   AnswerListRetreival($UserIDToUse,$QuestionDisplayArray[$x]);
                   if(count($AnswerListArray)>=1){
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
            
            if($NumeratorOfAnsweredQuestionsInTopic>0){            
            $PercentageToAdd = ($NumeratorOfAnsweredQuestionsInTopic/$DenominatorOfTotalQuestionsInTopic)*100;
            
            }
            else{
                $PercentageToAdd = 0;
            }
            
            array_push($AnswerPercentageArray,$PercentageToAdd);
           //order of operation topics gathered , questions in each topic gathered,
           // for each question see if the user has answered, answered/total questions = percentage for each topic
       }
       
       }
       function BigCiclePercentageCalc(){
           global $Language;
           $Language[0] = $_SESSION["Language"];
           AnsweredPercentageRetrieval($_SESSION["UserLoggedIn"]);
           global $AnswerPercentageArray;
           $AmountOf100s =100*(count($AnswerPercentageArray));
           $TotalPercentages = 0;
           for ($i=0;$i<(count($AnswerPercentageArray));$i++){
               $TotalPercentages=$TotalPercentages + $AnswerPercentageArray[$i];
           }
           $TotalToReturn = 100*($TotalPercentages / $AmountOf100s);
           $TotalToReturn =round($TotalToReturn);
           echo "<b>$TotalToReturn %</b>";
           
       }
       function TopicTableOrganisation(){
           global $Language;
           $Language[0] = $_SESSION["Language"];
           echo "<tr>";
           echo "<th> Topic </th>";
           AnsweredPercentageRetrieval($_SESSION["UserLoggedIn"]);
           echo "<th> Answer Percentage</th>";
           echo "</tr>";
           global $TopicDisplayArray,$AnswerPercentageArray;
           for($e=0;$e<(count($TopicDisplayArray));$e++){
               echo "<tr>";
               echo "   <td> $TopicDisplayArray[$e] </td>";
               echo "   <td> $AnswerPercentageArray[$e]%</td>";
               echo "</tr>";               
           }
          
       }
       
?>