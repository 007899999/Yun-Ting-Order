<?php
$config = array (
    //应用ID,您的APPID。
    'app_id' => "2018111362157238",

    //商户私钥
    'merchant_private_key' => "MIIEowIBAAKCAQEA6Vb9Sg4rAXl2qRqO94sit/uWyiAFyCb4F3p2InAhcnJJPXI+OIZI10gKplojkxDXajQxbf4iYvWuq+Ow291N9TaSABcllX1/o0ST/xJOoi62xauYEE88HHIWQNpCSVIE+eGxrokDtqoqxUHlaHVLxnE99hxnN68QWQEVAlGrjhGMVencnXHwyegDQ8nndIG9g1gpUffyYnmYnhehsyhAHSTCaplWaUy7MHln7JeXeBB30fafboGqXPxXgy3N1np58QvefihIjgwPFHqdiY8N11jU/ZygvBwbVdkkij30NEzPl5AfhHj2PdN9sMTNp9COoFH1vHk+JQk8aVnuJm+dUwIDAQABAoIBAQCR7miMlx3IV0mh6s08/dRh0kP092AGVHDWZi1ZzlzssZMijb9iJIGLui+G6FrVUDQY5LmBO+4Pi+2I7OagKuDlmhJnYhKMUqev2WThonZxMdv29iEtGhDTDXrjZl0Mc/JOwRsZt/ENnZEQOu0Zjjosyofg0tSu25rSvz1/bMppF1ia51jI+CIwLdDjMGkjLEMG/xFdu560UNIBCipYvxODSImSwS2D11QyLtgAOme6H9sm5u2UvSGUKsCV8Cl9XFCoP5ofkhU5yS5bMXwuSqD11sI/JoYSwUjhyOK4ZMzZLqxwAU53BSgw4QsZc0OIBgj0bSmOFTElJ9g6lV1BJj4hAoGBAPxHP+2iuhAk0+g3/zvn+W0qhEWXAqOSPv5Vc3RxtCkxmdk3bjiEyNy+V2z4lWFus6DhUbXrwnXZFhabLe7eRaFs2pggtkOBeS/cH/cquJqPIAaJk1ywb/B7jXR1IhVqiWPwea9SdFT6ifrfj3I+r/3e12R5njkzLxarW+aE6UTDAoGBAOzIN3+WnBnhUe7oCDo9Hq0Nkgl2gnsXQpOgY3+NTpzUXr2u2D6lW5OV8kXo2D7KPPV6RyiPCmQeltHoBquJpjb09DtFcPDBztZ5Oyfi8HZ9VRfxYUgI9A2z51abwrcLsqiwgHWNZDcx7apZv1XBnyA7uozkzOVdBnpyNqsh6XwxAoGAFCLfwGj/rvAxldR6Y961Mvh0zFUsWb//lS9e4sl8ltklrYfWHV7D5ZKvybvhuIAsiEfTzNB/mgZ00+MY/HrzVdopbIEX6zV3ZdGNH55iU7ve04bBo2FV3f0OomADE3CIfNih7dOIA4xn3QVhHr5QL9RMnvFyJqCmPon48OJk+B8CgYAluQwmdo8uDFiqUCsnZB9kAJp4iXnmNGF19YKEirWwhKEHZTisWlnJnIT5oEv7TOsDTNTTYF6ObtUmYenWIFrrvIDAhKKmqKnUP4IuK5iVuAf7hYgzGsIPAM9lW/Fcii+Pcnc0wy7pIDo1/pBeEDfSwdFJqxX9oKFslo+hGgkBsQKBgGbaiAvGn5WHHkNNCykvACsx39TNEKoq0CnQcWoJSboP+/gfodJ+RxaxBHebrAMjvFC6ejMLnc+28byDAsRyTRTr6S9miFaysSBeg67S19V28sdx0EN4lpTy5ZZDeZH5kcOoxhzA6sCF8gBuXMvO7mrdYoC8asMvP2bnuCAkeoUO",

    //异步通知地址
    'notify_url' => "http://qipei.mumarenkj.com/agent/ali/notify_url.php",

    //同步跳转
//		'return_url' => "http://qipei.mumarenkj.com/agent/ali/return_url.php",
    'return_url' => "",

    //编码格式
    'charset' => "UTF-8",

    //签名方式
    'sign_type'=>"RSA2",

    //支付宝网关
    'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

    //支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
    'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAyuyfzp1EVejNJxt4fcHJII8A/pmb1jKWFfEf7XrvYyp3sp9+ySs1T99Vi6gbUBJZVY/JpU5OWUTC3TOn/r9JvcbZVpLjsfQEUDmaGy8TLeuSGeqwFvvnS0orFswxswRkw2mptU9x7O1SnyZ/XdoxTmEqAP7u6T2M9Ub2pKtycRIURFz4o4bv1kOX4ntL8b9KEZowKHbxDAPQwWWYLm5tBNHSZELWXeSAYb5VTbSvZ8v2+b7munYdxMLZTasHKO3mDrLRb15vBA6cJki9s+zj+kD5/3tw14p8AOZelu0uQGEmwC1zY21gHumMVF1vzGkT9l+RN630GuW62Sd5r+X2+wIDAQAB",
);