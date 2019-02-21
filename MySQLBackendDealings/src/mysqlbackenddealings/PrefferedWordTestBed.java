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
        System.out.println(PreviousRecursiveStringCreator(Word,QuestionID));
             
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
        if(PreviousStringNew !="NULL"){
            PreviousString=PreviousStringNew;
            PreviousRecursiveStringCreator(PreviousString,QuestionID);
        }
        return "Word : "+Word+"Previous : "+PreviousString;
        }
        catch(Exception RecurStringFail){
        RecurStringFail.printStackTrace();
        return "NULL Error";
        }
    }   
}