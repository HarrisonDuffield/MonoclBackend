package mysqlbackenddealings;

public class MySQLBackendDealings {
    public static void main(String[] args) {
        /**order of operation:
        Connects to DB
        Creates folder for each language / accesses the folder for that language
        Goes through each question
        Compares last answe rfor question to last update to that questions file - creates file if one not present
        Gets all the answers for that question
        does the keyword procedure*/
        Class.forName("com.mysql.jdbc.Driver");
        Connection ConnectionFunction = DriverManager.getConncetion("jdbc:mysql://localhost:3306/monoclmain,PHPConnection2,PHPPassword12);
        Statement test = ConnectionFunction.createStatement();


    }

}
