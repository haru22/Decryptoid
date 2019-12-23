import javax.crypto.Cipher;
import javax.crypto.spec.SecretKeySpec;
import java.io.UnsupportedEncodingException;
import java.nio.charset.StandardCharsets;

public class des {
    private static byte[] toHexByteArray(String self) {
        byte[] bytes = new byte[self.length() / 2];
        for (int i = 0; i < bytes.length; ++i) {
            bytes[i] = ((byte) Integer.parseInt(self.substring(i * 2, i * 2 + 2), 16));
        }
        return bytes;
    }

    private static void printHexBytes(byte[] self, String label) {
        System.out.printf("%s: ", label);
        for (byte b : self) {
            int bb = (b >= 0) ? ((int) b) : b + 256;
            String ts = Integer.toString(bb, 16).toUpperCase();
            if (ts.length() < 2) {
                ts = "0" + ts;
            }
            System.out.print(ts);
        }
        System.out.println();
    }
    
    public byte hexToByte(String hexString) {
        int firstDigit = toDigit(hexString.charAt(0));
        int secondDigit = toDigit(hexString.charAt(1));
        return (byte) ((firstDigit << 4) + secondDigit);
    }
    
    private int toDigit(char hexChar) {
        int digit = Character.digit(hexChar, 16);
        if(digit == -1) {
            throw new IllegalArgumentException(
              "Invalid Hexadecimal Character: "+ hexChar);
        }
        return digit;
    }
    
    public byte[] decodeHexString(String hexString) {
        if (hexString.length() % 2 == 1) {
            throw new IllegalArgumentException(
              "Invalid hexadecimal String supplied.");
        }
         
        byte[] bytes = new byte[hexString.length() / 2];
        for (int i = 0; i < hexString.length(); i += 2) {
            bytes[i / 2] = hexToByte(hexString.substring(i, i + 2));
        }
        return bytes;
    }

    public static void main(String[] args) throws Exception {
        des test = new des();
        String temp = args[0];
        String strKey = "0e429232ea6d0d73";
        byte[] keyBytes = toHexByteArray(strKey);
        SecretKeySpec key = new SecretKeySpec(keyBytes, "DES");
        Cipher encCipher = Cipher.getInstance("DES");
        encCipher.init(Cipher.ENCRYPT_MODE, key);
        if(Boolean.parseBoolean(args[1]))
        {
            String strPlain = temp;
            byte[] plainBytes = strPlain.getBytes();
            byte[] encBytes = encCipher.doFinal(plainBytes);
            printHexBytes(encBytes, "Encrypted");
        }else
        {
            byte[] b = test.decodeHexString(temp);
            Cipher decCipher = Cipher.getInstance("DES");
            decCipher.init(Cipher.DECRYPT_MODE, key);
            byte[] decBytes = decCipher.doFinal(b);
            try {
                System.out.print("Decrypted: ");
    			System.out.println(new String(decBytes, "UTF-8"));
    		} catch (UnsupportedEncodingException e) {
    			// TODO Auto-generated catch block
    			e.printStackTrace();
    		}
            
        }
    }
}