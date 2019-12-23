
import java.util.*;
public class dddc
{
	public static void main(String[] args)
	{

        
		String EncryptedText = args[0];

		
		int[] Dec_Key = new int[5];


		
		int colSize = Dec_Key.length;
		int rowSize = EncryptedText.length() / colSize;
		ArrayList<String> al3 = new ArrayList<String>();
		
		String tempStr = "";
		
		for(int i =0; i < rowSize; i++)
		{
			for(int j = i; j < EncryptedText.length(); j += rowSize)
			{	tempStr += EncryptedText.charAt(j);
				
			}

			al3.add(tempStr);
			tempStr = "";
		}
		
		
		Dec_Key[0] = 2;
		Dec_Key[1] = 5;
		Dec_Key[2] = 1;
		Dec_Key[3] = 3;
		Dec_Key[4] = 4;
        
		String DecryptionRound1 = "";
		for(int i = 0; i < al3.size(); i++)
		{	//System.out.println(EncryptionKey.get(i));
			for(int j =0; j < colSize; j++)
			{	DecryptionRound1 += al3.get(i).charAt(Dec_Key[j]-1);
				
			}
			
		}
		
		
		al3 = new ArrayList<String>();
		
		for(int i =0; i < rowSize; i++)
		{
			for(int j = i; j < DecryptionRound1.length(); j += rowSize)
			{	tempStr += DecryptionRound1.charAt(j);
			}

			al3.add(tempStr);
			tempStr = "";
		}
		
		
		String DecryptionRound2 = "";
		for(int i = 0; i < al3.size(); i++)
		{	//System.out.println(EncryptionKey.get(i));
			for(int j =0; j < colSize; j++)
			{	DecryptionRound2 += al3.get(i).charAt(Dec_Key[j]-1);
				
			}

		}
		
            //System.out.println(DecryptionRound2);
//            System.out.println();
			System.out.println(DecryptionRound2);

		
		
	}
}
