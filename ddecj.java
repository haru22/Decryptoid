
import java.util.*;
public class ddecj
{
	public static void main(String[] args)
	{
		String Message = args[0];
		String keyLine1 = "3 1 4 5 2";

		StringTokenizer st = new StringTokenizer(keyLine1," ");
		int size = 0;
		ArrayList<Integer> EncryptionKey = new ArrayList<Integer>();
		while(st.hasMoreElements())
		{	EncryptionKey.add(Integer.parseInt(st.nextToken()));
			size++;
		}
        
       

		Message = Message.replace(" ","");
		int keySize = size;
		int MessageSize = Message.length();
		
		for(int i = MessageSize; i < 999999; i++)
		{
			if(i % size == 0)
			{	break;	}
			else
			{	Message = Message + "z";
			}
		}
		
		ArrayList<String> al = new ArrayList<String>();
		ArrayList<String> al2 = new ArrayList<String>();

		String str = "";
		for(int i=1; i < Message.length()+1; i++)
		{	
			str += Message.charAt(i-1);
			
			if( i % size == 0 && i != 0)
			{
				
				al.add(str);
				str = "";
			}
		}
		
		for(int i = 0; i < al.size(); i++)
		{	for(int j =0; j < EncryptionKey.size(); j++)
			{	
			}
			
		}
		String EncryptionRound1 = "";
		String EncryptionRound2 = "";
		
		
		
		for(int i = 0; i < keySize; i++)
		{	
			for(int j =0; j < al.size(); j++)
			{	EncryptionRound1 += al.get(j).charAt(EncryptionKey.get(i)-1);
				
			}

		}
		
		
		for(int i=1; i < EncryptionRound1.length()+1; i++)
		{	
			str += EncryptionRound1.charAt(i-1);
			
			if( i % size == 0 && i != 0)
			{

				al2.add(str);
				str = "";
			}
		}
		
	
		
	
		
		for(int i = 0; i < keySize; i++)
		{	
			for(int j =0; j < al2.size(); j++)
			{	EncryptionRound2 += al2.get(j).charAt(EncryptionKey.get(i)-1);
				
			}
			//System.out.println();
		}
		

		String EncryptedText = EncryptionRound2;

		
		int[] Enc_Key = new int[EncryptionKey.size()];
		int[] Dec_Key = new int[EncryptionKey.size()];

		for(int i =0; i < EncryptionKey.size(); i++)
		{	Enc_Key[i] = EncryptionKey.get(i);
			Dec_Key[EncryptionKey.get(i)-1] = i+1;
		}

		
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
		
			System.out.println(EncryptionRound2);

		
		
	}
}
