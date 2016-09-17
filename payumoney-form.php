<?php

$MERCHANT_KEY = "gtKFFx";
$SALT = "eCwWELxi";

//$PAYU_BASE_URL = "https://test.payu.in"; // LIVE mode
$PAYU_BASE_URL = "https://test.payu.in"; // TEST mode

$posted = [
    'key' => $MERCHANT_KEY,
    'txnid' => substr(hash('sha256', mt_rand() . microtime()), 0, 20),
    'amount' => 1000.33,
    'productinfo' => 'test-product',
    'firstname' => 'test-user-payu',
    'email' => 'test-user@payu.com',
    'phone' => '1234567899',
    'surl' => 'http://test-url/payment/success.php',
    'furl' => 'http://test-url/payment/failure.php',
    'service_provider' => 'payu_paisa', // <================= DONT send this in TEST mode
];

// Hash Sequence
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

$hashVarsSeq = explode('|', $hashSequence);
$hash_string = '';

foreach($hashVarsSeq as $hash_var) {
  $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
  $hash_string .= '|';
}

$hash_string .= $SALT;
$posted['hash'] = $hash = strtolower(hash('sha512', $hash_string));
$action = $PAYU_BASE_URL . '/_payment';

?>
<html>
  <body>
    <h2>PayUmoney mandatory fields</h2>

<pre>
<?php
    foreach ($posted as $key => $value){
        echo "$key => $value<br />";
    }
?>
</pre>
<br />

    <form action="<?php echo $action; ?>" method="post" name="payuForm">
        <input type="hidden" name="key"         value="<?php echo $posted["key"        ] ?>" />
        <input type="hidden" name="hash"        value="<?php echo $posted["hash"       ] ?>" />
        <input type="hidden" name="txnid"       value="<?php echo $posted["txnid"      ] ?>" />
        <input type="hidden" name="amount"      value="<?php echo $posted["amount"     ] ?>" />
        <input type="hidden" name="productinfo" value="<?php echo $posted["productinfo"] ?>" />
        <input type="hidden" name="firstname"   value="<?php echo $posted["firstname"  ] ?>" />
        <input type="hidden" name="email"       value="<?php echo $posted["email"      ] ?>" />
        <input type="hidden" name="phone"       value="<?php echo $posted["phone"      ] ?>" />
        <input type="hidden" name="surl"        value="<?php echo $posted["surl"       ] ?>" />
        <input type="hidden" name="furl"        value="<?php echo $posted["furl"       ] ?>" />

        <input type="hidden" name="service_provider" value="<?php echo $posted["service_provider"] ?>" />
        <input type="submit" value="Proceed with PayUmoney gateway" />
    </form>

<!--
Error if not all mandatory fields are set

Error Reason
One or more mandatory parameters are missing in the transaction request.

Corrective Action
Please ensure that you send all mandatory parameters in the transaction request to PayU.
Mandatory parameters which must be sent in the transaction are:
key, txnid, amount, productinfo, firstname, email, phone, surl, furl, hash
.
The parameters which you have actually sent in the transaction are:
key, txnid, amount, productinfo, firstname, email, phone.

Mandatory parameter missing from your transaction request are:
surl, hash.

Please re-initiate the transaction with all the mandatory parameters.

-->

  </body>
</html>
