<?php
function letter_frequency($s) {
    $count = array_count_values(str_split($s));
    $filtered = array_filter($count, function($freq) {
        return $freq > 3;
    });

    if (empty($filtered)) {
        return "NONE";
    }

    uksort($filtered, function($a, $b) use ($filtered) {
        if ($filtered[$a] == $filtered[$b]) {
            return ord($a) - ord($b);
        }
        return $filtered[$b] - $filtered[$a];
    });

    return implode('', array_keys($filtered));
}

// Example usage
$input_str = "abbababbabkeleeklkel";
echo letter_frequency($input_str);  // Output: bae
?>
