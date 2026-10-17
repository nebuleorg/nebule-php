<?php

const LIB_LOCAL_OBJECTS_FOLDER = '/home/bachue/code.master.nebule.org/o';
const PUBL_KEY = '/home/bachue/code.master.nebule.org/o/7e393926f0579282688fb2c5b30a24d2e62eec452e5b38940c9b266f8d80b760.sha2.256';
const PRIV_KEY = '/home/bachue/code.master.nebule.org/o/e8e48b759421a54560fb1363c9a0f7b81f6e6416bc50ff74e3330ebb23bfd353.sha2.256';
const PRIV_PWD = 'test';
#const DATA = '8b75e5405c8bb9b8760407333ca59169e96816719fdfa2c8e49d21b394745a200f1b5285c318c89de9a283110f27dd3928d3c53faa01e455cd35fe56e85b9149';
const DATA = 'nebule:link/2:0_0>020251008203808/l>7e393926f0579282688fb2c5b30a24d2e62eec452e5b38940c9b266f8d80b760.sha2.256>970bdb5df1e795929c71503d578b1b6bed601bb65ed7b8e4ae77dd85125d7864.sha2.256>5312dedbae053266a3556f44aba2292f24cdf1c3213aa5b4934005dd582aefa0.sha2.256';

$privateKeyFile = file_get_contents(PRIV_KEY, false, null, 0, 10000);
$privateKey = openssl_pkey_get_private($privateKeyFile, PRIV_PWD);
$publicKeyFile = file_get_contents(PUBL_KEY, false, null, 0, 10000);
$ressource = openssl_pkey_get_public($publicKeyFile);


$signature1 = '';
$signatureBin1 = '';
$hashData1 = hash('sha256', DATA);
$hashDataBin1 = pack("H*", $hashData1);
if (openssl_private_encrypt($hashDataBin1, $signatureBin1, $privateKey, OPENSSL_PKCS1_PADDING)) {
    $signature1 = bin2hex($signatureBin1);
    echo '1 : ' . $signature1 . "\n";
}
else
    echo "1 : err sign\n";

echo "\n";



$signature2 = '';
$signatureBin2 = '';
if (openssl_sign(DATA, $signatureBin2, $privateKey, OPENSSL_ALGO_SHA256)) {
    $signature2 = bin2hex($signatureBin2);
    echo '2 : ' . $signature2 . "\n";
}
else
    echo "2 : err sign\n";

$signatureBin1 = pack("H*", $signature1);
echo "\n";
if (openssl_verify(DATA, $signatureBin1, $ressource, OPENSSL_ALGO_SHA256))
    echo "1 : ok verify\n";
else
    echo "1 : err verify\n";

$signatureBin2 = pack("H*", $signature2);
if (openssl_verify(DATA, $signatureBin2, $ressource, OPENSSL_ALGO_SHA256))
    echo "2 : ok verify\n";
else
    echo "2 : err verify\n";
