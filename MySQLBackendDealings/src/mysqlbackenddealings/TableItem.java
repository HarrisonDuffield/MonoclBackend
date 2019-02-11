package mysqlbackenddealings;
public class TableItem {
    private String PreviousWord;
    private String Word;
    private String FollowingWord;
    private int Count;
    private int percentage;

    public TableItem(String Word, int Count) {
        this.PreviousWord =null;
        this.Word = Word;
        this.FollowingWord =null;
        this.Count = Count;
        this.percentage = 0;
    }
    
    
    public String HeaderCreator(){
        String HeaderToPrint = String.format("%300s %300s %300s %30s %30s","PreviousWord","MainWord","FollowingWord","|","Count","|","Percentage\n");
        return HeaderToPrint;
    }
    public String TableToPrintReturn(){
        String AddItemToTable = String.format("300s %300s %300s %30s %30s",this.PreviousWord,this.Word,this.FollowingWord,"|",this.Count,"|",this.percentage);
        return AddItemToTable;
    }

    public String getPreviousWord() {
        return PreviousWord;
    }

    public void setPreviousWord(String PreviousWord) {
        this.PreviousWord = PreviousWord;
    }

    public String getWord() {
        return Word;
    }

    public void setWord(String Word) {
        this.Word = Word;
    }

    public String getFollowingWord() {
        return FollowingWord;
    }

    public void setFollowingWord(String FollowingWord) {
        this.FollowingWord = FollowingWord;
    }

    public int getCount() {
        return Count;
    }

    public void setCount(int Count) {
        this.Count = Count;
    }
    public void CountIncreaser(){
        this.Count = this.Count++;
    }

    public int getPercentage() {
        return percentage;
    }

    public void setPercentage(int percentage) {
        this.percentage = percentage;
    }

    
    
}
