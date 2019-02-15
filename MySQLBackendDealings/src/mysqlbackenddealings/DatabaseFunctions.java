/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package mysqlbackenddealings;

import java.sql.*;

/**
 *
 * @author hpd12
 */
public class DatabaseFunctions {
    
    public static String ConnectionLocation = "jdbc:mysql://localhost:3306/monoclmain";
    public static String ConnectionLocationSecondaryTable="jdbc:mysql://localhost:3306/monoclquestionanswers";
    public static String UserName ="JavaConnection";
    public static String Password ="JavaPassword";
    public static String Driver = "com.mysql.jdbc.Driver";
    
    
    public static ResultSet IsTablePresent(String QuestionID){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        ResultSet QueryToReturn = ConnectionFunction.getMetaData().getTables(null,null,QuestionID,null);
        return QueryToReturn;
        }
        catch (Exception Failure){
            Failure.printStackTrace();
            return null;
            
        }
    }
    public static boolean IsItemAlreadyPresent(String QuestionID,String WordToSearchFor){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        String Query ="SELECT 'Count' FROM`"+QuestionID+"`WHERE MainWord ='"+WordToSearchFor+"';";
        System.out.println("Query :"+Query);
        ResultSet QueryToReturn = statement.executeQuery(Query);
        int count=0;
        while(QueryToReturn.next()){
            count=count+1;
        }
        System.out.println("Item already present count"+count);
        if(count>1){
            return true;
        }
        else{
            return false;
        }
        }
        catch(Exception IsItemAlreadyPresentError){
            IsItemAlreadyPresentError.printStackTrace();
            return true;
        }
    }
    public static int GetCount(String QuestionID , String WordToSearchFor){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        ResultSet QueryToReturn = statement.executeQuery("SELECT 'Count' FROM"+QuestionID+"WHERE `MainWord` ='"+WordToSearchFor+"';");
        int Count=QueryToReturn.getInt("Count");
        return Count;
        }
        catch(Exception GetCountFailure){
                GetCountFailure.printStackTrace();
                return 0;
        }
    }
    
    public static ResultSet SearchQueryReturn(String Query){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocation,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        ResultSet QueryToReturn = statement.executeQuery(Query);
        return QueryToReturn;
        }
        catch(Exception FalseSQLQueryResults){
            FalseSQLQueryResults.printStackTrace();
            return null;
        }
    }
    public static Boolean ClearTable(String QuestionID){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        statement.execute("DROP TABLE `"+QuestionID+"`;");
        if(CreateTable(QuestionID)){
            System.out.println("Table recreated");
        };
        return true;
    }
        catch(Exception DeleteFailureQuery){
            DeleteFailureQuery.printStackTrace();
            return false;
        }
    }
    public static Boolean CreateTable(String QuestionID){
        String CreationQuery="\n" +
"CREATE TABLE `monoclquestionanswers`.`"+QuestionID+"` (\n" +
"`AnswerWordId` INT NOT NULL AUTO_INCREMENT ,\n" +
"`PreviousWord` LONGTEXT NULL DEFAULT NULL ,\n" +
"`MainWord` LONGTEXT NOT NULL ,\n" +
"`FollowingWord` LONGTEXT NULL DEFAULT NULL , \n" +
"`Count` INT NOT NULL DEFAULT '0' ,\n" +
"`Percentage` DECIMAL NULL DEFAULT NULL ,PRIMARY KEY(AnswerWordID));";
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        statement.execute(CreationQuery);
        return true;
        }
        catch(Exception FalseSQLQueryResults){
            FalseSQLQueryResults.printStackTrace();
            return false;
        }
    }
    public static boolean InsertData(String QuestionID,String Query){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        statement.executeUpdate(Query);
        ResultSet QueryToReturn = ConnectionFunction.getMetaData().getTables(null,null,QuestionID,null);
        return true;
        }
        catch(Exception Insert){
            Insert.printStackTrace();
            return false;
        }
    }
    
}
