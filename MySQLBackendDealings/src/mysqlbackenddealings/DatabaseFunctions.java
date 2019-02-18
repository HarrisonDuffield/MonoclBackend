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
    public static int GetCount(String QuestionID,String WordToSearchFor){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        Statement statement = ConnectionFunction.createStatement();
        String Query ="SELECT * FROM`"+QuestionID+"`WHERE MainWord ='"+WordToSearchFor+"';";
        System.out.println("Query Item already presnet : "+Query);
        ResultSet QueryToReturn = statement.executeQuery(Query);
        int count=0;
        QueryToReturn.beforeFirst();
        
        while(QueryToReturn.next()){
            System.out.println("Count test 55 "+QueryToReturn.getString("Count"));
            count = Integer.parseInt(QueryToReturn.getString("Count"));
            
        }
        
        System.out.println("Query to Return print ");            
        System.out.println("Item already present count "+count);
        if(count>0){
            
            return count;
        }
        else{
            return count;
        }
        }
        catch(Exception IsItemAlreadyPresentError){
            IsItemAlreadyPresentError.printStackTrace();
            return 0;
        }
    }
    public static boolean IsItemAlreadyPresent(String QuestionID , String WordToSearchFor){
       if(GetCount(QuestionID,WordToSearchFor)>0){
           return true;
       }
       else{
           return false;
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
