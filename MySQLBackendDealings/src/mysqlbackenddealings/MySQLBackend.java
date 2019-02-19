package mysqlbackenddealings;
import java.sql.*;
import java.util.ArrayList;
import static mysqlbackenddealings.DatabaseFunctions.*;
public class MySQLBackend {
    public static String Green = "\033[0;32m";
    public static String Red = "\033[0;31m";
    public static String Yellow = "\033[0;33m";
    public static String Blue = "\033[0;34m";
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
                System.out.println("Word Handler Complete "+RESET);
            }
            else{
                System.err.println("False");
                break;
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
            System.out.println("57"+SplitByWord);
            for (String Word : SplitByWord) {
                if(IsItemAlreadyPresent(QuestionID,Word)==false){
                System.out.println("Item already present");
                System.out.println("Split Item : "+Word);
//                String QueryToSend ="INSERT INTO `"+QuestionID+"` (`AnswerWordId`, `PreviousWord`, `MainWord`, `FollowingWord`, `Count`, `Percentage`)"
//                        + " VALUES (NULL, NULL,'"+Word+"', NULL, '1', NULL);";
//                
//                System.out.println(QueryToSend);
                if(InsertData(QuestionID,Word)){
                    System.out.println("Query was succesful");
                    
                }
                else{
                    
                    System.err.println("Query Unsuccsesful");
                    return false;
                }
            }
            else{
                System.out.println("Else selected");
                System.out.println("Split Item : "+Word);
                int CountToUse=1+GetCount(QuestionID,Word);
                System.out.println("Count To Use : "+ CountToUse);
                String QueryToSend = "UPDATE `"+QuestionID+"` SET `Count`="+CountToUse+" WHERE `MainWord` = '"+Word+"';";
                System.out.println(QueryToSend);  
                if(UpdateData(QuestionID,Word,CountToUse)){
                    System.out.println("Query was succesful");
                    
                    
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
        System.err.println("Error line 90");
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
            ProceedingWordPopulator(AnswerArrayList,ArrayOfMainWords.get(i));
            String PreviousWordToPush =ProceedingWordPopulator(AnswerArrayList,ArrayOfMainWords.get(i));
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
        //for each item in the answer array,split the answer into before the word and the section inclkuding the word,then split the before section
        //by the last space,should return as expected
        //ArrayOfProceedingWords.retainmostcommonword
        for(int i=0;i<AnswerArrayOriginal.size();i++){
            if(AnswerArrayOriginal.get(i).contains(WordToCheck)){//check doen at the top to be more efficent ,so it doesnt check later after already havign doen things
            String[] TempString=AnswerArrayOriginal.get(i).split(" ");
            //int wordcount = TempString.length;
            if(TempString.length>0){//should check to see if its more than 1 word , not too sure if this is going to be an off by 1 problem
                System.out.println(Yellow+"Length beofre: "+TempString.length);
                System.out.println("Word to Check"+WordToCheck);
                System.out.println("Temp String Before split" + TempString[0]);
                TempString=AnswerArrayOriginal.get(i).split(WordToCheck);
                System.out.println(Yellow+"Length after: "+TempString.length);
                //for(int x=0;x<=wordcount;x++){
                System.out.println(TempString[0]);
               // String[] SecondTempStringArray = TempString[0].split(" ");
                System.out.println(Yellow+"Length after 2: "+TempString.length);
                System.out.println(Yellow+"Length after 2: "+TempString[0]);
                ArrayOfProceedingWords.add(SecondTempStringArray[(SecondTempStringArray.length-1)]);
                //}
            }
            else{//not so sure about the poitn of this else statment;
                //return "null";
            }
            }
            else{
                System.out.println("Doesnt contain the WordToCheck, going to iterate again");
            }
        
    }
    //see most common item in the array;
    for(int i=0;i<ArrayOfProceedingWords.size();i++){
        System.out.println(ArrayOfProceedingWords.get(i));
    }
    return FrequencyFinder(ArrayOfProceedingWords);
    }
    private static String FollowingWordPopulator(ArrayList<String> AnswerArrayOriginal,String WordToCheck){
        return "NULL";
                
    }
    private static String FrequencyFinder(ArrayList<String> SourceArray){
        ArrayList<TableItem> UniqueItemArray = new ArrayList();//reu-using table item, doesn tmatter that im using the 
        //main word isntead of previous as its only for orgnisation/storage
        UniqueItemArray.add(new TableItem(SourceArray.get(0),1));
        int MaxNumberAwarded=0;
        String MostCommonWord = " ";
        for(int i=1;i<SourceArray.size();i++){
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
        //sorting begins - self created algorithm,not too efficent but doesnt really matter;
//        int MostCommonCount =0;
//        int EfficentNumberToStopAt = MaxNumberAwarded * 0.75;
//        for(int i=0;i<UniqueItemArray.size();i++){
//            if(UniqueItemArray.get(i).getCount()>=MostCommonCount){
//                MostCommonWord = UniqueItemArray.get(i).getWord();
//                MostCommonCount = UniqueItemArray.get(i).getCount();
//                if(MostCommonCount >= EfficentNumberToStopAt){
//                   break; 
//                }
//                else{
//                    //nothing changes
//                }
//            }
//            else{
//                //item is not more frequent - next item
//            }          
//        }
//    
    
}
}
        
