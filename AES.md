# AES

- AES简介

高级加密标准(AES,Advanced Encryption Standard)为最常见的对称加密算法。对称加密算法也就是加密和解密用相同的密钥，具体的加密流程如下图：

<img src="https://imgconvert.csdnimg.cn/aHR0cDovL2ltZy5ibG9nLmNzZG4ubmV0LzIwMTcwMjE5MDgyOTA5Njg4?x-oss-process=image/format,png"></img>

引用地址：[(17条消息) AES加密算法的详细介绍与实现_TimeShatter的博客-CSDN博客_aes加密](https://blog.csdn.net/qq_28205153/article/details/55798628)



为了保持php和java加解密一致，可以使用如下算法做加解密


- PHP

```php
function encrypt($str, $key)
{
    $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
    $data = openssl_encrypt($str, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    return base64_encode($data);
}

function decrypt($str, $key)
{
    $key = substr(openssl_digest(openssl_digest($key, 'sha1', true), 'sha1', true), 0, 16);
    $data = base64_decode($str);
    return openssl_decrypt($data, 'AES-128-ECB', $key, OPENSSL_RAW_DATA, '');
}
```

- JAVA
```java
import sun.misc.BASE64Decoder;
import sun.misc.BASE64Encoder;

import javax.crypto.Cipher;
import javax.crypto.KeyGenerator;
import javax.crypto.SecretKey;
import javax.crypto.spec.SecretKeySpec;
import java.io.IOException;
import java.security.Key;
import java.security.NoSuchAlgorithmException;
import java.security.SecureRandom;

public class AESUtil {

    private static final String KEY_ALGORITHM = "AES";
    /**
     * 默认的加密算法
     */
    private static final String DEFAULT_CIPHER_ALGORITHM = "AES/ECB/PKCS5Padding";


    /**
     * AES 加密操作
     *
     * @param content 待加密内容
     * @param key     加密密钥
     * @return 返回Base64转码后的加密数据
     */
    public static String encrypt(String content, String key) throws Exception {
        try {
            // 创建密码器
            Cipher cipher = Cipher.getInstance(DEFAULT_CIPHER_ALGORITHM);
            byte[] byteContent = content.getBytes("utf-8");
            // 初始化为加密模式的密码器
            cipher.init(Cipher.ENCRYPT_MODE, getSecretKey(key));
            // 加密
            byte[] result = cipher.doFinal(byteContent);
            //通过Base64转码返回
            return byte2Base64(result);
        } catch (Exception ex) {

        }

        return null;
    }

    /**
     * AES 解密操作
     *
     * @param content
     * @param key
     * @return
     */
    public static String decrypt(String content, String key) {
        try {
            //实例化
            Cipher cipher = Cipher.getInstance(DEFAULT_CIPHER_ALGORITHM);
            //使用密钥初始化，设置为解密模式
            cipher.init(Cipher.DECRYPT_MODE, getSecretKey(key));
            //执行操作
            byte[] result = cipher.doFinal(base642Byte(content));
            return new String(result, "utf-8");
        } catch (Exception ex) {

        }

        return null;
    }

    /**
     * 生成加密秘钥
     *
     * @return
     */
    private static Key getSecretKey(final String key) {
        //返回生成指定算法密钥生成器的 KeyGenerator 对象
        try {
            KeyGenerator kg = KeyGenerator.getInstance(KEY_ALGORITHM);
            // 指定算法名称，不同的系统上生成的key是相同的。
            SecureRandom random = SecureRandom.getInstance("SHA1PRNG");
            random.setSeed(key.getBytes());
            //AES 要求密钥长度为 128
            kg.init(128, random);
            //生成一个密钥
            SecretKey secretKey = kg.generateKey();
            // 转换为AES专用密钥
            return new SecretKeySpec(secretKey.getEncoded(), KEY_ALGORITHM);
        } catch (NoSuchAlgorithmException ex) {

        }
        return null;
    }

    /**
     * 字节数组转Base64编码
     *
     * @param bytes
     * @return
     */
    public static String byte2Base64(byte[] bytes) {
        BASE64Encoder encoder = new BASE64Encoder();
        return encoder.encode(bytes);
    }

    /**
     * Base64编码转字节数组
     *
     * @param base64Key
     * @return
     * @throws IOException
     */
    public static byte[] base642Byte(String base64Key) throws IOException {
        BASE64Decoder decoder = new BASE64Decoder();
        return decoder.decodeBuffer(base64Key);
    }

}
```