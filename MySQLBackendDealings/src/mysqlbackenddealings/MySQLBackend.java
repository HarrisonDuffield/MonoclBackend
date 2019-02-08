package mysqlbackend;
import java.io.File;
import java.io.FileWriter;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.ArrayList;
public class MySQLBackend {
    public static String ConnectionLocation = "jdbc:mysql://localhost:3306/monoclmain";
    public static String UserName ="JavaConnection";
    public static String Password ="JavaPassword";
    public static String Driver = "com.mysql.jdbc.Driver";
    public static ArrayList QuestionIDArray = new ArrayList();
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
            ArrayList AnswerArrayList = new ArrayList();
            while(AnswerReturnResults.next()){
                AnswerArrayList.add
            }
            //funcction to prepare the output file with the string to output;
            if(PushToFile(results.getString(counter),OutputStringCreator(QuestionIDString))){      
                ConnectionFunction.close();
                System.out.println("Congratulations");
                }
            else{
                System.err.println("Push to file didn't work");
            }
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
    public static String OutputStringCreator(String QuestionID,ArrayList AnswerArray){
        String OutputString = QuestionID ;
        
    }
  }
        
