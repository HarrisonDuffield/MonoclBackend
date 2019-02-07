package mysqlbackenddealings;
import java.sql.*;
public class MySQLBackendDealings {
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
        Statement test = ConnectionFunction.createStatement();
        ResultSet results = test.executeQuery("Select * from answertable");       
        System.out.println(results[2]);
        ConnectionFunction.close();
        System.out.println("Congratulations");
        }
        catch(Exception c){
            System.out.println(c);
        }
               
        }
        
}