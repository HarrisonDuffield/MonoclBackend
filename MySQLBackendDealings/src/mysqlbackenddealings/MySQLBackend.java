package mysqlbackenddealings;
import java.sql.*;
import java.util.ArrayList;
import static mysqlbackenddealings.DatabaseFunctions.*;
import static mysqlbackenddealings.PrefferedWordTestBed.Organisation;
public class MySQLBackend {
    public static String Green = "\033[0;32m";
    public static String Red = "\033[0;31m";
    public static String Yellow = "\033[0;33m";
    public static String Blue = "\033[0;34m";
    public static String Purple = "\033[1;35m";
    public static String Cyan = "\033[1;36m";
    public static  String RESET = "\033[0m";
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
            System.out.println(Green+"\n\nQuestionID:"+QuestionIDResults.getString(counter)+RESET);
            //statements to gather the results;
            String QuestionIDString = QuestionIDResults.getString(counter);
            ResultSet AnswerReturnResults = SearchQueryReturn("SELECT AnswerText FROM answertable WHERE QuestionID ="+QuestionIDString);
            ArrayList<String> AnswerArrayList = new ArrayList<String>();
            while(AnswerReturnResults.next()){
                AnswerArrayList.add(AnswerReturnResults.getString("AnswerText"));
            }
            //funcction to prepare the output file with the string to output;
            if(WordSplitTablePopulator(QuestionIDString,AnswerArrayList) == true){
                System.out.println(Blue+"\n Now on word handling"+RESET);
                ProceedingAndFollwingWordHandler(QuestionIDString,AnswerArrayList);
                System.out.println("Word Handler Complete \n \n "+RESET);
                PercentageSetting(QuestionIDString,AnswerArrayList.size());
                SignificanceValueSet(QuestionIDString);
                Organisation(QuestionIDString);
                
                
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
            System.out.println(Red+"\n Answer Being Worked On : "+i+RESET);
            String[] SplitByWord=AnswerArray.get(i).split(" "); 
            //System.out.println("57"+SplitByWord);
            for (String Word : SplitByWord) {
                if(IsItemAlreadyPresent(QuestionID,Word)==false){                
                System.out.println("Split Item : "+Word);
                if(InsertData(QuestionID,Word)){                                       
                }
                else{                    
                    System.err.println("Query Unsuccsesful");
                    return false;
                }
            }
            else{
                int CountToUse=1+GetCount(QuestionID,Word);
                int PercentageToUse= 100*CountToUse/AnswerArray.size();
                String QueryToSend = "UPDATE `"+QuestionID+"` SET `Count`="+CountToUse+"WHERE `MainWord` = '"+Word+"';";                
                if(UpdateData(QuestionID,Word,CountToUse)){                 
                                       
                }
                else{
                    System.err.println("Query Unsuccsesful");
                    return false;
                }
            }
        }
    }
        return true;
    }
    catch(Exception TablePresent){
        TablePresent.printStackTrace();   
       // System.err.println("Error line 90");
        return false;
    }           
           
    
    }
    public static void ProceedingAndFollwingWordHandler(String QuestionID, ArrayList<String> AnswerArrayList) {
        try{
        ArrayList AnswerArray = AnswerArrayList;//data gathering and organistaion dealt with in one method to be more efficent as they will both build off this 
        ArrayList<String> ArrayOfMainWords = new ArrayList();
        ResultSet CurrentItems = SearchQueryReturnSecondaryTable("SELECT * FROM `"+QuestionID+"`;");
        while(CurrentItems.next()){
            ArrayOfMainWords.add(CurrentItems.getString("MainWord"));
            }
        
        for(int i=0;i<ArrayOfMainWords.size();i++){
            //System.out.println(ArrayOfMainWords.get(i));
            System.out.println("Item:"+ArrayOfMainWords.get(i));
            String PreviousWordToPush =ProceedingWordPopulator(AnswerArrayList,ArrayOfMainWords.get(i));
            System.out.println("Word To Push "+ PreviousWordToPush);
            String FollowingWordToPush = FollowingWordPopulator(AnswerArrayList,ArrayOfMainWords.get(i));
            PreviousFollowingWordPush(PreviousWordToPush,ArrayOfMainWords.get(i),FollowingWordToPush,QuestionID);
        }
        }
        catch(Exception ProFollHandlerFailure){
            ProFollHandlerFailure.printStackTrace();
        }
    }
        
    private static String ProceedingWordPopulator(ArrayList<String> AnswerArrayOriginal,String WordToCheck){        
        ArrayList<String> ArrayOfProceedingWords = new ArrayList();      
        for(int i=0;i<AnswerArrayOriginal.size();i++){            
            if(AnswerArrayOriginal.get(i).contains(WordToCheck)){//check doen at the top to be more efficent ,so it doesnt check later after already havign doen things
            String[] TempString=AnswerArrayOriginal.get(i).split("\\s");            
            if(TempString.length>1){//should check to see if its more than 1 word , not too sure if this is going to be an off by 1 problem
                int count =0;
                for(int j=1;j<TempString.length;j++){                   
                    if(TempString[j].contentEquals(WordToCheck)){
                        System.out.println( "Item To Add " +TempString[j-1]);
                        ArrayOfProceedingWords.add(TempString[j-1]);
                    }
                    else{
                       }
                }//
                
            }
            }
            else{
                //return "error Answers dont contain asnwers";
               // System.out.println("Doesnt contain the WordToCheck, going to iterate again");
            }   
    }
    if(ArrayOfProceedingWords.size()>0){
    return FrequencyFinder(ArrayOfProceedingWords);
    }
    else{
        return "NULL";
    }
    }
    private static String FollowingWordPopulator(ArrayList<String> AnswerArrayOriginal,String WordToCheck){
       ArrayList<String> ArrayOfFollowingWords = new ArrayList();      
        for(int i=0;i<AnswerArrayOriginal.size();i++){            
            if(AnswerArrayOriginal.get(i).contains(WordToCheck)){//check doen at the top to be more efficent ,so it doesnt check later after already havign doen things
            String[] TempString=AnswerArrayOriginal.get(i).split("\\s");            
            if(TempString.length>1){//should check to see if its more than 1 word , not too sure if this is going to be an off by 1 problem
                int count =0;
                for(int j=0;j<TempString.length -1;j++){
                    if(TempString[j].contentEquals(WordToCheck)&& j!=TempString.length){
                        System.out.println("Item To Add " + TempString[j+1]);
                        ArrayOfFollowingWords.add(TempString[j+1]);
                    }
                    else{
                       }
                }//
                
            }
            }
            else{
                //return "error Answers dont contain asnwers";
               // System.out.println("Doesnt contain the WordToCheck, going to iterate again");
            }   
    }
    if(ArrayOfFollowingWords.size()>0){
    return FrequencyFinder(ArrayOfFollowingWords);
    }
    else{
        return "NULL";
    }
    }
    private static String FrequencyFinder(ArrayList<String> SourceArray){
        if(SourceArray.size()>0){
        System.out.println("Source Array Size "+ SourceArray.size() + SourceArray.get(0));
        ArrayList<TableItem> UniqueItemArray = new ArrayList();//reu-using table item, doesn tmatter that im using the 
        //main word isntead of previous as its only for orgnisation/storage
        UniqueItemArray.add(new TableItem(SourceArray.get(0),1));
        int MaxNumberAwarded=0;
        for(int i=0;i<SourceArray.size();i++){
            System.out.println(Purple+SourceArray.get(i)+RESET);
        }
        String MostCommonWord = " ";
        for(int i=0;i<SourceArray.size();i++){
            for(int j=0;j<UniqueItemArray.size();j++){
                if(UniqueItemArray.get(j).getWord()==SourceArray.get(i)){
                    UniqueItemArray.get(j).CountIncreaser();//increase count by 1;
                    if(UniqueItemArray.get(j).getCount()>MaxNumberAwarded){
                        MaxNumberAwarded=UniqueItemArray.get(j).getCount();
                        MostCommonWord = UniqueItemArray.get(j).getWord();
                        
                    }
                else{
                    UniqueItemArray.add(new TableItem(SourceArray.get(i),1));
                }
            }
                
        }
        }
        return MostCommonWord;
        }
        else{
            return "NULL";
        }
       
    
}
}
        
