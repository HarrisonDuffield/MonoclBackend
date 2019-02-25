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
        //Statement statement = ConnectionFunction.createStatement();
        String Query ="SELECT * FROM `"+QuestionID+"` WHERE MainWord =? ;";
        PreparedStatement Statement = ConnectionFunction.prepareStatement(Query);
        
        Statement.setString(1,WordToSearchFor);
        //System.out.println("Query Item already presnet : "+Query);
        ResultSet QueryToReturn = Statement.executeQuery();
        int count=0;
        QueryToReturn.beforeFirst();
        
        while(QueryToReturn.next()){
            //System.out.println("Count test 55 "+QueryToReturn.getString("Count"));
            count = Integer.parseInt(QueryToReturn.getString("Count"));
            
        }
        
       // System.out.println("Query to Return print ");            
        //System.out.println("Item already present count "+count);
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
    public static ResultSet SearchQueryReturnSecondaryTable(String Query){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
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
    public static boolean InsertDataDeprecated(String QuestionID,String Query){
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
    public static boolean InsertData(String QuestionID,String Word){
        try{
        Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
        PreparedStatement Statement = ConnectionFunction.prepareStatement("INSERT INTO `"+QuestionID+"` (`AnswerWordId`, `PreviousWord`, `MainWord`, `FollowingWord`, `Count`, `Percentage`)"
                        + " VALUES (NULL, NULL,?, NULL, '1', NULL);"); 
        
        Statement.setString(1,Word);
        Statement.executeUpdate();
        return true;
        }
        catch(Exception InsertDataFailure){
            InsertDataFailure.printStackTrace();
            return false;
        }
    }
    public static boolean UpdateData(String QuestionID,String Word,int CountToUse){
        try{
             Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
             PreparedStatement Statement = ConnectionFunction.prepareStatement("UPDATE `"+QuestionID+"` SET `Count`="+CountToUse+" WHERE `MainWord` = ? ;");
             Statement.setString(1,Word);
             Statement.executeUpdate();
             return true;
        }
        catch(Exception UpdateDataFailure){
            UpdateDataFailure.printStackTrace();
            return false;
        
        }
    
}
    public static boolean PreviousFollowingWordPush(String PreviousWord,String MainWord,String FollowingWord,String QuestionID){
       try{
             Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
             PreparedStatement Statement = ConnectionFunction.prepareStatement("UPDATE `"+QuestionID+"` SET `PreviousWord`= ?,FollowingWord = ?   WHERE MainWord = ?;");
             Statement.setString(1,PreviousWord);
             Statement.setString(2,FollowingWord);
             Statement.setString(3,MainWord);
             Statement.executeUpdate();
             return true;
        }
       catch(Exception PrevFollPushFail){
           PrevFollPushFail.printStackTrace();
           return false;
       }
    }
    public static void PercentageSetting(String QuestionID,int maxsize){
        try{
            Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocationSecondaryTable,UserName,Password);
            ResultSet RetrievalOfCountsQuery = SearchQueryReturnSecondaryTable("SELECT COUNT FROM `"+QuestionID+"`;");
            PreparedStatement Statement = ConnectionFunction.prepareStatement("UPDATE `"+QuestionID+"` SET `Percentage` = ? WHERE Count =?;");
            while(RetrievalOfCountsQuery.next()){
                int Percentage = 100* RetrievalOfCountsQuery.getInt("Count")/maxsize;
                System.out.println("Pushing Percentage of :"+Percentage);
                Statement.setInt(1,Percentage);
                Statement.setInt(2,RetrievalOfCountsQuery.getInt("Count"));
                Statement.executeUpdate();
            }
            
        }
        catch(Exception PercentageFail){
            PercentageFail.printStackTrace();
        }
    }
    
    public static void SignificanceValueSet(String QuestionID){
         try{
            Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocation,UserName,Password);
            ResultSet RetrievalOfCountsQuery = SearchQueryReturnSecondaryTable("SELECT Percentage FROM `"+QuestionID+"`;");
            int CountSoFar =0;
            int iteration=0;
            while(RetrievalOfCountsQuery.next()){
                CountSoFar=CountSoFar + RetrievalOfCountsQuery.getInt("Percentage");
                iteration++;
            }
            int SignificantValue = (CountSoFar/iteration);
            PreparedStatement Statement = ConnectionFunction.prepareStatement("UPDATE `questiontable` SET `SignificantValue` = ? WHERE `QuestionID` = ?;");
            Statement.setInt(1,SignificantValue);
            Statement.setString(2,QuestionID);
            Statement.executeUpdate();
            
         }
         catch(Exception SignifValFail){
             SignifValFail.printStackTrace();
         }
}
    public static void SignificantStringPush(String QuestionID,String StringToPush){
        try{
            System.out.println("String To Push - Preffered Answer "+StringToPush);
            Connection ConnectionFunction = DriverManager.getConnection(ConnectionLocation,UserName,Password);
            PreparedStatement Statement = ConnectionFunction.prepareStatement("UPDATE`questiontable` SET `PreferredAnswer` = ? WHERE `QuestionID` = ?;");
            Statement.setString(1,StringToPush);
            Statement.setString(2,QuestionID);
            Statement.executeUpdate();
            
        }
        catch(Exception SignifStrPushFail){
            SignifStrPushFail.printStackTrace();
        }
    }
}

