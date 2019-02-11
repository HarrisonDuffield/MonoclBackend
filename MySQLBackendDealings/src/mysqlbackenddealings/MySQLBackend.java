package mysqlbackend;
import java.io.File;
import java.io.FileWriter;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import mysqlbackenddealings.TableItem;
public class MySQLBackend {
    public static String ConnectionLocation = "jdbc:mysql://localhost:3306/monoclmain";
    public static String UserName ="JavaConnection";
    public static String Password ="JavaPassword";
    public static String Driver = "com.mysql.jdbc.Driver";
    public static ArrayList QuestionIDArray = new ArrayList();
    public static ArrayList<TableItem> WordAndCounts = new ArrayList<TableItem>();
    public static void main(String[] args) {
        /**order of operation:
        Connects to DB
        Creates folder for each language / accesses the folder for that language
        Goes through each question
        Compares last answe rfor question to last update to that questions file - creates file if one not present
        Gets all the answers for that question
        does the keyword procedure*/
        try{            
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocation,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        ResultSet QuestionIDResults = statement.executeQuery("SELECT DISTINCT QuestionID FROM answertable");
        int counter =0;
        while(QuestionIDResults.next()){            
            System.out.println("QuestionID:"+QuestionIDResults.getString(counter));
            //statements to gather the results;
            String QuestionIDString = QuestionIDResults.getString(counter);
            ResultSet AnswerReturnResults = statement.executeQuery("SELECT AnswerText FROM answertable WHERE QuestionID ="+QuestionIDString);
            ArrayList<String> AnswerArrayList = new ArrayList<String>();
            while(AnswerReturnResults.next()){
                AnswerArrayList.add(AnswerReturnResults.getString("AnswerText"));
            }
            //funcction to prepare the output file with the string to output;
            if(PushToFile(QuestionIDResults.getString(counter),OutputStringCreator(QuestionIDString,AnswerArrayList))){      
                ConnectionFunction.close();
                System.out.println("Congratulations");
                }
            else{
                System.err.println("Push to file didn't work");
            }
            counter++;
        }
        }             
        catch(Exception FalseSQLQueryResults){
            FalseSQLQueryResults.printStackTrace();
        }
    }
    
    
    
    public static boolean PushToFile(String QuestionID,String OutputToPush){
        String QustionIDPrepatoryString;
        String DirectoryString = "..\\MonoclAssetFiles\\"+(QuestionID)+".txt";
        File FileAtDirectory = new File(DirectoryString);
        try{
            Files.deleteIfExists(Paths.get(DirectoryString));
            //file already exists
            //more efficent to just delete the file rather than  
            //try appending it and checcking if  the reuslts are the same
            FileAtDirectory.delete();
        }
        catch(Exception FileDeleteIfExistsExemption){
            //file does not exist - does not require to be deleted;
           FileDeleteIfExistsExemption.printStackTrace();
           return false;
           
        }
        try{
            FileWriter FileWriter = new FileWriter(FileAtDirectory);
            FileWriter.write(OutputToPush);
            FileWriter.close();
            return true;
        }
        catch(Exception FileWriterException){
                FileWriterException.printStackTrace();
                return false;
        }
        
        
    }
    public static String OutputStringCreator(String QuestionID){
        String OutputString = "QuestionID :"+"\n";
        OutputString = OutputString + WordAndCounts.get(0).HeaderCreator();
        for(int i=0;i<WordAndCounts.size();i++){
            OutputString=OutputString+WordAndCounts.get(i).TableToPrintReturn();
        }
        return OutputString;
        
        
    }
    public static void WordSplitTablePopulator(String QuestionID,ArrayList<String> AnswerArray){
        for(int i=0;i<AnswerArray.size();i++){//split by answer
            String[] ArraySplit =AnswerArray.get(i).split(" ");
            ArrayList ArraySplitList = new ArrayList(Arrays.asList(ArraySplit));
            for(int x=0;x<ArraySplitList.size();x++){//split by word
                boolean ispresent= false;
                boolean forloopcomplete=false;
               for(int y=0;y<WordAndCounts.size();y++){//checks each word in there
                if(WordAndCounts.get(y).getWord() == ArraySplitList.get(x)){
                   WordAndCounts.get(x).CountIncreaser();
                   ispresent=true;
                   forloopcomplete=true;
                   break;
                }
                else{
                    forloopcomplete=true;
                    ispresent=false;
                }
               }
               if(ispresent == false && forloopcomplete==true){
                   WordAndCounts.add(new TableItem(ArraySplitList.get(x).toString(),1));
               }
            }
       }
    }
    
}
        
