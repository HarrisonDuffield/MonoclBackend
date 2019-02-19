package mysqlbackenddealings;
import java.util.ArrayList;
import java.util.Random;

public class Sort {
    public static void QuickSorting(ArrayList<Integer> List,int Low,int High){//0 and size i think
        int OriginalLow=0;
        int OriginalHigh=List.size()-1;
        int LeftPointer=0;
        int RightPointer=List.size()-1;
        if(List.size()<2){
            //break;
        }
        else{
            Random random=new Random();
            int pivot=random.nextInt(List.size()-1)+0;
            System.out.println("Pivot"+List.get(pivot));
            
            while(LeftPointer!=RightPointer){
               // System.out.println("Pivot :"+List.get(pivot));
                //System.out.println("LeftPointer+1 :"+List.get(LeftPointer+1));
                System.out.println("LeftPointer "+LeftPointer);
                System.out.println("LeftPointer get "+List.get(LeftPointer));
                while(List.get(LeftPointer)>=List.get(pivot)){
                   System.out.println("LeftPointer "+LeftPointer);
                    LeftPointer=LeftPointer+1;
                }
               // System.out.println("RightPointer+1:"+List.get(RightPointer-1));
                while(List.get(RightPointer-1)<=List.get(pivot)){
                    System.out.println("RightPointer "+RightPointer);
                    RightPointer=RightPointer-1;
                }
               // System.out.println("LeftPointer "+LeftPointer);
               // System.out.println("RightPointer "+RightPointer);
                int TempStorage=List.get(RightPointer);
                List.set(RightPointer,List.get(LeftPointer));
                List.set(LeftPointer,TempStorage);
            }
            System.out.println(List);
                        
        }
     
}
}


