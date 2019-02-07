package mysqlbackend;
import java.sql.*;
import java.util.ArrayList;
public class MySQLBackend {
    public static String ConnectionLocation = "jdbc:mysql://localhost:3306/monoclmain";
    public static String UserName ="JavaConnection";
    public static String Password ="JavaPassword";
    public static String Driver = "com.mysql.jdbc.Driver";
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
        ResultSet results = statement.executeQuery("Select * FROM answertable");       
        System.out.println();
        System.out.println(results.findColumn("QuestionID"));
        while(results.next()){
            System.out.println(results.getString(results.findColumn("AnswerID")));
        }
        ConnectionFunction.close();
        System.out.println("Congratulations");
        }
        catch(Exception c){
            System.out.println(c);
        }               
        }
        
}