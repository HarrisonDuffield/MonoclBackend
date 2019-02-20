package mysqlbackenddealings;
import java.util.ArrayList;
import java.util.Random;

public class Sort {
    public static ArrayList QuickSorting(ArrayList<Integer> List,int Low,int High){
        int OriginalLow=0;
        int OriginalHigh=List.size()-1;
        int LeftPointer=Low;
        int RightPointer=High;
        System.out.println("Recursive Call");
        if(List.size()<2){
            return List;
        }
        else{
            Random random=new Random();
            int pivot=List.get(random.nextInt((List.size()-1))+0);
            
            
            while(LeftPointer<=RightPointer){
//                System.out.println("Pivot :"+List.get(pivot));
                //System.out.println("LeftPointer+1 :"+List.get(LeftPointer+1));
               // System.out.println("LeftPointer "+LeftPointer);
                //System.out.println("LeftPointer get "+List.get(LeftPointer));
                while(List.get(LeftPointer)<pivot){
                   System.out.println("LeftPointer "+LeftPointer);
                    LeftPointer++;
                }
               
                while(List.get(RightPointer)>pivot){
                   System.out.println(" 57 RightPointer "+RightPointer+ "Pivot : " +pivot);
                    RightPointer--;
                }
              //  System.out.println("LeftPointer "+LeftPointer);
                //System.out.println("RightPointer "+RightPointer);
                int TempStorage=List.get(LeftPointer);
                if(LeftPointer<=RightPointer){
                List.set(LeftPointer,List.get(RightPointer));
                List.set(RightPointer,TempStorage);
                 LeftPointer++;
                 RightPointer--;
                }
                if(Low<RightPointer){
                    QuickSorting(List,Low,RightPointer);
                }
                if(LeftPointer<High){
                    QuickSorting(List,LeftPointer,High);
                }
                    
                }
            }
            return List;
                        
        }
     
}



