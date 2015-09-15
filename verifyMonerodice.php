#!/usr/bin/php
<?php
/*
 * Verify the roll results of Monerodice.net
 *
 * required inputs:
 * serverHash = $argv[1]
 * serverSeed = $argv[2]
 * userSeed = $argv[3]
 * nonce = $argv[4]
 *
 * outputs:
 * server hash correctness
 * roll result
*/

if(count($argv) != 5){
    print "useage: php verifyMonerodice.php serverHash serverSeed userSeed nonce \n";
    exit;
}

if(hash('sha256', $argv[2]) == $argv[1]){  //check if hashed serverSeed matches serverHash
    print "Server Hash is correct! \n";
}else{
    print "Server Hash is incorrect! \n";
}

//simulate dice roll to get the exact roll result
print "Roll result is: " . rollDice($argv[2], $argv[3].'_'.$argv[4]);

//function which executes dice roll based on your input
function rollDice($server_seed, $secret)
{
    $hash = hash_hmac('sha512', $server_seed, $secret);

    for($i = 0; $i < strlen($hash); $i += 5)
    {
        $sub = substr($hash, $i, 5);
        if(strlen($sub) == 5)
        {
            $decimal_number = hexdec($sub);

            if($decimal_number < 1000000)
            {
                $decimal_fourc = bcmod($decimal_number, 10000);
                $final_decimal = bcdiv($decimal_fourc, 100, 2);
                return $final_decimal;
            }
        }
        else
        {
            break;
        }
    }
}
?>
