<?php

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * This function uses type hints now (PHP 7+ only), but it was originally
 * written for PHP 5 as well.
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException("Length must be a positive integer");
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces []= $keyspace[random_int(0, $max)];
    }
    return implode('', $pieces);
}
//usage
/*
$a = random_str(32);
$b = random_str(8, 'abcdefghijklmnopqrstuvwxyz');
$c = random_str();
*/

/*
get a token is not used elsewhere in the table
*/
function get_uniqueToken($db, $tableName)
{
	$uniqueToken = random_str(20);
	$query ="SELECT * FROM `$tableName` WHERE `uniqueToken` LIKE '$uniqueToken'";
	echo $query;
	$result = $db->query($query);
	if ($row = $result->fetch_row()) {
    return get_uniqueToken($db,$tableName);
	} else {
    return $uniqueToken;
	}
}
?>
