<?php
function find_triplet($arr, $val) {
    sort($arr);
    $n = count($arr);
    for ($i = 0; $i < $n - 2; $i++) {
        $l = $i + 1;
        $r = $n - 1;
        while ($l < $r) {
            $current_sum = $arr[$i] + $arr[$l] + $arr[$r];
            if ($current_sum == $val) {
                return [$arr[$i], $arr[$l], $arr[$r]];
            } elseif ($current_sum < $val) {
                $l++;
            } else {
                $r--;
            }
        }
    }
    return false;
}

// Example usage
$arr = [12, 3, 4, 1, 6, 9];
$val = 24;
$result = find_triplet($arr, $val);
if ($result) {
    echo "{" . implode(", ", $result) . "}";  // Output: {12, 3, 9}
} else {
    echo "False";
}
?>
