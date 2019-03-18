 <?php
       $TopicDisplayArray = array();
       $AnsweredOrNotArray = array();
       $QuestionDisplayArray = array();
       $SelectiveQuestionDisplayArray=array();
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
       function ClearQuestionArray(){
           
           global $QuestionDisplayArray;
           //echo count($QuestionDisplayArray);
           foreach($QuestionDisplayArray as $item){
               unset($QuestionDisplayArray[$item]);
           } 
           //echo count($QuestionDisplayArray);
       }
       function ClearAnswerArray(){
           global $AnswerListArray;
           foreach($AnswerListArray as $item){
               unset($AnswerListArray[$item]);
           }
       }
       function ClearTopicArray(){
           global $TopicDisplayArray;
           foreach($TopicDisplayArray as $item){
            unset($TopicDisplayArray[$item]);
        }
       }
       function QuestionTableExport($TopicClickedOn){           
        global $Language,$SelectiveQuestionDisplayArray,$AnsweredOrNotArray;
        $Language[0] = $_SESSION["Language"];
            SelectiveQuestionDisplayFunction($TopicClickedOn,$Language[0]);
            CompleteOrIncompleteReturn($_SESSION["UserLoggedIn"],$SelectiveQuestionDisplayArray);
           echo"<tr>";           
           echo "<th> Question Id</th>";
            echo "<th> Complete</th>";
           echo"</tr>";
            for($i=0;$i<count($SelectiveQuestionDisplayArray);$i++){
            $itemtopush = $SelectiveQuestionDisplayArray[$i];
            $ticktopush = $AnsweredOrNotArray[$i];
            echo "<tr>";
            echo "<td>";
            echo $itemtopush;
            echo"</td>";
            echo"<td>";
            echo $ticktopush;
            echo "</td>";
            echo"</tr>";
           }                          
       }
       function CompleteOrIncompleteReturn($UserID,$ArrayOfQuestionIDs){
           global $ConnectionFunction,$AnsweredOrNotArray;
           for($i=0;$i<count($ArrayOfQuestionIDs);$i++){
               $ItemToSearchFor =$ArrayOfQuestionIDs[$i];
               $AnswerSubmittedQuery="SELECT * From AnswerTable WHERE UserID ='$UserID' AND QuestionID = '$ItemToSearchFor'";
               $CompleteOrNotExecution = mysqli_query($ConnectionFunction,$AnswerSubmittedQuery);
               if($CompleteOrNotExecution){
                   $CountOfResults = mysqli_num_rows($CompleteOrNotExecution);
                   
                   if($CountOfResults > 0){
                       array_push($AnsweredOrNotArray,"✓");
                   }
                   else{
                       array_push($AnsweredOrNotArray,"x");
                   }
                }
               else{
                   array_push($AnsweredOrNotArray,"x");
               }
           }
        }

       
       function SelectiveQuestionDisplayFunction($TopicClickedOn,$Language){
           $TopicClickedOn = ltrim($TopicClickedOn," ");//whitespace seems to mean it reutns an empty set
           $TopicClickedOn = rtrim($TopicClickedOn," ");
               
        $QuestionListQuery = "Select QuestionID from questiontable where Topic ='$TopicClickedOn'AND Language = '$Language' ";
        global $ConnectionFunction;
        $QuestionListExecution = mysqli_query($ConnectionFunction,$QuestionListQuery);
        if($QuestionListExecution){
            $counter=0;
            foreach($QuestionListExecution as $row){
                global $SelectiveQuestionDisplayArray;
                if(in_array($row['QuestionID'],$SelectiveQuestionDisplayArray)){
                                          
                }
                else{
                    array_push($SelectiveQuestionDisplayArray,$row['QuestionID']);
                    $counter=$counter+1;
                }
            }
            return $counter;
        }
        else{
            
            echo mysqli_error($ConnectionFunction);
    }
    }

















       function QuestionDisplayFunction($TopicClickedOn,$Language){
         // echo 88;
        
           $QuestionListQuery = "Select QuestionID from questiontable where Topic = '$TopicClickedOn' AND Language = '$Language' ";
           global $ConnectionFunction;
           $QuestionListExecution = mysqli_query($ConnectionFunction,$QuestionListQuery);
           if($QuestionListExecution){
               foreach($QuestionListExecution as $row){
                   global $QuestionDisplayArray;
                   if(in_array($row['QuestionID'],$QuestionDisplayArray)){
                                             //suspect the issue lies with this funciton
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
           global $Language;
        $LanguageToUse = $Language[0];
       $TopicListQuery = "SELECT Topic FROM QuestionTable WHERE Language = '$LanguageToUse'";
       
       global $ConnectionFunction;
       $TopicListExecution= mysqli_query($ConnectionFunction, $TopicListQuery);
       if($TopicListExecution){
           
           foreach($TopicListExecution as $row){
               ClearTopicArray();
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
           //echo 77;
           $AnswerListQuery = "SELECT * FROM answertable WHERE QuestionID = '$QuestionID' AND UserID = '$UserIDToUse'";
           $AnswerListExecution =mysqli_query($ConnectionFunction,$AnswerListQuery);
           if($QuestionID == 2){
               echo "i give up";
           }
           if($AnswerListExecution){
               foreach($AnswerListExecution as $row){
                   global $AnswerListArray;
                   $itemtopush=$row['AnswerID'];
                   //echo $itemtopush;
                  // echo $itemtopush;
                   if(in_array($itemtopush,$AnswerListArray)){
                       
                       
                   }
                   else{
                       array_push($AnswerListArray,$itemtopush);
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
               global $Language;
               QuestionDisplayFunction($TopicDisplayArray[$i],$Language[0]);
               //echo count($QuestionDisplayArray);
               for($x = 0;$x<count($QuestionDisplayArray)-1;$x++){
                     // echo $x;                  
                   //echo $QuestionDisplayArray[$x];
                   AnswerListRetreival($UserIDToUse,$QuestionDisplayArray[$x]);
                  // echo $AnswerListArray[0];
                   if(count($AnswerListArray)>=1){
                       $NumeratorOfAnsweredQuestionsInTopic++;
                       $DenominatorOfTotalQuestionsInTopic++;
                    //   echo 999;
                       //echo $NumeratorOfAnsweredQuestionsInTopic;
                //echo 808;
                  //   echo $DenominatorOfTotalQuestionsInTopic;
                  ClearAnswerArray();
                       
                   }
                   else{
                      $DenominatorOfTotalQuestionsInTopic++;
                      ClearAnswerArray();
                   }
                                
            }
            ClearQuestionArray();
            
            
            if($NumeratorOfAnsweredQuestionsInTopic>0){            
            $PercentageToAdd = ($NumeratorOfAnsweredQuestionsInTopic/$DenominatorOfTotalQuestionsInTopic);
            $PercentageToAdd = $PercentageToAdd*100;
            
            }
            else{
                $PercentageToAdd = 0;
            }
            //echo $PercentageToAdd;
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
           if($AmountOf100s == 0 || $TotalPercentages ==0){
            echo "<b> 0 %</b>";
           }
           else{
           $TotalToReturn = 100*($TotalPercentages / $AmountOf100s);
           $TotalToReturn =round($TotalToReturn);
           echo "<b>$TotalToReturn %</b>";
           }
           
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
               echo "   <td > $TopicDisplayArray[$e] </td>";
               echo "   <td> $AnswerPercentageArray[$e]%</td>";
               echo "</tr>";               
           }
          
       }
       
?>