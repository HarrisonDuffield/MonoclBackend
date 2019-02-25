package mysqlbackenddealings;

import java.sql.ResultSet;
import java.util.ArrayList;
import java.util.Collections;
import static mysqlbackenddealings.DatabaseFunctions.*;
import static mysqlbackenddealings.Sort.*;
public class PrefferedWordTestBed {
    public static void Organisation(String QuestionID){
        try{
        String Query = "SELECT `Percentage` FROM `"+QuestionID+"`;";
        ResultSet ItemsInTable = SearchQueryReturnSecondaryTable(Query);
        ArrayList PercentageAL = new ArrayList();
        while(ItemsInTable.next()){
            PercentageAL.add(ItemsInTable.getInt("Percentage"));
          
        }
        System.out.println("Before Percentage Array : "+ PercentageAL.toString());
        String Word ="";
        PercentageAL=QuickSort(PercentageAL,0,(PercentageAL.size()-1));
        System.out.println("After Percentage Array :"+PercentageAL.toString());
        Collections.reverse(PercentageAL);
        System.out.println("Reverse Percentage Array :"+PercentageAL.toString());
        Query = "SELECT `MainWord` FROM `"+QuestionID+"` WHERE `Percentage` = "+PercentageAL.get(0)+";";
        ItemsInTable = SearchQueryReturnSecondaryTable(Query);
        while(ItemsInTable.next()){
            Word=ItemsInTable.getString("MainWord");
            }        
        String IterativeStringTestReturn =IterativeStringCreator(Word,QuestionID);
        SignificantStringPush(QuestionID,IterativeStringTestReturn);
        
       // while(!"NULL".equals(IterativeStringTestReturn)){
       //     System.out.println("T R I G G E R E D");
        //    IterativeStringTestReturn=IterativeStringTest(IterativeStringTestReturn,QuestionID);
        //}
             
        }
    catch(Exception OrganisationFail){
        OrganisationFail.printStackTrace();
    }
    }
    public static String PreviousRecursiveStringCreator(String Word,String QuestionID){
        try{
        String Query = "SELECT PreviousWord FROM `"+QuestionID+"` WHERE MainWord = '"+Word+"';";
        System.out.println(Query);
        ResultSet ItemsInTable = SearchQueryReturnSecondaryTable(Query);
        String PreviousStringNew ="=";//as i defineS
        String PreviousString = "_";
        while(ItemsInTable.next()){
             PreviousStringNew=ItemsInTable.getString("PreviousWord");
        }
        System.out.println(PreviousStringNew);
        while(PreviousStringNew !="NULL"){
            PreviousString=PreviousStringNew;
            PreviousRecursiveStringCreator(PreviousString,QuestionID);
        }
        
        return PreviousString;
        }
        catch(Exception RecurStringFail){
        RecurStringFail.printStackTrace();
        return "NULL Error";
        }
    }
    public static String IterativeStringTest(String Start,String QuestionID){
    try{
            String BaseString = Start;
            String MainWord=Start;
            String Query = "SELECT PreviousWord FROM `"+QuestionID+"` WHERE MainWord = '"+MainWord+"' LIMIT 1;";
            ResultSet ItemsInTable = SearchQueryReturnSecondaryTable(Query);
            while(ItemsInTable.next()){
                MainWord=ItemsInTable.getString("PreviousWord");
            }
            return MainWord;
    }
    catch(Exception TestFail){
        TestFail.printStackTrace();
        return "fail";
    }
    }
    public static String IterativeStringCreator(String Start,String QuestionID){
        try{
            String BaseString = Start;
            ArrayList OutputString = new ArrayList();
            String MainWord = Start;
            while(!"NULL".equals(MainWord)){                 
                 String Query = "SELECT PreviousWord FROM `"+QuestionID+"` WHERE MainWord = '"+MainWord+"' LIMIT 1;";
                 System.out.println("MainWord" +MainWord);
                 ResultSet ItemsInTable = SearchQueryReturnSecondaryTable(Query);
                 while(ItemsInTable.next()){
                     MainWord=ItemsInTable.getString("PreviousWord");
                     MainWord=MainWord.toString();
                     if(ItemsInTable.getString("PreviousWord")==null){
                         return OutputString.toString();
                     }
                 }
                     if(!"NULL".equals(MainWord)){
                         OutputString.add(MainWord);
                     }
                     else {
                         System.out.println("I should end here");
                         break;
                        
                     }
                 }
                
            
            Collections.reverse(OutputString);
            OutputString.add(BaseString);
            System.out.println("Now following Word");
            while(!"NULL".equals(MainWord)){                             
                 String Query = "SELECT FollowingWord FROM `"+QuestionID+"` WHERE MainWord = '"+MainWord+"';";
                 System.out.println(Query);
                 ResultSet ItemsInTable = SearchQueryReturnSecondaryTable(Query);
                 while(ItemsInTable.next()){
                     MainWord=ItemsInTable.getString("FollowingWord");
                 }
                     if(!"NULL".equals(MainWord)){
                         OutputString.add(MainWord);
                     }
                     else{
                         
                       break; 
                     }
                 }
                
            
        return OutputString.toString();
        }
        catch(Exception IterativeStringCreatorPrevFail){
            IterativeStringCreatorPrevFail.printStackTrace();
            return null;
        }
            
}
}