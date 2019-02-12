package mysqlbackenddealings;
import java.sql.*;
import java.util.ArrayList;
import static mysqlbackenddealings.DatabaseFunctions.*;
public class MySQLBackend {
    public static void main(String[] args) {
        /**order of operation:
        Connects to DB
        Creates folder for each language / accesses the folder for that language
        Goes through each question
        Compares last answe rfor question to last update to that questions file - creates file if one not present
        Gets all the answers for that question
        does the keyword procedure*/
        try{
        ResultSet QuestionIDResults = SearchQueryReturn("SELECT DISTINCT QuestionID FROM answertable");
        int counter =1;
        while(QuestionIDResults.next()){            
            System.out.println("QuestionID:"+QuestionIDResults.getString(counter));
            //statements to gather the results;
            String QuestionIDString = QuestionIDResults.getString(counter);
            ResultSet AnswerReturnResults = SearchQueryReturn("SELECT AnswerText FROM answertable WHERE QuestionID ="+QuestionIDString);
            ArrayList<String> AnswerArrayList = new ArrayList<String>();
            while(AnswerReturnResults.next()){
                AnswerArrayList.add(AnswerReturnResults.getString("AnswerText"));
            }
            //funcction to prepare the output file with the string to output;
            if(WordSplitTablePopulator(QuestionIDString,AnswerArrayList) == true){
                System.out.println("True");
            }
            else{
                System.err.println("False");
            }
        }
        }             
        catch(Exception FalseSQLQueryResults){
            FalseSQLQueryResults.printStackTrace();
        }
    }
     
    
    
    public static boolean WordSplitTablePopulator(String QuestionID,ArrayList<String> AnswerArray){
        try{
        if(IsTablePresent(QuestionID).next()){
            ClearTable(QuestionID);
            System.out.println("Table Replaced");
            
        }
        else{
            if(CreateTable(QuestionID)){
                System.out.println("Table Now  exists");
            };
           
        }
        for(int i=0;i<AnswerArray.size();i++){
            String[] SplitByWord=AnswerArray.get(i).split(" "); 
            System.out.println(SplitByWord);
            for (String Word : SplitByWord) {
                System.out.println("Split Item : "+Word);
                String QueryToSend ="INSERT INTO `"+QuestionID+"` (`AnswerWordId`, `PreviousWord`, `MainWord`, `FollowingWord`, `Count`, `Percentage`)"
                        + " VALUES (NULL, NULL,'"+Word+"', NULL, '0', NULL);";
                
                System.out.println(QueryToSend);
                if(InsertData(QuestionID,QueryToSend)){
                    System.out.println("Query was succesful");
                    return true;
                }
                else{
                    System.err.println("Query Unsuccsesful");
                    return false;
                }
            }
        }
        }
        catch(Exception TablePresent){
        TablePresent.printStackTrace();   
        return false;
        }
        System.err.println("Error line 74");
        return false;
                
    
        
    
}
    
}
        
